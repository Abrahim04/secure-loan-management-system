@extends('layouts.app')

@section('title', 'Pay via GCash')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-1">Pay via GCash</h4>
                <p class="text-muted">Installment #{{ $paymentSchedule->month_number }} — Due {{ $paymentSchedule->due_date->format('M d, Y') }}</p>

                <div class="alert alert-secondary">
                    <div class="d-flex justify-content-between">
                        <span>Amount Due</span>
                        <span>₱{{ number_format($paymentSchedule->amount_due, 2) }}</span>
                    </div>
                    @if ($paymentSchedule->penalty_amount > 0)
                        <div class="d-flex justify-content-between text-danger">
                            <span>Penalty</span>
                            <span>₱{{ number_format($paymentSchedule->penalty_amount, 2) }}</span>
                        </div>
                    @endif
                    <hr class="my-1">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Due</span>
                        <span>₱{{ number_format($paymentSchedule->totalDue(), 2) }}</span>
                    </div>
                </div>

                <p class="small text-muted">
                    Pay this amount to our GCash account externally, then enter the reference number and
                    upload your receipt below. We never ask for your GCash MPIN, password, or OTP.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('payments.store', $paymentSchedule) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">GCash Reference Number</label>
                        <input type="text" name="gcash_reference_number" class="form-control" value="{{ old('gcash_reference_number') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount Paid (₱)</label>
                        <input type="number" step="0.01" name="amount" class="form-control"
                               value="{{ old('amount', $paymentSchedule->totalDue()) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->toDateString()) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Proof of Payment (screenshot or receipt)</label>
                        <input type="file" name="proof_of_payment" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
