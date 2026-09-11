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
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class MfaController extends Controller
{
    public function show(Request $request): \Illuminate\View\View|RedirectResponse
    {
        if (! $request->session()->has('mfa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.mfa-verify');
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

            throw ValidationException::withMessages([
                'otp_code' => 'Incorrect code. Please try again.',
            ]);
        }

        // Success
        $otp->update(['is_used' => true]);
        $user->update(['last_login_at' => now()]);

        $request->session()->forget(['mfa_user_id', 'mfa_otp_sent_at']);
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

        $cooldown = (int) SystemSetting::get('otp_resend_cooldown_seconds', 60);
        $lastSentAt = $request->session()->get('mfa_otp_sent_at', 0);

        if (now()->timestamp - $lastSentAt < $cooldown) {
            $wait = $cooldown - (now()->timestamp - $lastSentAt);

            throw ValidationException::withMessages([
                'otp_code' => "Please wait {$wait} more second(s) before requesting a new code.",
            ]);
        }

        $user = User::findOrFail($userId);
        $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
        $otp = Mfa::generateFor($user, $otpExpiry);

        Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));

        $request->session()->put('mfa_otp_sent_at', now()->timestamp);

        return back()->with('status', 'A new code has been sent to your email.');
    }
}
