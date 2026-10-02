@extends('layouts.app')

@section('title', 'Payment Schedule')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Table Styling (Matches My Loans & Audit Logs) -->
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
        box-shadow: var(--dash-card-shadow);
    }
    .dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Shared Glass Table Styling */
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

    /* Overdue Row Soft Highlight */
    .glass-table tr.row-overdue td {
        background-color: rgba(239, 68, 68, 0.05) !important;
    }

    /* Loan Link / Text Styling (Matches My Loans text-muted style) */
    .loan-title-link {
        color: var(--text-muted, #64748b) !important;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .loan-title-link:hover {
        color: #0284c7 !important;
        text-decoration: underline;
    }

    /* Plain Text Status Colors (Matches My Loans Page) */
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

    /* Pay Button */
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

    /* Custom Bootstrap Pagination Styling (Matches Audit Logs) */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .pagination .page-item .page-link {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border) !important;
        color: var(--text-main) !important;
        border-radius: 8px !important;
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
    }
    .pagination .page-item .page-link:hover {
        background-color: rgba(2, 132, 199, 0.1) !important;
        border-color: #0284c7 !important;
        color: #0284c7 !important;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        background-color: transparent !important;
        color: var(--text-muted) !important;
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Container --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Payment Schedule</h2>
        <p class="text-muted small mb-0">Track your upcoming loan installments and payment history.</p>
    </div>

    @if ($schedules->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="text-muted fw-semibold mb-0 py-2">Wala ka pang payment schedule.</p>
        </div>
    @else
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th>Loan</th>
                            <th>Month</th>
                            <th>Due Date</th>
                            <th>Amount Due</th>
                            <th>Penalty</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            @php
                                $hasPending = $schedule->hasPendingPayment();
                            @endphp
                            <tr class="{{ $schedule->status === 'overdue' && !$hasPending ? 'row-overdue' : '' }}">
                                <td>
                                    <a href="{{ route('loans.show', $schedule->loan_id) }}" class="loan-title-link">
                                        {{ $schedule->loan->loanType->name ?? 'Personal Loan' }} #{{ $schedule->loan_id }}
                                    </a>
                                </td>
                                <td class="text-muted">Month {{ $schedule->month_number }}</td>
                                <td class="text-muted">{{ $schedule->due_date->format('M d, Y') }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($schedule->amount_due, 2) }}</td>
                                <td>
                                    @if($schedule->penalty_amount > 0)
                                        <span class="text-danger fw-semibold">₱{{ number_format($schedule->penalty_amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">₱0.00</span>
                                    @endif
                                </td>
                                <td class="text-main">₱{{ number_format($schedule->amount_paid, 2) }}</td>
                                <td>
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

        {{-- Styled Glassmorphism Pagination Card (Identical to Audit Logs) --}}
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $schedules->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $schedules->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $schedules->total() }}</span> results
            </div>
            <div>
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection