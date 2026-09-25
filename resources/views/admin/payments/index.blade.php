@extends('layouts.app')

@section('title', 'Payment Verification')

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
    .activity-table td, .activity-table th {
        border-bottom: 1px solid var(--table-border) !important;
        padding: 16px 12px !important;
        background: transparent !important;
    }
    .activity-table th {
        color: #6b7280 !important;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    /* DIRECT OVERRIDE PARA SA REF NUMBER AT TABLE TEXT */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        color: var(--text-main) !important;
        letter-spacing: 0.5px;
    }
</style>

<div class="mb-4">
    <h2 class="fw-bold dash-title mb-1">Pending Payments</h2>
    <p class="dash-subtext mb-0">Verify and process submitted payment receipts requiring approval.</p>
</div>

<div class="dash-card p-4">
    @if ($payments->isEmpty())
        <p class="dash-subtext mb-0 text-center py-3">No pending payments to verify.</p>
    @else
        <div class="table-responsive">
            <table class="table activity-table align-middle mb-0">
                <thead>
                    <tr class="text-uppercase">
                        <th>Payer</th>
                        <th>Loan</th>
                        <th>Reference #</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td class="fw-bold dash-title">{{ $payment->user->name }}</td>
                            <td class="fw-semibold dash-title">
                                #{{ $payment->paymentSchedule->loan_id }} 
                                <span class="dash-subtext small">(Month {{ $payment->paymentSchedule->month_number }})</span>
                            </td>
                            <td><span class="ref-no-text">{{ $payment->gcash_reference_number }}</span></td>
                            <td class="fw-bold text-success">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="fw-semibold dash-title">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="text-end">
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

        @if($payments->hasPages())
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    @endif
</div>
@endsection