@extends('layouts.app')

@section('title', 'Review Loan Application')

@section('content')
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow);
    }
    .dash-title {
        color: var(--text-main) !important;
    }
    .dash-subtext {
        color: var(--text-muted) !important;
    }
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.12);
        border: 1px solid rgba(100, 116, 139, 0.25);
        color: var(--text-main);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.25);
        color: var(--text-main);
    }
    .btn-approve {
        background: #10b981;
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.2s ease;
    }
    .btn-approve:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-reject {
        background: #ef4444;
        border: none;
        color: #ffffff;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.2s ease;
    }
    .btn-reject:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
</style>

<div class="mb-3">
    <a href="{{ route('admin.loans.index') }}" class="glass-btn-back mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        <span>Back to Loan Management</span>
    </a>
    <h2 class="fw-bold dash-title mb-1">Review Loan Application</h2>
    <p class="dash-subtext mb-0">Evaluate details before approving or rejecting this application.</p>
</div>

<div class="dash-card p-4 mb-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <span class="dash-subtext small d-block">Applicant</span>
            <span class="fw-bold dash-title fs-6">{{ $loan->user->name ?? 'N/A' }}</span>
            <span class="dash-subtext small d-block">({{ $loan->user->email ?? 'No email' }})</span>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small d-block">Loan Type</span>
            <span class="fw-semibold dash-title">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</span>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small d-block">Applied Date</span>
            <span class="fw-semibold dash-title">{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</span>
        </div>
    </div>

    <hr style="border-color: var(--dash-card-border);">

    <div class="row g-4">
        <div class="col-md-3">
            <span class="dash-subtext small d-block">Principal Amount</span>
            <span class="fw-bold fs-5 text-success">₱{{ number_format($loan->amount ?? $loan->principal_amount ?? 0, 2) }}</span>
        </div>
        <div class="col-md-3">
            <span class="dash-subtext small d-block">Interest</span>
            <span class="fw-bold fs-5 dash-title">₱{{ number_format($loan->interest_amount ?? 0, 2) }}</span>
        </div>
        <div class="col-md-3">
            <span class="dash-subtext small d-block">Total Payable</span>
            <span class="fw-bold fs-5" style="color: #0284c7 !important;">₱{{ number_format($loan->total_payable ?? 0, 2) }}</span>
        </div>
        <div class="col-md-3">
            <span class="dash-subtext small d-block">Monthly Payment</span>
            <span class="fw-bold fs-5 dash-title">₱{{ number_format($loan->monthly_payment ?? 0, 2) }}</span>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="d-flex gap-3">
    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="flex-fill">
        @csrf
        <button type="submit" class="btn btn-approve w-100">Approve Loan</button>
    </form>
    <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="flex-fill">
        @csrf
        <button type="submit" class="btn btn-reject w-100">Reject Loan</button>
    </form>
</div>
@endsection