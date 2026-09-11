<?php

namespace App\Http\Controllers;

use App\Models\PaymentSchedule;
use Illuminate\View\View;

class PaymentScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = PaymentSchedule::whereHas('loan', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['loan.loanType'])
            ->orderBy('due_date')
            ->paginate(20);

        return view('payment-schedule.index', compact('schedules'));
    }
}
