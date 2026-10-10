@extends('layouts.app')

@section('title', 'Loan Management')

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
    .dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }

    /* Dark Mode Theme Override - Ensures smooth consistent Dark Slate Blue */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Action Buttons Hover Effects (Wala nang arrow icon) */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 5px 18px;
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

    /* Auto-Dismissing Custom Glass Alerts */
    .glass-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .glass-alert-success {
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }
    .glass-alert-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    .glass-alert.fade-out {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Dynamic Flash Alert Banner --}}
    @if (session('success') || session('status'))
        @php
            $isDanger = session('status') && (str_contains(strtolower(session('status')), 'reject') || str_contains(strtolower(session('status')), 'cancel'));
            $message = session('success') ?? session('status');
        @endphp
        <div id="autoDismissAlert" class="glass-alert {{ $isDanger ? 'glass-alert-danger' : 'glass-alert-success' }} mb-4">
            @if ($isDanger)
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            @endif
            <span>{{ $message }}</span>
        </div>
    @endif

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Pending Loan Applications</h2>
        <p class="text-muted small mb-0">Review and manage submitted loan requests requiring approval.</p>
    </div>

    {{-- Glass Table Card Container --}}
    @if($loans->isEmpty())
        <div class="dash-card p-4 text-center">
            <p class="text-muted fw-semibold mb-0 py-2">No pending loan applications available.</p>
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
                            <th>Term</th>
                            <th>Applied</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td class="ps-4 fw-semibold text-main">{{ $loan->user->name ?? 'N/A' }}</td>
                                <td class="text-muted">{{ $loan->loan_type ?? $loan->loanType->name ?? 'Personal Loan' }}</td>
                                <td class="fw-bold text-main">₱{{ number_format($loan->amount ?? $loan->principal_amount ?? 0, 2) }}</td>
                                <td class="text-muted">{{ $loan->term_months ?? $loan->term ?? 0 }} mo.</td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.loans.show', $loan->id) }}" class="glass-btn-action">
                                        <span>Review</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

{{-- JavaScript Script to Auto-Dismiss Alert after 5 Seconds --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('autoDismissAlert');
    if (alertBox) {
        setTimeout(function() {
            alertBox.classList.add('fade-out');
            setTimeout(function() {
                alertBox.remove();
            }, 500);
        }, 5000);
    }
});
</script>
@endsection