@extends('layouts.app')

@section('title', 'Loan History')

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

    /* Clean Text Status Badges (No Background & Centered) */
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0;
        background: transparent !important;
        border: none !important;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.approved {
        color: #10b981;
    }
    .status-badge.rejected {
        color: #ef4444;
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
                            <th>Applicant</th>
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
                                <td class="fw-semibold text-main">{{ $loan->user->name ?? 'N/A' }}</td>
                                <td class="text-muted">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->principal_amount ?? $loan->amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    @if(in_array($loan->status, ['active', 'approved', 'completed', 'paid']))
                                        <span class="status-badge approved">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Approved
                                        </span>
                                    @else
                                        <span class="status-badge rejected">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
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