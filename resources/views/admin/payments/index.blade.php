@extends('layouts.app')

@section('title', 'Payment Verification')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Table Styling (100% Matched with Audit Logs) -->
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
    .dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }

    /* Dark Mode Theme Override - Ensures smooth consistent Dark Slate Blue */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Action Buttons Hover Effects (Wala nang arrow icon) */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 5px 18px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .glass-btn-action:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Shared Glass Table Styling */
    .glass-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main, #f8fafc) !important;
        background: transparent !important;
        --bs-table-bg: transparent !important;
        --bs-table-color: var(--text-main, #f8fafc) !important;
    }
    .glass-table th {
        background: rgba(255, 255, 255, 0.03) !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        color: var(--text-muted, #94a3b8) !important;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px !important;
        font-weight: 700;
        vertical-align: middle;
    }
    .glass-table td {
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05)) !important;
        padding: 14px 16px !important;
        background: transparent !important;
        font-size: 0.875rem;
        color: var(--text-main, #f8fafc) !important;
        vertical-align: middle;
    }
    .glass-table tr {
        transition: background-color 0.15s ease;
        background: transparent !important;
    }
    .glass-table tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }

    /* Reference Number Font Styling */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: var(--text-main, #f8fafc) !important;
        letter-spacing: 0.5px;
    }

    /* Custom Bootstrap Pagination Styling */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .pagination .page-item .page-link {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
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
        background-color: rgba(2, 132, 199, 0.15) !important;
        border-color: #0284c7 !important;
        color: #0284c7 !important;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        background-color: transparent !important;
        color: var(--text-muted, #94a3b8) !important;
    }
</style>

<div class="container-fluid px-0">
    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Pending Payments</h2>
        <p class="text-muted small mb-0">Verify and process submitted payment receipts requiring approval.</p>
    </div>

    {{-- Glass Table Card Container --}}
    @if ($payments->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="text-muted fw-semibold mb-0 py-2">No pending payments to verify.</p>
        </div>
    @else
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Payer</th>
                            <th>Loan</th>
                            <th>Reference #</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="ps-4 fw-semibold text-main">{{ $payment->user->name }}</td>
                                <td class="fw-semibold text-main">
                                    #{{ $payment->paymentSchedule->loan_id }} 
                                    <span class="text-muted small">(Month {{ $payment->paymentSchedule->month_number }})</span>
                                </td>
                                <td><span class="ref-no-text">{{ $payment->gcash_reference_number }}</span></td>
                                <td class="fw-bold text-main">₱{{ number_format($payment->amount, 2) }}</td>
                                <td class="text-muted">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="glass-btn-action">
                                        <span>Review</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="dash-card p-3 d-flex justify-content-end mb-4">
                {{ $payments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>
@endsection