@extends('layouts.app')

@section('title', 'Loan Details')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling (Matches Admin & Payment Schedule Theme) -->
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

    /* Glass Table Styling */
    .glass-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main) !important;
    }
    .glass-table th {
        background: rgba(0, 0, 0, 0.02) !important;
        border-bottom: 1px solid var(--table-border, rgba(0, 0, 0, 0.08)) !important;
        color: var(--text-muted, #64748b) !important;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px !important;
        font-weight: 700;
    }
    .glass-table td {
        border-bottom: 1px solid var(--table-border, rgba(0, 0, 0, 0.05)) !important;
        padding: 14px 16px !important;
        background: transparent !important;
        font-size: 0.875rem;
    }
    .glass-table tr:last-child td {
        border-bottom: none !important;
    }

    /* Plain Text Status Styling (Matches Payment Schedule Page) */
    .status-text-pending,
    .status-text-partially_paid {
        color: #d97706 !important;
        font-weight: 600;
    }
    .status-text-active, 
    .status-text-approved,
    .status-text-paid {
        color: #16a34a !important;
        font-weight: 600;
    }
    .status-text-rejected,
    .status-text-overdue {
        color: #dc2626 !important;
        font-weight: 600;
    }
    .status-text-completed,
    .status-text-unpaid,
    .status-text-secondary {
        color: #64748b !important;
        font-weight: 600;
    }

    /* Action Pay Button (Walang Arrow Icon & Standardized) */
    .btn-pay-gcash {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: transparent;
        border: 1px solid #0284c7;
        color: #0284c7;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 5px 16px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .btn-pay-gcash:hover {
        background: #0284c7;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('loans.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to My Loans</span>
        </a>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Loan Application Details</h2>
        <p class="text-muted small mb-0">View the full details and current status of your loan.</p>
    </div>

    {{-- Loan Summary Card --}}
    <div class="dash-card p-4 mb-4">
        <div class="row g-4">
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Loan Type</span>
                <span class="text-main fw-bold fs-6">{{ $loan->loanType->name ?? $loan->loan_type ?? 'Personal Loan' }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Term</span>
                <span class="text-main fw-bold fs-6">{{ $loan->term_months }} months</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Applied Date</span>
                <span class="fw-bold fs-6 text-main">{{ \Carbon\Carbon::parse($loan->applied_at ?? $loan->created_at)->format('M d, Y') }}</span>
            </div>
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status</span>
                <span class="status-text-{{ strtolower($loan->status) }} fs-6">
                    {{ ucfirst($loan->status) }}
                </span>
            </div>
        </div>

        <hr class="my-4" style="border-color: var(--dash-card-border);">

        <div class="row g-4">
            <div class="col-md-3">
                <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Principal Amount</span>
                <span class="fw-bold fs-5 text-success">₱{{ number_format($loan->principal_amount ?? $loan->amount ?? 0, 2) }}</span>
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

        @if ($loan->status === 'rejected')
            <hr class="my-4" style="border-color: var(--dash-card-border);">
            <div class="p-3 rounded-3" style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.2);">
                <span class="text-danger fw-bold d-block mb-1">Rejection Reason:</span>
                <span class="text-main small">{{ $loan->rejection_reason ?? 'No reason provided.' }}</span>
            </div>
        @endif
    </div>

    {{-- Payment Schedule Table Section --}}
    @if ($loan->paymentSchedules && $loan->paymentSchedules->isNotEmpty())
        <div class="mb-3">
            <h4 class="fw-bold text-main mb-1">Payment Schedule</h4>
            <p class="text-muted small mb-0">View the full details and current status of your loan.</p>
        </div>

        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Penalty</th>
                            <th>Amount Paid</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loan->paymentSchedules as $schedule)
                            @php
                                $hasPending = $schedule->hasPendingPayment();
                            @endphp
                            <tr>
                                <td class="text-muted">Month {{ $schedule->month_number }}</td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($schedule->amount_due, 2) }}</td>
                                <td>
                                    @if(($schedule->penalty_amount ?? 0) > 0)
                                        <span class="text-danger fw-semibold">₱{{ number_format($schedule->penalty_amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">₱0.00</span>
                                    @endif
                                </td>
                                <td class="text-main">₱{{ number_format($schedule->amount_paid ?? 0, 2) }}</td>
                                <td class="text-center">
                                    @if ($hasPending)
                                        <span class="status-text-pending">
                                            For Approval
                                        </span>
                                    @else
                                        <span class="status-text-{{ strtolower($schedule->status) }}">
                                            {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($schedule->status === 'paid')
                                        <span class="status-text-paid small fs-7"><i class="bi bi-check-circle-fill me-1"></i> Paid</span>
                                    @elseif ($hasPending)
                                        <span class="status-text-pending small">
                                            Pending Approval
                                        </span>
                                    @else
                                        <a href="{{ route('payments.create', $schedule) }}" class="btn-pay-gcash">
                                            Pay via GCash
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection