@extends('layouts.app')

@section('title', 'Review Payment')

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
        color: #6b7280 !important;
    }

    /* EXACT BUTTON STYLE AT HOVER NG LOAN MANAGEMENT */
    .btn-back-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #dbe0e6;
        border: 1px solid #c2c9d1;
        color: #1e293b;
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    .btn-back-pill:hover {
        background-color: #94a3b8;
        border-color: #64748b;
        color: #ffffff;
    }

    .glass-btn-receipt {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(2, 132, 199, 0.1);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-receipt:hover {
        background: #0284c7;
        color: #ffffff;
    }
    .btn-approve-custom {
        background: #10b981;
        border: none;
        color: #ffffff;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-approve-custom:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-reject-custom {
        background: #ef4444;
        border: none;
        color: #ffffff;
        font-weight: 600;
        padding: 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-reject-custom:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
}
</style>

<div class="mb-3">
    <a href="{{ route('admin.payments.index') }}" class="btn-back-pill">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to Collections</span>
    </a>
</div>

<div class="mb-4">
    <h2 class="fw-bold dash-title mb-1">Review Payment</h2>
    <p class="dash-subtext mb-0">Evaluate payment details and proof of payment before verifying or rejecting.</p>
</div>

<div class="dash-card p-4 mb-4">
    <div class="row g-4">
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">Payer</span>
            <span class="dash-title fw-bold fs-6 d-block">{{ $payment->user->name }}</span>
            <span class="dash-subtext small">{{ $payment->user->email }}</span>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">Loan Target</span>
            <span class="dash-title fw-bold fs-6">#{{ $payment->paymentSchedule->loan_id }} — Month {{ $payment->paymentSchedule->month_number }}</span>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">Payment Date</span>
            <span class="fw-bold fs-6 dash-title">{{ $payment->payment_date->format('M d, Y') }}</span>
        </div>
    </div>

    <hr class="my-4" style="border-color: var(--dash-card-border);">

    <div class="row g-4">
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">GCash Reference #</span>
            <td><span class="ref-no-text dash-title">{{ $payment->gcash_reference_number }}</span></td>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">Amount Submitted</span>
            <span class="text-success fw-bold fs-5">₱{{ number_format($payment->amount, 2) }}</span>
        </div>
        <div class="col-md-4">
            <span class="dash-subtext small text-uppercase fw-semibold d-block mb-1">Installment Total Due</span>
            <span class="dash-title fw-bold fs-5">₱{{ number_format($payment->paymentSchedule->totalDue(), 2) }}</span>
        </div>
    </div>

    <hr class="my-4" style="border-color: var(--dash-card-border);">

    <div>
        <span class="dash-subtext small text-uppercase fw-semibold d-block mb-2">Proof of Payment</span>
        <a href="{{ route('payments.proof', $payment) }}" target="_blank" class="glass-btn-receipt">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            <span>View Uploaded Receipt</span>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
            @csrf
            <button type="submit" class="btn-approve-custom w-100" onclick="return confirm('Mark this payment as verified?')">
                Verify Payment
            </button>
        </form>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn-reject-custom w-100" data-bs-toggle="collapse" data-bs-target="#rejectForm">
            Reject Payment
        </button>
    </div>
</div>

<div class="collapse mt-3" id="rejectForm">
    <div class="dash-card p-4">
        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label dash-title fw-semibold">Rejection Reason</label>
                <textarea name="rejection_reason" class="form-control bg-transparent text-main" rows="3" placeholder="State the reason for rejecting this receipt..." required style="border-color: var(--dash-card-border);"></textarea>
            </div>
            <button type="submit" class="btn-reject-custom px-4 py-2">Confirm Rejection</button>
        </form>
    </div>
</div>
@endsection