@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling (100% Matched with Audit Logs) -->
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

    /* Dark Mode Theme Override */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    /* Uniform Action Buttons Style (Matched with Loan Report Blue) */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        padding: 9px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        width: 100%;
        text-align: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
    }
    .glass-btn-action:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Welcome Header Section --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Reports</h2>
        <p class="text-muted small mb-0">Overview of key platform statistics and detailed analytical logs.</p>
    </div>

    {{-- Stats Grid (Walang Icon) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Total Borrowers</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['total_users'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Active Loans</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['active_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Completed Loans</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['completed_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Overdue Installments</span>
                <h3 class="fw-bold mb-0 text-danger">{{ $stats['overdue_installments'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Pending Applications</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['pending_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Pending Verifications</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['pending_payments'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Total Loans (All-time)</span>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['total_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <span class="text-muted small fw-semibold d-block mb-2">Penalties Collected</span>
                <h3 class="fw-bold mb-0 text-main">₱{{ number_format($stats['total_penalties_collected'], 2) }}</h3>
            </div>
        </div>
    </div>

    {{-- Detailed Reports Section --}}
    <h5 class="fw-bold mb-3 text-main">Detailed Reports</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Loan Report</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">View comprehensive records of all loan statuses, active applications, and history.</p>
                </div>
                <a href="{{ route('admin.reports.loans') }}" class="glass-btn-action">
                    <span>View Loan Report</span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Payment Report</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">Track all verified, pending, and complete borrower payment transactions.</p>
                </div>
                <a href="{{ route('admin.reports.payments') }}" class="glass-btn-action">
                    <span>View Payment Report</span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Penalty Report</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">Analyze collected penalty fees, late payments, and outstanding balances.</p>
                </div>
                <a href="{{ route('admin.reports.penalties') }}" class="glass-btn-action">
                    <span>View Penalty Report</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection