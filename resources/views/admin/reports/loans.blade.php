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

    /* Modern Back Button */
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

    /* Modern Glass Table Styling */
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
        padding: 12px 16px !important;
        font-weight: 700;
    }
    .glass-table td {
        border-bottom: 1px solid var(--dash-card-border) !important;
        padding: 14px 16px !important;
        background: transparent !important;
        font-size: 0.875rem;
    }
    .glass-table tr:hover {
        background-color: rgba(100, 116, 139, 0.04) !important;
    }

    /* Clean Status Plain Text (No Background, Centered) */
    .badge-status {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0;
        background: transparent !important;
        border: none !important;
        display: inline-block;
    }
    .badge-pending { color: #f59e0b; }
    .badge-active { color: #0284c7; }
    .badge-completed { color: #10b981; }
    .badge-rejected { color: #ef4444; }

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
    {{-- Header & Custom Dropdown Action --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.reports.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Reports</span>
        </a>

        @php
            $statuses = [
                'all' => 'All Statuses',
                'pending' => 'Pending',
                'active' => 'Active',
                'completed' => 'Completed',
                'rejected' => 'Rejected'
            ];
            $currentStatusLabel = $statuses[$status] ?? 'All Statuses';
        @endphp

        {{-- Custom Glassmorphism Dropdown --}}
        <div class="dropdown">
            <button class="glass-dropdown-toggle dropdown-toggle" type="button" id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                <span>{{ $currentStatusLabel }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end glass-dropdown-menu" aria-labelledby="statusFilterDropdown">
                @foreach ($statuses as $value => $label)
                    <li>
                        <a class="dropdown-item glass-dropdown-item {{ $status === $value ? 'active' : '' }}" href="{{ route('admin.reports.loans', ['status' => $value]) }}">
                            <span>{{ $label }}</span>
                            @if ($status === $value)
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
        <h2 class="fw-bold text-main mb-1">Loan Report</h2>
        <p class="text-muted small mb-0">Detailed list and filter view for all registered borrower loans.</p>
    </div>

    @if ($loans->isEmpty())
        <div class="dash-card p-5 text-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3 opacity-50"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            <h6 class="fw-semibold text-main mb-1">No Loans Found</h6>
            <p class="text-muted small mb-0">No loans match the currently selected status filter.</p>
        </div>
    @else
        {{-- Glass Table Card Container --}}
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Borrower</th>
                            <th>Loan Type</th>
                            <th>Principal</th>
                            <th>Total Payable</th>
                            <th>Term</th>
                            <th class="text-center">Status</th>
                            <th>Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td class="ps-4 fw-semibold text-main">{{ $loan->user->name }}</td>
                                <td class="text-muted">{{ $loan->loanType->name }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->principal_amount, 2) }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->total_payable, 2) }}</td>
                                <td class="text-muted">{{ $loan->term_months }} mo.</td>
                                <td class="text-center">
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

        {{-- Styled Glassmorphism Pagination Card --}}
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $loans->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $loans->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $loans->total() }}</span> results
            </div>
            <div>
                {{ $loans->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection