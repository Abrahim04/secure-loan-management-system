@extends('layouts.app')

@section('title', 'Review Loan Application')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Review Styling -->
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Modern Back Pill Button */
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.1);
        border: 1px solid rgba(100, 116, 139, 0.25);
        color: var(--text-main, #334155);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.2);
        color: var(--text-main, #0f172a);
        transform: translateX(-3px);
    }

    /* Modern Rounded Action Buttons */
    .btn-approve-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #10b981;
        border: 1px solid #059669;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 22px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-approve-glass:hover {
        background: #059669;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transform: translateY(-1px);
    }

    .btn-reject-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 22px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-reject-glass:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('admin.loans.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Loan Management</span>
        </a>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Review Loan Application</h2>
        <p class="text-muted small mb-0">Evaluate details before approving or rejecting this application.</p>
    </div>

    {{-- Information Card --}}
    <div class="dash-card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Applicant</span>
                <span class="text-main fw-bold fs-6 d-block">{{ $loan->user->name ?? 'N/A' }}</span>
                <span class="text-muted small">{{ $loan->user->email ?? 'No email' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Loan Type</span>
                <span class="text-main fw-bold fs-6">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Applied Date</span>
                <span class="fw-bold fs-6 text-main">{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</span>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--dash-card-border);">

        <div class="row g-4">
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Principal Amount</span>
                <span class="fw-bold fs-5 text-success">₱{{ number_format($loan->amount ?? $loan->principal_amount ?? 0, 2) }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Interest</span>
                <span class="fw-bold fs-5 text-main">₱{{ number_format($loan->interest_amount ?? 0, 2) }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Payable</span>
                <span class="fw-bold fs-5" style="color: #0284c7 !important;">₱{{ number_format($loan->total_payable ?? 0, 2) }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Monthly Payment</span>
                <span class="fw-bold fs-5 text-main">₱{{ number_format($loan->monthly_payment ?? 0, 2) }}</span>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--dash-card-border);">

        {{-- Action Buttons Row --}}
        <div class="d-flex align-items-center justify-content-end gap-2">
            <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-reject-glass" onclick="return confirm('Are you sure you want to reject this loan application?')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span>Reject Loan</span>
                </button>
            </form>

            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-approve-glass" onclick="return confirm('Approve this loan application?')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Approve Loan</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection