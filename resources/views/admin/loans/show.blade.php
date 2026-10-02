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

    /* Modern Glassmorphism Confirmation Modals */
    .glass-modal .modal-content {
        background-color: var(--dash-card-bg, #ffffff) !important;
        backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.1)) !important;
        border-radius: 20px;
        box-shadow: var(--dash-card-shadow, 0 20px 40px rgba(0, 0, 0, 0.2));
        color: var(--text-main, #0f172a);
    }
    .glass-modal .modal-header, 
    .glass-modal .modal-footer {
        border-color: var(--dash-card-border, rgba(0, 0, 0, 0.08)) !important;
    }
    .glass-modal .btn-close {
        filter: var(--btn-close-filter, none);
    }
    .modal-icon-badge {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .modal-icon-badge.success {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }
    .modal-icon-badge.danger {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
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

        {{-- Action Buttons (Triggers Modals) --}}
        <div class="d-flex align-items-center justify-content-end gap-2">
            <button type="button" class="btn-reject-glass" data-bs-toggle="modal" data-bs-target="#rejectLoanModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                <span>Reject Loan</span>
            </button>

            <button type="button" class="btn-approve-glass" data-bs-toggle="modal" data-bs-target="#approveLoanModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Approve Loan</span>
            </button>
        </div>
    </div>
</div>

{{-- APPROVE LOAN MODAL --}}
<div class="modal fade glass-modal" id="approveLoanModal" tabindex="-1" aria-labelledby="approveLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-body pt-4">
                <div class="modal-icon-badge success mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Approve Application?</h4>
                <p class="text-muted small mb-0 px-3">
                    Are you sure you want to approve this loan application for <strong>{{ $loan->user->name ?? 'this applicant' }}</strong> amounting to <strong>₱{{ number_format($loan->total_payable ?? 0, 2) }}</strong>?
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-approve-glass">
                        Yes, Approve Loan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- REJECT LOAN MODAL --}}
<div class="modal fade glass-modal" id="rejectLoanModal" tabindex="-1" aria-labelledby="rejectLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-body pt-4 text-center">
                <div class="modal-icon-badge danger mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Reject Application?</h4>
                <p class="text-muted small mb-3 px-3">
                    Are you sure you want to reject this loan application?
                </p>

                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" id="rejectForm">
                    @csrf
                    <div class="text-start mb-3">
                        <label for="rejection_reason" class="form-label small fw-semibold text-muted mb-1">Rejection Reason (Optional):</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Provide a reason for the applicant..." style="background: rgba(0, 0, 0, 0.03); border-color: var(--dash-card-border); color: var(--text-main); font-size: 0.875rem; border-radius: 12px;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="rejectForm" class="btn-reject-glass" style="background: #ef4444; color: #ffffff;">
                    Yes, Reject Loan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection