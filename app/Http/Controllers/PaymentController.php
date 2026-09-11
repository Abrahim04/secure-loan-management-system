<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\PaymentSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(PaymentSchedule $paymentSchedule): View
    {
        $this->authorizeOwnership($paymentSchedule);

        abort_if($paymentSchedule->status === 'paid', 400, 'This installment is already fully paid.');

        return view('payments.create', compact('paymentSchedule'));
    }

    public function store(Request $request, PaymentSchedule $paymentSchedule): RedirectResponse
    {
        $this->authorizeOwnership($paymentSchedule);

        abort_if($paymentSchedule->status === 'paid', 400, 'This installment is already fully paid.');

        $validated = $request->validate([
            'gcash_reference_number' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            // Only the receipt image/PDF is stored — never GCash credentials or OTP.
            'proof_of_payment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->file('proof_of_payment')->store('proof_of_payments', 'local');

        $payment = Payment::create([
            'payment_schedule_id' => $paymentSchedule->id,
            'user_id' => auth()->id(),
            'gcash_reference_number' => $validated['gcash_reference_number'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'proof_of_payment_path' => $path,
            'status' => 'pending',
        ]);

        AuditLog::record(auth()->id(), 'Submitted Payment', 'Payment', $payment->id);

        return redirect()->route('loans.show', $paymentSchedule->loan_id)
            ->with('status', 'Your payment has been submitted and is pending verification.');
    }

    /**
     * Stream the proof-of-payment file to its owner or an admin only.
     */
    public function proof(Payment $payment): StreamedResponse
    {
        abort_unless(
            $payment->user_id === auth()->id() || auth()->user()->isAdmin(),
            403
        );

        abort_unless(Storage::disk('local')->exists($payment->proof_of_payment_path), 404);

        return Storage::disk('local')->response($payment->proof_of_payment_path);
    }

    protected function authorizeOwnership(PaymentSchedule $paymentSchedule): void
    {
        abort_unless($paymentSchedule->loan->user_id === auth()->id(), 403);
    }
}
