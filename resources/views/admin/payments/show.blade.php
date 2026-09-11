@extends('layouts.app')

@section('title', 'Review Payment')

@section('content')
<h4 class="mb-4">Review Payment</h4>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Payer:</strong><br>{{ $payment->user->name }} ({{ $payment->user->email }})</div>
            <div class="col-md-4"><strong>Loan:</strong><br>#{{ $payment->paymentSchedule->loan_id }} — Month {{ $payment->paymentSchedule->month_number }}</div>
            <div class="col-md-4"><strong>Payment Date:</strong><br>{{ $payment->payment_date->format('M d, Y') }}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4"><strong>GCash Reference #:</strong><br>{{ $payment->gcash_reference_number }}</div>
            <div class="col-md-4"><strong>Amount Submitted:</strong><br>₱{{ number_format($payment->amount, 2) }}</div>
            <div class="col-md-4"><strong>Installment Total Due:</strong><br>₱{{ number_format($payment->paymentSchedule->totalDue(), 2) }}</div>
        </div>
        <hr>
        <strong>Proof of Payment:</strong><br>
        <a href="{{ route('payments.proof', $payment) }}" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
            View Uploaded Receipt
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
            @csrf
            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this payment as verified?')">
                Verify Payment
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-danger w-100" data-bs-toggle="collapse" data-bs-target="#rejectForm">
            Reject Payment
        </button>
    </div>
</div>

<div class="collapse mt-3" id="rejectForm">
    <div class="card card-body">
        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Rejection Reason</label>
                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Confirm Rejection</button>
        </form>
    </div>
</div>
@endsection
