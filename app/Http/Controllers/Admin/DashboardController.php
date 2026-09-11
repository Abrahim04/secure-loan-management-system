<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use App\Models\SecurityEvent;
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
        ]);
    }
}
