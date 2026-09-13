<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Mfa;
use App\Models\SecurityEvent;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MfaController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        $resendCooldown = $this->currentCooldown($request, 'mfa_resend_count', 'mfa_last_resend_at');

        return view('auth.mfa-verify', compact('resendCooldown'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('mfa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);
        $maxAttempts = (int) SystemSetting::get('otp_max_attempts', 3);

        $otp = Mfa::where('user_id', $user->id)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otp || ! $otp->isValid()) {
            SecurityEvent::record($user->id, 'failed_mfa', 'Attempted verification with expired/missing OTP');

            throw ValidationException::withMessages([
                'otp_code' => 'This code has expired. Please request a new one.',
            ]);
        }

        if ($otp->attempts >= $maxAttempts) {
            SecurityEvent::record($user->id, 'failed_mfa', 'Exceeded max OTP attempts');

            throw ValidationException::withMessages([
                'otp_code' => 'Too many incorrect attempts. Please request a new code.',
            ]);
        }

        if ($otp->otp_code !== $request->input('otp_code')) {
            $otp->increment('attempts');
            SecurityEvent::record($user->id, 'failed_mfa', 'Incorrect OTP entered');

            // A failed guess counts toward the same combined threshold as a resend click.
            $this->registerAttempt($request, 'mfa_resend_count', 'mfa_last_resend_at');

            throw ValidationException::withMessages([
                'otp_code' => 'Incorrect code. Please try again.',
            ]);
        }

        // Success
        $otp->update(['is_used' => true]);
        $user->update(['last_login_at' => now()]);

        $request->session()->forget(['mfa_user_id', 'mfa_otp_sent_at', 'mfa_resend_count', 'mfa_last_resend_at']);
        $request->session()->regenerate();

        Auth::login($user);

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('dashboard')
        );
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('mfa_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $this->assertNotThrottled($request, 'mfa_resend_count', 'mfa_last_resend_at');

        $user = User::findOrFail($userId);
        $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
        $otp = Mfa::generateFor($user, $otpExpiry);

        // Already-authenticated-enough context (a valid pending MFA session exists),
        // so a specific error here carries no enumeration risk.
        try {
            Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));
        } catch (\Exception $e) {
            Log::error('OTP Mail Delivery Failed: ' . $e->getMessage());

            throw ValidationException::withMessages([
                'otp_code' => 'Unable to send OTP email. Please verify your email address or try again later.',
            ]);
        }

        // Only reached on success — a failed send does not cost the user
        // one of their free resend attempts.
        $request->session()->put('mfa_otp_sent_at', now()->timestamp);
        $this->registerAttempt($request, 'mfa_resend_count', 'mfa_last_resend_at');

        return back()->with('status', 'A new code has been sent to your email.');
    }

    /**
     * Shared throttle logic for login MFA and password-reset OTP flows:
     * the first `otp_free_resend_attempts` resend clicks or failed code
     * submissions are unrestricted; only once that combined count is
     * reached does a cooldown between further resends apply.
     */
    private function assertNotThrottled(Request $request, string $countKey, string $lastAtKey): void
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $count = $request->session()->get($countKey, 0);

        if ($count < $freeLimit) {
            return;
        }

        $cooldown = (int) SystemSetting::get('otp_resend_cooldown_seconds', 60);
        $lastAt = $request->session()->get($lastAtKey, 0);
        $elapsed = now()->timestamp - $lastAt;

        if ($elapsed < $cooldown) {
            $wait = $cooldown - $elapsed;

            throw ValidationException::withMessages([
                'otp_code' => "Please wait {$wait} more second(s) before requesting a new code.",
            ]);
        }
    }

    private function registerAttempt(Request $request, string $countKey, string $lastAtKey): void
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $newCount = $request->session()->increment($countKey);

        if ($newCount >= $freeLimit) {
            $request->session()->put($lastAtKey, now()->timestamp);
        }
    }

    private function currentCooldown(Request $request, string $countKey, string $lastAtKey): int
    {
        $freeLimit = (int) SystemSetting::get('otp_free_resend_attempts', 5);
        $count = $request->session()->get($countKey, 0);

        if ($count < $freeLimit) {
            return 0;
        }

        $cooldown = (int) SystemSetting::get('otp_resend_cooldown_seconds', 60);
        $lastAt = $request->session()->get($lastAtKey, 0);
        $elapsed = now()->timestamp - $lastAt;

        return max(0, $cooldown - $elapsed);
    }
}