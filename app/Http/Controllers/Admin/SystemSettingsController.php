<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingsController extends Controller
{
    public function penalties(): View
    {
        $settings = [
            'penalty_per_day' => SystemSetting::get('penalty_per_day', 20),
            'grace_period_days' => SystemSetting::get('grace_period_days', 0),
            'max_penalty' => SystemSetting::get('max_penalty', 500),
        ];

        return view('admin.settings.penalties', compact('settings'));
    }

    public function updatePenalties(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'penalty_per_day' => ['required', 'numeric', 'min:0'],
            'grace_period_days' => ['required', 'integer', 'min:0'],
            'max_penalty' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, (string) $value);
        }

        AuditLog::record(
            auth()->id(),
            'Updated Penalty Policy',
            'SystemSetting',
            null,
            "penalty_per_day={$validated['penalty_per_day']}, grace_period_days={$validated['grace_period_days']}, max_penalty={$validated['max_penalty']}"
        );

        return back()->with('status', 'Penalty policy updated.');
    }

    public function general(): View
    {
        $settings = [
            'otp_expiry_minutes' => SystemSetting::get('otp_expiry_minutes', 5),
            'otp_max_attempts' => SystemSetting::get('otp_max_attempts', 3),
            'otp_resend_cooldown_seconds' => SystemSetting::get('otp_resend_cooldown_seconds', 60),
        ];

        return view('admin.settings.system', compact('settings'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp_expiry_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'otp_max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'otp_resend_cooldown_seconds' => ['required', 'integer', 'min:10', 'max:600'],
        ]);

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, (string) $value);
        }

        AuditLog::record(
            auth()->id(),
            'Updated System Settings',
            'SystemSetting',
            null,
            "otp_expiry_minutes={$validated['otp_expiry_minutes']}, otp_max_attempts={$validated['otp_max_attempts']}, otp_resend_cooldown_seconds={$validated['otp_resend_cooldown_seconds']}"
        );

        return back()->with('status', 'System settings updated.');
    }
}
