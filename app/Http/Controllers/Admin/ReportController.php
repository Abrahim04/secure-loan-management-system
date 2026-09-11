<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\PaymentSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_loans' => Loan::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'completed_loans' => Loan::where('status', 'completed')->count(),
            'overdue_installments' => PaymentSchedule::where('status', 'overdue')->count(),
            'pending_loans' => Loan::where('status', 'pending')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_penalties_collected' => Penalty::sum('amount'),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function loans(Request $request): View
    {
        $status = $request->input('status', 'all');

        $loans = Loan::with(['user', 'loanType'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest('applied_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.reports.loans', compact('loans', 'status'));
    }

    public function payments(): View
    {
        $payments = Payment::with(['user', 'paymentSchedule.loan'])
            ->latest('payment_date')
            ->paginate(25);

        return view('admin.reports.payments', compact('payments'));
    }

    public function penalties(): View
    {
        $penalties = Penalty::with('paymentSchedule.loan.user')
            ->latest('applied_at')
            ->paginate(25);

        return view('admin.reports.penalties', compact('penalties'));
    }
}
