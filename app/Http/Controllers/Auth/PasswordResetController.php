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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Enter email
     */
    public function showEmailForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Always behave the same way whether or not the account exists,
        // so this endpoint can't be used to enumerate registered emails.
        if ($user && $user->is_active) {
            $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
            $otp = Mfa::generateFor($user, $otpExpiry);

            Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));
        }

        $request->session()->put('password_reset_email', $validated['email']);
        $request->session()->put('password_reset_otp_sent_at', now()->timestamp);
        $request->session()->forget('password_reset_verified');

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

        return view('auth.forgot-password-verify');
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

        $request->session()->forget(['password_reset_email', 'password_reset_verified', 'password_reset_otp_sent_at']);

        return redirect()->route('login')->with('status', 'Your password has been updated. Please log in.');
    }
}
