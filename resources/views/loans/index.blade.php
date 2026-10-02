@extends('layouts.app')

@section('title', 'My Loans')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Table Styling (Matches Payment Schedule & Admin Theme) -->
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

    /* Modern Glass Primary Button (Apply Loan) */
    .glass-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }
    .glass-btn-primary:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }

    /* Action Buttons Hover Effects */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 5px 16px;
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

    /* Plain Text Status Colors (Without Background Pill) */
    .status-text-pending {
        color: #d97706 !important;
        font-weight: 600;
    }
    .status-text-active, .status-text-approved {
        color: #16a34a !important;
        font-weight: 600;
    }
    .status-text-rejected {
        color: #dc2626 !important;
        font-weight: 600;
    }
    .status-text-completed {
        color: #64748b !important;
        font-weight: 600;
    }

    /* Custom Bootstrap Pagination Styling (Matches Payment Schedule) */
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
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">My Loans</h2>
            <p class="text-muted small mb-0">Track and manage your loan applications and status.</p>
        </div>
        <a href="{{ route('loans.create') }}" class="glass-btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Apply for a Loan</span>
        </a>
    </div>

    {{-- Glass Table Card Container --}}
    @if($loans->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="text-muted fw-semibold mb-0 py-2">You haven't applied for a loan yet.</p>
        </div>
    @else
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th>Loan Type</th>
                            <th>Principal</th>
                            <th>Term</th>
                            <th>Monthly Payment</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td class="text-muted fw-medium">{{ $loan->loanType->name ?? 'Personal Loan' }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->principal_amount, 2) }}</td>
                                <td class="text-muted">{{ $loan->term_months }} mo.</td>
                                <td class="fw-semibold text-main">₱{{ number_format($loan->monthly_payment, 2) }}</td>
                                <td>
                                    <span class="status-text-{{ strtolower($loan->status) }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($loan->applied_at)->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('loans.show', $loan->id) }}" class="glass-btn-action">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Styled Glassmorphism Pagination Card (Identical to Payment Schedule) --}}
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $loans->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $loans->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $loans->total() }}</span> results
            </div>
            <div>
                {{ $loans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection