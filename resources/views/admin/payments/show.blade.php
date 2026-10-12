@extends('layouts.app')

@section('title', 'Review Payment')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Review Styling -->
<style>
    /* Translucent Glass Dash Card Container - Matched with Audit Logs & Security Events */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    /* Dark Mode Theme Override - Ensures smooth consistent Dark Slate Blue */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Modern Back Pill Button */
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.05);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12));
        color: var(--text-main, #f8fafc);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.15);
        color: var(--text-main, #f8fafc);
        transform: translateX(-3px);
    }

    /* Receipt Pill Action Button */
    .glass-btn-receipt {
        display: inline-flex;
        align-items: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .glass-btn-receipt:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Verify Payment Button: Transparent na may banayad na kulay sa simula, magiging solid green pag hinover */
    .btn-approve-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-approve-glass:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transform: translateY(-1px);
    }

    /* Reject Payment Button: Transparent na may banayad na kulay sa simula, magiging solid red pag hinover */
    .btn-reject-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
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

    /* Modal Cancel Button */
    .btn-modal-cancel {
        background: rgba(100, 116, 139, 0.12) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid rgba(100, 116, 139, 0.3) !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-modal-cancel:hover {
        background: rgba(100, 116, 139, 0.3) !important;
        color: var(--text-main, #f8fafc) !important;
        border-color: rgba(100, 116, 139, 0.5) !important;
        transform: translateY(-1px);
    }

    /* Modern Glassmorphism Confirmation Modals */
    .glass-modal .modal-content {
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.95)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 20px;
        box-shadow: var(--dash-card-shadow, 0 20px 40px rgba(0, 0, 0, 0.4));
        color: var(--text-main, #f8fafc);
    }
    .glass-modal .modal-header, 
    .glass-modal .modal-footer {
        border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
    }
    .glass-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }
    .modal-icon-badge.danger {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    /* Monospace Reference Text */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: var(--text-main, #f8fafc) !important;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('admin.payments.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Collections</span>
        </a>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Review Payment</h2>
        <p class="text-muted small mb-0">Evaluate payment details and proof of payment before verifying or rejecting.</p>
    </div>

    {{-- Information Card --}}
    <div class="dash-card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Payer</span>
                <span class="text-main fw-bold fs-6 d-block">{{ $payment->user->name }}</span>
                <span class="text-muted small">{{ $payment->user->email }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Loan Target</span>
                <span class="text-main fw-bold fs-6">#{{ $payment->paymentSchedule->loan_id }} — Month {{ $payment->paymentSchedule->month_number }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Payment Date</span>
                <span class="fw-bold fs-6 text-main">{{ $payment->payment_date->format('M d, Y') }}</span>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08));">

        <div class="row g-4">
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">GCash Reference #</span>
                <span class="ref-no-text">{{ $payment->gcash_reference_number }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Amount Submitted</span>
                <span class="text-main fw-bold fs-5">₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="col-md-4">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Installment Total Due</span>
                <span class="text-main fw-bold fs-5">₱{{ number_format($payment->paymentSchedule->totalDue(), 2) }}</span>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08));">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="text-muted small text-uppercase fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">Proof of Payment</span>
                <a href="{{ route('payments.proof', $payment) }}" target="_blank" class="glass-btn-receipt">
                    <span>View Uploaded Receipt</span>
                </a>
            </div>

            {{-- Action Buttons Container (Triggers Modals) --}}
            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                <button type="button" class="btn-reject-glass" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                    <span>Reject Payment</span>
                </button>

                <button type="button" class="btn-approve-glass" data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
                    <span>Verify Payment</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- VERIFY PAYMENT MODAL --}}
<div class="modal fade glass-modal" id="verifyPaymentModal" tabindex="-1" aria-labelledby="verifyPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-body pt-4">
                <div class="modal-icon-badge success mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Verify Payment?</h4>
                <p class="text-muted small mb-0 px-3">
                    Are you sure you want to verify this payment amounting to <strong>₱{{ number_format($payment->amount, 2) }}</strong> from <strong>{{ $payment->user->name }}</strong>?
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn-approve-glass">
                        Yes, Verify Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- REJECT PAYMENT MODAL --}}
<div class="modal fade glass-modal" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="m-0">
                @csrf
                <div class="modal-body pt-4 text-center">
                    <div class="modal-icon-badge danger mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <h4 class="fw-bold text-main mb-2">Reject Payment?</h4>
                    <p class="text-muted small mb-3 px-3">
                        Are you sure you want to reject this payment receipt?
                    </p>

                    <div class="text-start mb-3">
                        <label for="rejection_reason" class="form-label small fw-semibold text-muted mb-1">Rejection Reason (Optional):</label>
                        <textarea class="form-control bg-transparent text-main" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Provide a reason for the applicant..." style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.12)); font-size: 0.875rem; border-radius: 12px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-reject-glass">
                        Yes, Reject Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
