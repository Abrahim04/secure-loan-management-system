@extends('layouts.app')

@section('title', 'Payment Verification')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Table Styling -->
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

    /* Action Buttons Hover Effects */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 5px 14px;
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
    .glass-btn-action svg {
        transition: transform 0.2s ease;
    }
    .glass-btn-action:hover svg {
        transform: translateX(3px);
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

    /* DIRECT OVERRIDE PARA SA REF NUMBER */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        color: var(--text-main) !important;
        letter-spacing: 0.5px;
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
                            <th>Payer</th>
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
                                <td class="fw-semibold text-main">{{ $payment->user->name }}</td>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
            <div class="d-flex justify-content-end">
                {{ $payments->links() }}
            </div>
        @endif
    @endif
</div>
@endsection