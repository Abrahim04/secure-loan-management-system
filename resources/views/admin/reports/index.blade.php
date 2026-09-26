@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling para sa Reports -->
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
    .icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-box svg {
        stroke-width: 2px;
    }
    
    /* Action Buttons Hover Effects */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border-radius: 50px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        width: 100%;
    }
    .glass-btn-action svg {
        transition: transform 0.2s ease;
    }
    .glass-btn-action:hover svg {
        transform: translateX(3px);
    }

    /* Primary Pill */
    .glass-btn-primary {
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
    }
    .glass-btn-primary:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Success Pill */
    .glass-btn-success {
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #059669;
    }
    .glass-btn-success:hover {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transform: translateY(-1px);
    }

    /* Danger Pill */
    .glass-btn-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
    }
    .glass-btn-danger:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Welcome Header Section --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Reports</h2>
        <p class="text-muted small mb-0">Overview of key platform statistics and detailed analytical logs.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Borrowers</span>
                    <div class="icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['total_users'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Active Loans</span>
                    <div class="icon-box" style="background: rgba(56, 189, 248, 0.15); color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['active_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Completed Loans</span>
                    <div class="icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['completed_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Overdue Installments</span>
                    <div class="icon-box" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-danger">{{ $stats['overdue_installments'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Pending Applications</span>
                    <div class="icon-box" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['pending_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Pending Verifications</span>
                    <div class="icon-box" style="background: rgba(100, 116, 139, 0.15); color: #64748b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['pending_payments'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Loans (All-time)</span>
                    <div class="icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-main">{{ $stats['total_loans'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Penalties Collected</span>
                    <div class="icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                    </div>
                </div>
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
                        <div class="icon-box p-3" style="background: rgba(2, 132, 199, 0.15); color: #0284c7; width: 48px; height: 48px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Loan Report</h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small" style="font-size: 0.7rem;">Filterable</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">View comprehensive records of all loan statuses, active applications, and history.</p>
                </div>
                <a href="{{ route('admin.reports.loans') }}" class="glass-btn-action glass-btn-primary">
                    <span>View Loan Report</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box p-3" style="background: rgba(16, 185, 129, 0.15); color: #10b981; width: 48px; height: 48px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Payment Report</h6>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill small" style="font-size: 0.7rem;">Financials</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">Track all verified, pending, and complete borrower payment transactions.</p>
                </div>
                <a href="{{ route('admin.reports.payments') }}" class="glass-btn-action glass-btn-success">
                    <span>View Payment Report</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-box p-3" style="background: rgba(239, 68, 68, 0.15); color: #ef4444; width: 48px; height: 48px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-main">Penalty Report</h6>
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill small" style="font-size: 0.7rem;">Overdue</span>
                        </div>
                    </div>
                    <p class="text-muted small mb-4">Analyze collected penalty fees, late payments, and outstanding balances.</p>
                </div>
                <a href="{{ route('admin.reports.penalties') }}" class="glass-btn-action glass-btn-danger">
                    <span>View Penalty Report</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection