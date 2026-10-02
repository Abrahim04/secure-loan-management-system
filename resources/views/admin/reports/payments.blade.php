@extends('layouts.app')

@section('title', 'Payment Report')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling -->
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
        cursor: default; /* Pinalitan mula 'pointer' para maging normal na arrow cursor */
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
        border: 1px solid var(--dash-card-border);
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

    /* Custom Glassmorphism Dropdown Button */
    .glass-dropdown-toggle {
        background-color: var(--dash-card-bg) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: var(--dash-card-shadow);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .glass-dropdown-toggle:hover, .glass-dropdown-toggle:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Custom Glassmorphism Dropdown Menu */
    .glass-dropdown-menu {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 12px;
        box-shadow: var(--dash-card-shadow), 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        padding: 6px;
        margin-top: 6px !important;
        min-width: 170px;
    }
    .glass-dropdown-item {
        color: var(--text-main) !important;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 8px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .glass-dropdown-item:hover {
        background-color: rgba(2, 132, 199, 0.1) !important;
        color: #0284c7 !important;
    }
    .glass-dropdown-item.active {
        background-color: #0284c7 !important;
        color: #ffffff !important;
    }

    /* Monospace Reference Text */
    .ref-no-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
    }

    /* Table Styling */
    .glass-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main) !important;
    }
    .glass-table th {
        background: rgba(100, 116, 139, 0.06) !important;
        border-bottom: 1px solid var(--dash-card-border) !important;
        color: var(--text-muted, #64748b) !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
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
        background-color: rgba(100, 116, 139, 0.04);
    }

    /* Soft Badge Colors */
    .badge-status {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-pending { background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); }
    .badge-verified { background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25); }
    .badge-rejected { background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); }

    /* Custom Bootstrap Pagination Styling */
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
    {{-- Header Actions & Custom Dropdown Filter --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.reports.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reports</span>
        </a>

        @php
            $currentStatus = request('status', 'all');
            $statuses = [
                'all' => 'All Statuses',
                'pending' => 'Pending',
                'verified' => 'Verified',
                'rejected' => 'Rejected'
            ];
            $currentStatusLabel = $statuses[$currentStatus] ?? 'All Statuses';
        @endphp

        {{-- Glassmorphism Status Dropdown --}}
        <div class="dropdown">
            <button class="glass-dropdown-toggle dropdown-toggle" type="button" id="paymentStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                <span>{{ $currentStatusLabel }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end glass-dropdown-menu" aria-labelledby="paymentStatusDropdown">
                @foreach ($statuses as $value => $label)
                    <li>
                        <a class="dropdown-item glass-dropdown-item {{ $currentStatus === $value ? 'active' : '' }}" href="{{ route('admin.reports.payments', ['status' => $value]) }}">
                            <span>{{ $label }}</span>
                            @if ($currentStatus === $value)
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Payment Report</h2>
        <p class="text-muted small mb-0">Track all received payment submissions, verification status, and transaction histories.</p>
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
    <div class="dash-card p-0 overflow-hidden mb-4">
        @if ($payments->isEmpty())
            <div class="text-center p-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3 opacity-50"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                <h6 class="fw-bold text-main mb-1">No Payments Found</h6>
                <p class="text-muted small mb-0">No payment records match the currently selected filter status.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table glass-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Payer</th>
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
                                <td class="ps-4">
                                    <div class="fw-bold text-main">{{ $payment->user->name }}</div>
                                    <div class="text-muted small" style="font-size: 0.78rem;">{{ $payment->user->email }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-main">#{{ $payment->paymentSchedule->loan_id }}</span>
                                    <span class="text-muted small d-block" style="font-size: 0.78rem;">Month {{ $payment->paymentSchedule->month_number }}</span>
                                </td>
                                <td>
                                    <span class="ref-no-text text-main">{{ $payment->gcash_reference_number }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-main">₱{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="text-main">{{ $payment->payment_date->format('M d, Y') }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = match(strtolower($payment->status)) {
                                            'verified' => 'badge-verified',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge-status {{ $badgeClass }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Glassmorphism Pagination Card --}}
    @if (!$payments->isEmpty())
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $payments->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $payments->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $payments->total() }}</span> results
            </div>
            <div>
                {{ $payments->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection