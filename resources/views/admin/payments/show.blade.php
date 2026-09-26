@extends('layouts.app')

@section('title', 'Review Payment')

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

    /* Receipt Pill Action Button */
    .glass-btn-receipt {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(2, 132, 199, 0.1);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 8px 18px;
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

    /* Monospace Reference Text */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: var(--text-main) !important;
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

        <hr class="my-4" style="border-color: var(--dash-card-border);">

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

        <hr class="my-4" style="border-color: var(--dash-card-border);">

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="text-muted small text-uppercase fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">Proof of Payment</span>
                <a href="{{ route('payments.proof', $payment) }}" target="_blank" class="glass-btn-receipt">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <span>View Uploaded Receipt</span>
                </a>
            </div>

            {{-- Action Buttons Container --}}
            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                <button type="button" class="btn-reject-glass" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <span>Reject Payment</span>
                </button>

                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn-approve-glass" onclick="return confirm('Mark this payment as verified?')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
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
                    <textarea name="rejection_reason" class="form-control bg-transparent text-main" rows="3" placeholder="State the reason for rejecting this receipt..." required style="border-color: var(--dash-card-border); border-radius: 10px;"></textarea>
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