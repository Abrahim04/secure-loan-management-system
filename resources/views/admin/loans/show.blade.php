@extends('layouts.app')

@section('title', 'Review Loan')

@section('content')
<h4 class="mb-4">Review Loan Application</h4>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Applicant:</strong><br>{{ $loan->user->name }} ({{ $loan->user->email }})</div>
            <div class="col-md-4"><strong>Loan Type:</strong><br>{{ $loan->loanType->name }}</div>
            <div class="col-md-4"><strong>Applied:</strong><br>{{ $loan->applied_at->format('M d, Y') }}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3"><strong>Principal:</strong><br>₱{{ number_format($loan->principal_amount, 2) }}</div>
            <div class="col-md-3"><strong>Interest:</strong><br>₱{{ number_format($loan->interest_amount, 2) }}</div>
            <div class="col-md-3"><strong>Total Payable:</strong><br>₱{{ number_format($loan->total_payable, 2) }}</div>
            <div class="col-md-3"><strong>Monthly Payment:</strong><br>₱{{ number_format($loan->monthly_payment, 2) }}</div>
        </div>
        @if ($loan->purpose)
            <hr>
            <strong>Purpose:</strong>
            <p class="mb-0">{{ $loan->purpose }}</p>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">
            @csrf
            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this loan and generate its payment schedule?')">
                Approve Loan
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-danger w-100" data-bs-toggle="collapse" data-bs-target="#rejectForm">
            Reject Loan
        </button>
    </div>
</div>

<div class="collapse mt-3" id="rejectForm">
    <div class="card card-body">
        <form method="POST" action="{{ route('admin.loans.reject', $loan) }}">
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
