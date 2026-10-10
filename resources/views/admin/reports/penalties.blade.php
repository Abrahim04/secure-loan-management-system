@extends('layouts.app')

@section('title', 'Penalty Report')

@section('content')
<style>
    /* Dash Card & Container Theme - Matched with Loan & Payment Reports */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    /* Dark Mode Theme Override - Ensures consistent Dark Slate Blue, never solid black */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Interactive Hover Effect for Metric Cards */
    .metric-card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
    }
    .metric-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    /* Back Pill Button */
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.1);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12));
        color: var(--text-main, #f8fafc);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.2);
        color: var(--text-main, #ffffff);
        transform: translateX(-3px);
    }

    /* Table Styling - Matched with Loan Report */
    .glass-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main, #f8fafc) !important;
        background: transparent !important;
    }
    .glass-table th {
        background: rgba(255, 255, 255, 0.03) !important;
        color: var(--text-muted, #94a3b8);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        padding: 12px 16px !important;
    }
    .glass-table td {
        padding: 14px 16px !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05)) !important;
        color: var(--text-main, #f8fafc);
        font-size: 0.875rem;
        background: transparent !important;
    }
    .glass-table tbody tr {
        transition: background-color 0.15s ease;
        background: transparent !important;
    }
    .glass-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }

    /* Clean Days Overdue Plain Text (No Red Background, Centered) */
    .days-overdue-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #ef4444 !important;
        background: transparent !important;
        border: none !important;
        display: inline-block;
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
            <h2 class="fw-bold text-main mb-1">Penalty Report</h2>
            <p class="text-muted small mb-0">Overview of late payment penalties applied, overdue days, and fine rates.</p>
        </div>
    </div>

    {{-- Summary Metric Cards (Clean & Minimalist without Icons) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-4">
            <div class="dash-card metric-card-hover p-3">
                <span class="text-muted small d-block fw-medium mb-1">Total Penalties</span>
                <h5 class="fw-bold text-main mb-0">{{ $penalties->count() }}</h5>
            </div>
        </div>

        <div class="col-6 col-lg-4">
            <div class="dash-card metric-card-hover p-3">
                <span class="text-muted small d-block fw-medium mb-1">Total Penalty Amount</span>
                <h5 class="fw-bold text-main mb-0">₱{{ number_format($penalties->sum('amount'), 2) }}</h5>
            </div>
        </div>

        <div class="col-6 col-lg-4">
            <div class="dash-card metric-card-hover p-3">
                <span class="text-muted small d-block fw-medium mb-1">Avg. Days Overdue</span>
                <h5 class="fw-bold text-main mb-0">{{ $penalties->count() > 0 ? round($penalties->avg('days_overdue'), 1) : 0 }} Days</h5>
            </div>
        </div>
    </div>

    {{-- Main Content Table Card --}}
    <div class="dash-card p-4">
        @if ($penalties->isEmpty())
            <div class="text-center py-5">
                <div class="p-3 d-inline-block rounded-circle mb-3" style="background: rgba(100, 116, 139, 0.1); color: #64748b;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path></svg>
                </div>
                <h6 class="fw-bold text-main mb-1">No Penalties Found</h6>
                <p class="text-muted small mb-0">No penalties have been applied yet.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table glass-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Borrower</th>
                            <th>Loan</th>
                            <th class="text-center">Days Overdue</th>
                            <th>Penalty Amount</th>
                            <th>Rate/Day</th>
                            <th>Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penalties as $penalty)
                            <tr>
                                <td>
                                    <div class="fw-bold text-main">{{ $penalty->paymentSchedule->loan->user->name }}</div>
                                    <div class="text-muted small" style="font-size: 0.78rem;">{{ $penalty->paymentSchedule->loan->user->email }}</div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-main">#{{ $penalty->paymentSchedule->loan_id }}</span>
                                    <span class="text-muted small d-block" style="font-size: 0.78rem;">(Month {{ $penalty->paymentSchedule->month_number }})</span>
                                </td>
                                <td class="text-center">
                                    <span class="days-overdue-text">
                                        {{ $penalty->days_overdue }} days
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">₱{{ number_format($penalty->amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="text-main">₱{{ number_format($penalty->rate_per_day, 2) }}</span>
                                </td>
                                <td>
                                    <span class="text-main">{{ $penalty->applied_at->format('M d, Y') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($penalties->hasPages())
                <div class="mt-4">
                    {{ $penalties->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection