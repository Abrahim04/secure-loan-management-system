<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentVerificationController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['user', 'paymentSchedule.loan'])
            ->where('status', 'pending')
            ->oldest('payment_date')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['user', 'paymentSchedule.loan.loanType']);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Payment $payment): RedirectResponse
    {
        abort_unless($payment->status === 'pending', 400, 'Only pending payments can be verified.');

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $schedule = $payment->paymentSchedule;
            $schedule->increment('amount_paid', $payment->amount);
            $schedule->refresh();

            $totalOwed = $schedule->amount_due + $schedule->penalty_amount;
            $schedule->status = $schedule->amount_paid >= $totalOwed ? 'paid' : 'partially_paid';
            $schedule->save();

            // If every installment on this loan is now paid, mark the loan completed.
            $loan = $schedule->loan;
            $allPaid = $loan->paymentSchedules()->where('status', '!=', 'paid')->doesntExist();
            if ($allPaid) {
                $loan->update(['status' => 'completed']);
            }

            Notification::create([
                'user_id' => $payment->user_id,
                'type' => 'payment_verified',
                'title' => 'Payment Verified',
                'message' => "Your payment of ₱{$payment->amount} (Ref: {$payment->gcash_reference_number}) has been verified.",
            ]);

            AuditLog::record(
                auth()->id(),
                'Verified Payment',
                'Payment',
                $payment->id,
                "Amount: ₱{$payment->amount}, Mode: GCash, Ref: {$payment->gcash_reference_number}"
            );
        });

        return redirect()->route('admin.payments.index')->with('status', 'Payment verified.');
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($payment->status === 'pending', 400, 'Only pending payments can be rejected.');

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            Notification::create([
                'user_id' => $payment->user_id,
                'type' => 'payment_rejected',
                'title' => 'Payment Rejected',
                'message' => "Your payment (Ref: {$payment->gcash_reference_number}) was rejected. Reason: {$validated['rejection_reason']}",
            ]);

            AuditLog::record(auth()->id(), 'Rejected Payment', 'Payment', $payment->id, $validated['rejection_reason']);
        });

        return redirect()->route('admin.payments.index')->with('status', 'Payment rejected.');
    }
}
