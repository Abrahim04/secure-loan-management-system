@extends('layouts.app')

@section('title', 'Payment Report')

@section('content')
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow);
    }

    /* Interactive Hover Effect for Metric Cards */
    .metric-card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .metric-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    .metric-card-hover:hover .metric-icon-box {
        transform: scale(1.08);
    }

    /* Metric Icon Cards */
    .metric-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Back Pill Button */
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

    /* Monospace Reference Text */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
    }

    /* Table Styling */
    .glass-table th {
        background: rgba(0, 0, 0, 0.02);
        color: var(--text-muted, #64748b);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        border-bottom: 1px solid var(--dash-card-border) !important;
        padding: 12px 16px;
    }
    .glass-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--dash-card-border);
        color: var(--text-main);
        font-size: 0.875rem;
    }
    .glass-table tbody tr {
        transition: background-color 0.2s ease;
    }
    .glass-table tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.03);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('admin.reports.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reports</span>
        </a>
    </div>

    {{-- Title Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">Payment Report</h2>
            <p class="text-muted small mb-0">Track all received payment submissions, verification status, and transaction histories.</p>
        </div>
    </div>

    {{-- Summary Metric Cards with Hover Animations --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="dash-card metric-card-hover p-3 d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                </div>
                <div>
                    <span class="text-muted small d-block fw-medium">Total Entries</span>
                    <h5 class="fw-bold text-main mb-0">{{ $payments->count() }}</h5>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="dash-card metric-card-hover p-3 d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <div>
                    <span class="text-muted small d-block fw-medium">Verified Collections</span>
                    <h5 class="fw-bold text-main mb-0">₱{{ number_format($payments->where('status', 'verified')->sum('amount'), 2) }}</h5>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="dash-card metric-card-hover p-3 d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div>
                    <span class="text-muted small d-block fw-medium">Pending Review</span>
                    <h5 class="fw-bold text-main mb-0">{{ $payments->where('status', 'pending')->count() }}</h5>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="dash-card metric-card-hover p-3 d-flex align-items-center gap-3">
                <div class="metric-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <div>
                    <span class="text-muted small d-block fw-medium">Rejected Receipts</span>
                    <h5 class="fw-bold text-main mb-0">{{ $payments->where('status', 'rejected')->count() }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Table Card --}}
    <div class="dash-card p-4">
        @if ($payments->isEmpty())
            <div class="text-center py-5">
                <div class="p-3 d-inline-block rounded-circle mb-3" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                </div>
                <h6 class="fw-bold text-main mb-1">No Payments Found</h6>
                <p class="text-muted small mb-0">There are no payment records registered in the system yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table glass-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Payer</th>
                            <th>Loan Target</th>
                            <th>GCash Reference #</th>
                            <th>Amount Submitted</th>
                            <th>Payment Date</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    <div class="fw-bold text-main">{{ $payment->user->name }}</div>
                                    <div class="text-muted small" style="font-size: 0.78rem;">{{ $payment->user->email }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-main">#{{ $payment->paymentSchedule->loan_id }}</span>
                                    <span class="text-muted small d-block" style="font-size: 0.78rem;">Month {{ $payment->paymentSchedule->month_number }}</span>
                                </td>
                                <td>
                                    <span class="ref-no-text">{{ $payment->gcash_reference_number }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-main">₱{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="text-main">{{ $payment->payment_date->format('M d, Y') }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($payment->status === 'verified')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.75rem;">
                                            Verified
                                        </span>
                                    @elseif ($payment->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.75rem;">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.75rem;">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="mt-4">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection