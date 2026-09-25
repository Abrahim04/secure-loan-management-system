@extends('layouts.app')

@section('title', 'Loan Management')

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
        color: var(--text-muted) !important;
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
        color: var(--text-main) !important;
    }
</style>

<div class="mb-4">
    <h2 class="fw-bold dash-title mb-1">Pending Loan Applications</h2>
    <p class="dash-subtext mb-0">Review and manage submitted loan requests requiring approval.</p>
</div>

<div class="dash-card p-4">
    @if($loans->isEmpty())
        <p class="dash-subtext mb-0 text-center py-3">No pending loan applications available.</p>
    @else
        <div class="table-responsive">
            <table class="table activity-table align-middle mb-0">
                <thead>
                    <tr class="dash-subtext small text-uppercase">
                        <th>Applicant</th>
                        <th>Loan Type</th>
                        <th>Principal</th>
                        <th>Term</th>
                        <th>Applied</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        <tr>
                            <td class="fw-bold dash-title">{{ $loan->user->name ?? 'N/A' }}</td>
                            <td class="fw-semibold dash-title">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</td>
                            <td class="fw-bold text-success">₱{{ number_format($loan->amount ?? $loan->principal_amount ?? 0, 2) }}</td>
                            <td class="dash-subtext">{{ $loan->term_months ?? $loan->term ?? 0 }} mo.</td>
                            <td class="dash-subtext">{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.loans.show', $loan->id) }}" class="glass-btn-action">
                                    <span>Review</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection