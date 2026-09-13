<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AuditLog;
use App\Models\Mfa;
use App\Models\SecurityEvent;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Enter email
     */
    public function showEmailForm(Request $request): View
    {
        // If a reset for some email is already in progress and has crossed the
        // free-attempts threshold, reflect that cooldown here too — resubmitting
        // the same email from this page is functionally a resend.
        $resendCooldown = $this->currentCooldown($request);

        return view('auth.forgot-password', compact('resendCooldown'));
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $inProgressEmail = $request->session()->get('password_reset_email');
        $isResend = $inProgressEmail && $inProgressEmail === $validated['email'];

        if ($isResend) {
            $this->assertNotThrottled($request);
        }

        $user = User::where('email', $validated['email'])->first();
        $mailFailed = false;

        // Always behave the same way whether or not the account exists,
        // so this endpoint can't be used to enumerate registered emails —
        // including when mail delivery itself fails. A failure is logged
        // server-side, never surfaced, so "account exists but mail broke"
        // can't be distinguished from "no such account" by the response.
        if ($user && $user->is_active) {
            $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
            $otp = Mfa::generateFor($user, $otpExpiry);

            try {
                Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));
            } catch (\Exception $e) {
                Log::error('OTP Mail Delivery Failed: ' . $e->getMessage());
                $mailFailed = true;
            }
        }

        $request->session()->put('password_reset_email', $validated['email']);
        $request->session()->put('password_reset_otp_sent_at', now()->timestamp);
        $request->session()->forget('password_reset_verified');

        if ($isResend) {
            // Only counts toward the free-attempts budget on an actual successful
            // send — a failed delivery shouldn't cost the user a free retry.
            if (! $mailFailed) {
                $this->registerAttempt($request);
            }
        } else {
            // Brand new request for this email — start the counter fresh.
            $request->session()->put('password_reset_resend_count', 0);
            $request->session()->forget('password_reset_last_resend_at');
        }

        return redirect()->route('password.reset.verify')
            ->with('status', 'If that email is registered, a verification code has been sent.');
    }

    /**
     * Step 2: Verify OTP
     */
    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.reset.request');
        }

        $resendCooldown = $this->currentCooldown($request);

        return view('auth.forgot-password-verify', compact('resendCooldown'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $email = $request->session()->get('password_reset_email');
        $user = $email ? User::where('email', $email)->first() : null;

        if (! $user) {
            return redirect()->route('password.reset.request');
        }

        $maxAttempts = (int) SystemSetting::get('otp_max_attempts', 3);

        $otp = Mfa::where('user_id', $user->id)->where('is_used', false)->latest()->first();

        if (! $otp || ! $otp->isValid() || $otp->attempts >= $maxAttempts) {
            SecurityEvent::record($user->id, 'password_reset_attempt', 'Expired/invalid OTP used during password reset');

            throw ValidationException::withMessages([
                'otp_code' => 'This code is invalid or has expired. Please request a new one.',
            ]);
        }

        if ($otp->otp_code !== $request->input('otp_code')) {
            $otp->increment('attempts');
            SecurityEvent::record($user->id, 'password_reset_attempt', 'Incorrect OTP entered during password reset');

            // A failed guess counts toward the same combined threshold as a resend click.
            $this->registerAttempt($request);

            throw ValidationException::withMessages([
                'otp_code' => 'Incorrect code. Please try again.',
            ]);
        }

        $otp->update(['is_used' => true]);
        $request->session()->put('password_reset_verified', true);

        return redirect()->route('password.reset.form');
    }

    /**
     * Step 3: Set new password
     */
    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('password_reset_verified')) {
            return redirect()->route('password.reset.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        if (! $request->session()->get('password_reset_verified')) {
            return redirect()->route('password.reset.request');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $email = $request->session()->get('password_reset_email');
        $user = User::where('email', $email)->firstOrFail();

        $user->update(['password' => Hash::make($validated['password'])]);

        AuditLog::record($user->id, 'Reset Password', 'User', $user->id);
        SecurityEvent::record($user->id, 'password_reset', 'Password successfully reset via forgot-password flow');

        $request->session()->forget([
            'password_reset_email',
            'password_reset_verified',
            'password_reset_otp_sent_at',
            'password_reset_resend_count',
            'password_reset_last_resend_at',
        ]);

        return redirect()->route('login')->with('status', 'Your password has been updated. Please log in.');
    }

    /**
     * Shared throttle logic, mirroring MfaController: the first
     * `otp_free_resend_attempts` resend clicks or failed code submissions
     * are unrestricted; only past that combined count does a cooldown apply.
     */
    private function assertNotThrottled(Request $request): void
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $count = $request->session()->get('password_reset_resend_count', 0);

        if ($count < $freeLimit) {
            return;
        }

        $cooldown = (int) SystemSetting::get('otp_resend_cooldown_seconds', 60);
        $lastAt = $request->session()->get('password_reset_last_resend_at', 0);
        $elapsed = now()->timestamp - $lastAt;

        if ($elapsed < $cooldown) {
            $wait = $cooldown - $elapsed;

            throw ValidationException::withMessages([
                'otp_code' => "Please wait {$wait} more second(s) before requesting a new code.",
            ]);
        }
    }

    private function registerAttempt(Request $request): void
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $newCount = $request->session()->increment('password_reset_resend_count');

        if ($newCount >= $freeLimit) {
            $request->session()->put('password_reset_last_resend_at', now()->timestamp);
        }
    }

    private function currentCooldown(Request $request): int
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $count = $request->session()->get('password_reset_resend_count', 0);

        if ($count < $freeLimit) {
            return 0;
        }

        $cooldown = (int) SystemSetting::get('otp_resend_cooldown_seconds', 60);
        $lastAt = $request->session()->get('password_reset_last_resend_at', 0);
        $elapsed = now()->timestamp - $lastAt;

        return max(0, $cooldown - $elapsed);
    }
}