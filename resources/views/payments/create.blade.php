@extends('layouts.app')

@section('title', 'Pay via GCash')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Modern Back Pill Button (Gayang-gaya sa Loan Details) */
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

    /* Glass Summary Breakdown Box */
    .summary-box {
        background: rgba(100, 116, 139, 0.05);
        border: 1px solid var(--dash-card-border);
        border-radius: 12px;
        padding: 18px;
    }

    /* Form Controls */
    .glass-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 6px;
    }
    .glass-input {
        background: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border) !important;
        color: var(--text-main) !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .glass-input:focus {
        background: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }
    .glass-input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    /* Primary Submit Button (Centered Text) */
    .btn-submit-payment {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 11px 24px;
        border-radius: 50px;
        width: 100%;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }
    .btn-submit-payment:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button (Nasa gilid katulad sa Loan Details) --}}
    <div class="mb-3">
        <a href="{{ url()->previous() != url()->current() ? url()->previous() : url('/payment-schedules') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Payment Schedule</span>
        </a>
    </div>

    {{-- Main Payment Form Card (Centered at may fixed max-width para maganda pa rin ang hugis) --}}
    <div class="dash-card p-4 p-md-5 mx-auto" style="max-width: 680px;">
        {{-- Header Section (Walang Icon) --}}
        <div class="border-bottom pb-3 mb-4" style="border-color: var(--dash-card-border) !important;">
            <h3 class="fw-bold text-main mb-1">Pay via GCash</h3>
            <p class="text-muted small mb-0">
                Installment #{{ $paymentSchedule->month_number }} — Due {{ $paymentSchedule->due_date->format('M d, Y') }}
            </p>
        </div>

        {{-- Payment Summary Box --}}
        <div class="summary-box mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Amount Due</span>
                <span class="fw-semibold text-main">₱{{ number_format($paymentSchedule->amount_due, 2) }}</span>
            </div>
            @if ($paymentSchedule->penalty_amount > 0)
                <div class="d-flex justify-content-between align-items-center mb-2 text-danger">
                    <span class="small">Penalty</span>
                    <span class="fw-semibold">₱{{ number_format($paymentSchedule->penalty_amount, 2) }}</span>
                </div>
            @endif
            <hr class="my-2" style="border-color: var(--dash-card-border);">
            <div class="d-flex justify-content-between align-items-center pt-1">
                <span class="fw-bold text-main">Total Due</span>
                <span class="fw-bold text-main fs-5">₱{{ number_format($paymentSchedule->totalDue(), 2) }}</span>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="p-3 mb-4 rounded-3" style="background: rgba(2, 132, 199, 0.08); border: 1px solid rgba(2, 132, 199, 0.2);">
            <p class="text-muted small mb-0">
                <i class="bi bi-info-circle-fill text-primary me-1"></i>
                Pay this amount to our GCash account externally, then enter the reference number and upload your receipt below. We never ask for your GCash MPIN, password, or OTP.
            </p>
        </div>

        {{-- Display Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-3 mb-4" role="alert">
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Payment Form --}}
        <form method="POST" action="{{ route('payments.store', $paymentSchedule) }}" enctype="multipart/form-data">
            @csrf

            {{-- GCash Reference Number --}}
            <div class="mb-3">
                <label for="gcash_reference_number" class="glass-label">GCash Reference Number</label>
                <input type="text" name="gcash_reference_number" id="gcash_reference_number" 
                       class="form-control glass-input" 
                       placeholder="e.g. 100123456789" value="{{ old('gcash_reference_number') }}" required>
            </div>

            {{-- Amount Paid --}}
            <div class="mb-3">
                <label for="amount" class="glass-label">Amount Paid (₱)</label>
                <input type="number" step="0.01" name="amount" id="amount" 
                       class="form-control glass-input" 
                       value="{{ old('amount', $paymentSchedule->totalDue()) }}" required>
            </div>

            {{-- Payment Date --}}
            <div class="mb-3">
                <label for="payment_date" class="glass-label">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" 
                       class="form-control glass-input" 
                       value="{{ old('payment_date', now()->toDateString()) }}" required>
            </div>

            {{-- Proof of Payment --}}
            <div class="mb-4">
                <label for="proof_of_payment" class="glass-label">Proof of Payment (screenshot or receipt)</label>
                <input type="file" name="proof_of_payment" id="proof_of_payment" 
                       class="form-control glass-input" 
                       accept=".jpg,.jpeg,.png,.pdf" required>
            </div>

            {{-- Submit Button (Centered Text) --}}
            <button type="submit" class="btn-submit-payment">
                Submit Payment
            </button>
        </form>
    </div>
</div>
@endsection