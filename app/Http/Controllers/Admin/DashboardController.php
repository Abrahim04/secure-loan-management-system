<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pendingLoansCount' => Loan::where('status', 'pending')->count(),
            'pendingPaymentsCount' => Payment::where('status', 'pending')->count(),
            'overdueCount' => PaymentSchedule::where('status', 'overdue')->count(),
            'recentSecurityEventsCount' => SecurityEvent::where('created_at', '>=', now()->subDay())->count(),

            // Total Portfolio Summary
            'totalReleased' => Loan::whereIn('status', ['active', 'completed'])->sum('principal_amount'),
            'totalCollected' => Payment::where('status', 'verified')->sum('amount'),

            // Recent Activity
            'recentActivity' => AuditLog::with('user')->latest()->take(5)->get(),

            // User Management summary
            'activeUsersCount' => User::where('role', 'user')->where('is_active', true)->count(),
            'blockedUsersCount' => User::where('role', 'user')->where('is_active', false)->count(),
            'unverifiedUsersCount' => User::where('role', 'user')->whereNull('email_verified_at')->count(),
        ]);
    }
}
