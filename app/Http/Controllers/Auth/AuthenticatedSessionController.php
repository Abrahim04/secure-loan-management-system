<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mfa;
use App\Models\SecurityEvent;
use App\Models\SystemSetting;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Step 1 of login: verify email + password, but do NOT establish a session yet.
     * A valid credential check triggers an OTP email and redirects to MFA verification.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Validate credentials without creating a session (Auth::once acts as a stateless check)
        if (! $user || ! $user->is_active || ! Auth::once($credentials)) {
            SecurityEvent::record($user?->id, 'failed_login', "Failed login attempt for {$credentials['email']}");

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        Auth::logout(); // undo Auth::once's temporary auth state

        $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
        $otp = Mfa::generateFor($user, $otpExpiry);

        Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));

        $request->session()->put('mfa_user_id', $user->id);
        $request->session()->put('mfa_otp_sent_at', now()->timestamp);

        return redirect()->route('mfa.verify');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
