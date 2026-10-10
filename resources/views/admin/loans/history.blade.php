@extends('layouts.app')

@section('title', 'Loan History')

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

    /* Dark Mode Theme Override */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Modern Translucent Glass Table Styling */
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

    /* Clean Text Status Badges (No Background, Colored Text Only) */
    .status-badge {
        display: inline-block;
        padding: 0;
        background: transparent !important;
        border: none !important;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.approved {
        color: #10b981 !important;
    }
    .status-badge.rejected {
        color: #ef4444 !important;
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
    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Loan History & Archive</h2>
        <p class="text-muted small mb-0">Record of all approved and rejected loan applications.</p>
    </div>

    {{-- Main Container --}}
    @if($loans->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="text-muted fw-semibold mb-0 py-2">No processed loan applications found in history.</p>
        </div>
    @else
        <div class="dash-card p-0 overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table glass-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Applicant</th>
                            <th>Loan Type</th>
                            <th>Principal</th>
                            <th class="text-center">Status</th>
                            <th>Reason / Remarks</th>
                            <th>Reviewed By</th>
                            <th>Date Processed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td class="ps-4 fw-semibold text-main">{{ $loan->user->name ?? 'N/A' }}</td>
                                <td class="text-muted">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->principal_amount ?? $loan->amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    @if(in_array($loan->status, ['active', 'approved', 'completed', 'paid']))
                                        <span class="status-badge approved">
                                            Approved
                                        </span>
                                    @else
                                        <span class="status-badge rejected">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    @if($loan->status === 'rejected')
                                        <span class="text-danger fw-medium">{{ $loan->rejection_reason ?? 'No reason provided' }}</span>
                                    @else
                                        <span class="text-success fw-medium">Payment Schedule Active</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $loan->reviewer->name ?? 'Admin' }}
                                </td>
                                <td class="text-muted small">
                                    {{ $loan->reviewed_at ? \Carbon\Carbon::parse($loan->reviewed_at)->format('M d, Y h:i A') : \Carbon\Carbon::parse($loan->updated_at)->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($loans->hasPages())
                <div class="p-3">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection