@extends('layouts.app')

@section('title', 'Loan Report')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling -->
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

    /* Modern Back Button & Filter Dropdown */
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

    .custom-filter-select {
        background-color: var(--dash-card-bg) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: var(--dash-card-shadow);
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-filter-select:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Modern Glass Table Styling */
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

    /* Status Pills */
    .badge-status {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-active { background: rgba(2, 132, 199, 0.15); color: #0284c7; border: 1px solid rgba(2, 132, 199, 0.3); }
    .badge-completed { background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3); }
</style>

<div class="container-fluid px-0">
    {{-- Header & Back Action --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.reports.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reports</span>
        </a>

        {{-- Filter Dropdown --}}
        <form method="GET" class="m-0">
            <select name="status" class="custom-filter-select" onchange="this.form.submit()">
                @foreach (['all' => 'All Statuses', 'pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold dash-title mb-1">Loan Report</h2>
        <p class="dash-subtext small mb-0">Detailed list and filter view for all registered borrower loans.</p>
    </div>

    @if ($loans->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="dash-subtext mb-0 fw-semibold">No loans found for this status filter.</p>
        </div>
    @else
        {{-- Glass Table Card Container --}}
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th>Borrower</th>
                            <th>Loan Type</th>
                            <th>Principal</th>
                            <th>Total Payable</th>
                            <th>Term</th>
                            <th>Status</th>
                            <th>Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td class="fw-semibold text-main">{{ $loan->user->name }}</td>
                                <td class="text-muted">{{ $loan->loanType->name }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->principal_amount, 2) }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->total_payable, 2) }}</td>
                                <td class="text-muted">{{ $loan->term_months }} mo.</td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($loan->status)) {
                                            'pending' => 'badge-pending',
                                            'active' => 'badge-active',
                                            'completed' => 'badge-completed',
                                            'rejected' => 'badge-rejected',
                                            default => 'badge-pending'
                                        };
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $loan->applied_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Styling --}}
        <div class="d-flex justify-content-end">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection