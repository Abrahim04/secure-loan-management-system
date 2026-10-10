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

    /* Receipt Pill Action Button (Wala nang icon) */
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

    /* Modern Rounded Action Buttons (Wala nang check at X icons) */
    .btn-approve-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #10b981;
        border: 1px solid #059669;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
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

            {{-- Action Buttons Container --}}
            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                <button type="button" class="btn-reject-glass" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                    <span>Reject Payment</span>
                </button>

                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-approve-glass" onclick="return confirm('Mark this payment as verified?')">
                        <span>Verify Payment</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Collapsible Rejection Form --}}
    <div class="collapse mb-4" id="rejectForm">
        <div class="dash-card p-4">
            <h6 class="fw-bold text-main mb-2">Rejection Form</h6>
            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted small fw-semibold">Rejection Reason</label>
                    <textarea name="rejection_reason" class="form-control bg-transparent text-main" rows="3" placeholder="State the reason for rejecting this receipt..." required style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.12)); border-radius: 10px;"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn-reject-glass">
                        <span>Confirm Rejection</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection