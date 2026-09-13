<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\AuditLog;
use App\Models\Mfa;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user', // registration always creates a borrower account
        ]);

        $otpExpiry = (int) SystemSetting::get('otp_expiry_minutes', 5);
        $otp = Mfa::generateFor($user, $otpExpiry);

        try {
            Mail::to($user->email)->send(new OtpMail($otp->otp_code, $otpExpiry));
        } catch (\Exception $e) {
            Log::error('OTP Mail Delivery Failed: ' . $e->getMessage());

            // Roll back the just-created account so the email address is free
            // to retry with — otherwise the user hits "email already taken"
            // on their next attempt despite never having a working account.
            $user->forceDelete();

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Unable to send OTP email. Please verify your email address or try again later.']);
        }

        event(new Registered($user));

        AuditLog::record($user->id, 'Registered Account', 'User', $user->id);

        // Do NOT log the user in yet — registration now requires the same
        // Email OTP MFA step as login, not a bypass straight to the dashboard.
        $request->session()->put('mfa_user_id', $user->id);
        $request->session()->put('mfa_otp_sent_at', now()->timestamp);
        $request->session()->forget(['mfa_resend_count', 'mfa_last_resend_at']);

        return redirect()->route('mfa.verify')
            ->with('status', 'Account created. Please verify your email to continue.');
    }
}