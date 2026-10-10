<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoanType;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingsController extends Controller
{
    public function general(): View
    {
        $settings = [
            'otp_expiry_minutes' => SystemSetting::get('otp_expiry_minutes', 5),
            'otp_max_attempts' => SystemSetting::get('otp_max_attempts', 3),
            'otp_resend_cooldown_seconds' => SystemSetting::get('otp_resend_cooldown_seconds', 60),
            'otp_free_resend_attempts' => SystemSetting::get('otp_free_resend_attempts', 5),
            'maintenance_mode' => SystemSetting::get('maintenance_mode', '0'),
            'admin_notify_loan_application' => SystemSetting::get('admin_notify_loan_application', '1'),
            'admin_notify_large_repayment' => SystemSetting::get('admin_notify_large_repayment', '1'),
        ];

        $penaltySettings = [
            'penalty_per_day' => SystemSetting::get('penalty_per_day', 20),
            'grace_period_days' => SystemSetting::get('grace_period_days', 0),
            'max_penalty' => SystemSetting::get('max_penalty', 500),
        ];

        $loanTypes = LoanType::all();

        return view('admin.settings.system', compact('settings', 'penaltySettings', 'loanTypes'));
    }

    public function penalties(): View
    {
        $settings = [
            'penalty_per_day' => SystemSetting::get('penalty_per_day', 20),
            'grace_period_days' => SystemSetting::get('grace_period_days', 0),
            'max_penalty' => SystemSetting::get('max_penalty', 500),
        ];

        return view('admin.settings.penalties', compact('settings'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp_expiry_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'otp_max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'otp_resend_cooldown_seconds' => ['required', 'integer', 'min:10', 'max:600'],
            'otp_free_resend_attempts' => ['required', 'integer', 'min:0', 'max:20'],
            'maintenance_mode' => ['required', 'in:0,1,2'], // Na-update para sa 3 states: 0=Operational, 1=Maintenance, 2=Shutdown
            'admin_notify_loan_application' => ['nullable', 'in:0,1'],
            'admin_notify_large_repayment' => ['nullable', 'in:0,1'],
        ]);

        $validated['admin_notify_loan_application'] = $request->has('admin_notify_loan_application') ? '1' : '0';
        $validated['admin_notify_large_repayment'] = $request->has('admin_notify_large_repayment') ? '1' : '0';

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, (string) $value);
        }

        AuditLog::record(
            auth()->id(),
            'Updated System Settings',
            'SystemSetting',
            null,
            "Updated general system settings and system operational state."
        );

        return back()->with('status', 'System settings updated successfully.');
    }

    public function updateLoanTypes(Request $request): RedirectResponse
    {
        $request->validate([
            'loan_types' => ['required', 'array'],
            'loan_types.*.interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'loan_types.*.max_term_months' => ['required', 'integer', 'min:1', 'max:60'],
            'loan_types.*.min_amount' => ['required', 'numeric', 'min:0'],
            'loan_types.*.max_amount' => ['required', 'numeric', 'min:0', 'gte:loan_types.*.min_amount'],
        ]);

        foreach ($request->loan_types as $id => $data) {
            $loanType = LoanType::find($id);
            if ($loanType) {
                $loanType->update([
                    'interest_rate' => $data['interest_rate'],
                    'max_term_months' => $data['max_term_months'],
                    'min_amount' => $data['min_amount'],
                    'max_amount' => $data['max_amount'],
                ]);
            }
        }

        AuditLog::record(
            auth()->id(),
            'Updated Loan Types Configurations',
            'LoanType',
            null,
            'Updated interest rates, term limits, and amount ranges for loan types.'
        );

        return back()->with('status', 'Loan types configurations updated successfully.');
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
}