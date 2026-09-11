<?php

namespace App\Http\Controllers;

use App\Models\LoanType;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $openSchedules = PaymentSchedule::whereHas('loan', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', '!=', 'paid')
            ->with('loan.loanType')
            ->orderBy('due_date')
            ->get();

        $outstandingBalance = $openSchedules->sum(function ($schedule) {
            return $schedule->amount_due + $schedule->penalty_amount - $schedule->amount_paid;
        });

        $nextDue = $openSchedules->first();

        $unreadNotifications = $user->appNotifications()->where('is_read', false)->latest()->take(3)->get();
        $unreadCount = $user->appNotifications()->where('is_read', false)->count();

        $loanTypes = LoanType::where('is_active', true)->get();

        // --- Repayment progress across all active loans ---
        $activeLoans = $user->loans()->where('status', 'active')->with('paymentSchedules')->get();
        $totalPayableActive = $activeLoans->sum('total_payable');
        $totalPaidActive = $activeLoans->flatMap->paymentSchedules->sum('amount_paid');
        $repaymentPercent = $totalPayableActive > 0
            ? min(100, round(($totalPaidActive / $totalPayableActive) * 100))
            : 0;

        // --- Quick payment history (last 5 submissions, any status) ---
        $recentPayments = Payment::where('user_id', $user->id)
            ->with('paymentSchedule.loan.loanType')
            ->latest()
            ->take(5)
            ->get();

        // --- Credit limit / borrowing status (simplified heuristic, not a real credit model) ---
        $hasOverdue = $openSchedules->contains('status', 'overdue');
        $totalActivePrincipal = $activeLoans->sum('principal_amount');
        $maxLoanTypeLimit = LoanType::where('is_active', true)->max('max_amount') ?? 0;
        $availableCapacity = max(0, $maxLoanTypeLimit - $totalActivePrincipal);
        $isEligibleForReloan = ! $hasOverdue && $availableCapacity > 0;

        return view('dashboard', compact(
            'outstandingBalance',
            'nextDue',
            'unreadNotifications',
            'unreadCount',
            'loanTypes',
            'repaymentPercent',
            'totalPayableActive',
            'totalPaidActive',
            'recentPayments',
            'hasOverdue',
            'availableCapacity',
            'isEligibleForReloan'
        ));
    }
}
