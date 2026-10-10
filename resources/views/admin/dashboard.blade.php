@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling (100% Matched with Audit Logs) -->
<style>
    /* Translucent Glass Dash Card Container - Matched with Audit Logs */
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
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .icon-box svg {
        stroke-width: 2.2px;
    }

    /* Light/Dark text helper overrides */
    .dash-title {
        color: var(--text-main, #f8fafc) !important;
    }
    .dash-subtext {
        color: var(--text-muted, #94a3b8) !important;
    }

    /* Unified Accent Blue Pill Action Buttons */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 6px 16px;
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

    .activity-table td {
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05)) !important;
        padding: 12px 8px !important;
        background: transparent !important;
        color: var(--text-main, #f8fafc) !important;
    }
</style>

{{-- Welcome Header Section --}}
<div class="mb-4">
    <h2 class="fw-bold dash-title mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
    <p class="dash-subtext mb-0">Here's what's happening with your loan system today.</p>
</div>

{{-- Total Portfolio Summary --}}
<div class="row g-3 mb-1">
    <!-- Total Released Loans -->
    <div class="col-md-6">
        <div class="dash-card h-100 p-4" style="border-left: 4px solid #10b981 !important;">
            <div class="mb-2">
                <h6 class="dash-subtext mb-0 fw-semibold">Total Released Loans</h6>
            </div>
            <h2 class="fw-bold my-1" style="color: #10b981 !important;">₱{{ number_format($totalReleased, 2) }}</h2>
            <p class="mb-0 small dash-subtext">Total disbursed capital (active + completed loans)</p>
        </div>
    </div>

    <!-- Total Collected Amount -->
    <div class="col-md-6">
        <div class="dash-card h-100 p-4" style="border-left: 4px solid #0284c7 !important;">
            <div class="mb-2">
                <h6 class="dash-subtext mb-0 fw-semibold">Total Collected Amount</h6>
            </div>
            <h2 class="fw-bold my-1" style="color: #0284c7 !important;">₱{{ number_format($totalCollected, 2) }}</h2>
            <p class="mb-0 small dash-subtext">Sum of all verified payments</p>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <!-- Pending Loans -->
    <div class="col-md-3">
        <a href="{{ route('admin.loans.index') }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3" style="border-left: 4px solid #2563eb !important;">
                <div class="mb-2">
                    <h6 class="dash-subtext mb-0 small fw-semibold">Pending Loans</h6>
                    <h3 class="dash-title fw-bold mb-0 mt-1">{{ $pendingLoansCount }}</h3>
                </div>
                <p class="mb-0 small dash-subtext">Review new loan applications</p>
            </div>
        </a>
    </div>

    <!-- Pending Payments -->
    <div class="col-md-3">
        <a href="{{ route('admin.payments.index') }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3" style="border-left: 4px solid #d97706 !important;">
                <div class="mb-2">
                    <h6 class="dash-subtext mb-0 small fw-semibold">Pending Payments</h6>
                    <h3 class="dash-title fw-bold mb-0 mt-1">{{ $pendingPaymentsCount }}</h3>
                </div>
                <p class="mb-0 small dash-subtext">Verify submitted GCash payments</p>
            </div>
        </a>
    </div>

    <!-- Overdue Installments -->
    <div class="col-md-3">
        <a href="{{ route('admin.reports.loans', ['status' => 'active']) }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3" style="border-left: 4px solid #ef4444 !important;">
                <div class="mb-2">
                    <h6 class="dash-subtext mb-0 small fw-semibold">Overdue Installments</h6>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #ef4444 !important;">{{ $overdueCount }}</h3>
                </div>
                <p class="mb-0 small dash-subtext">Monitor overdue accounts & penalties</p>
            </div>
        </a>
    </div>

    <!-- Security Events -->
    <div class="col-md-3">
        <a href="{{ route('admin.security-events.index') }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3" style="border-left: 4px solid #0284c7 !important;">
                <div class="mb-2">
                    <h6 class="dash-subtext mb-0 small fw-semibold">Security Events (24h)</h6>
                    <h3 class="dash-title fw-bold mb-0 mt-1">{{ $recentSecurityEventsCount }}</h3>
                </div>
                <p class="mb-0 small dash-subtext">Failed logins and suspicious activity</p>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Recent Activity Table --}}
    <div class="col-md-7">
        <div class="dash-card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <h6 class="dash-title fw-bold mb-0">Recent Activity</h6>
                </div>
                <a href="{{ route('admin.audit-logs.index') }}" class="glass-btn-action">
                    <span>View all audit logs</span>
                </a>
            </div>

            @if ($recentActivity->isEmpty())
                <p class="dash-subtext small mb-0">No recent activity.</p>
            @else
                <div class="table-responsive">
                    <table class="table activity-table align-middle mb-0">
                        <tbody>
                            @foreach ($recentActivity as $log)
                                <tr>
                                    <td class="small fw-semibold">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary bg-opacity-25 p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            </div>
                                            <span>{{ $log->user->name ?? 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="small dash-subtext">{{ $log->action }}</td>
                                    <td class="small dash-subtext text-end">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- User Management quick access --}}
    <div class="col-md-5">
        <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h6 class="dash-title fw-bold mb-0">User Management</h6>
                </div>
                <div class="row text-center py-2 g-2">
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25);">
                            <div class="fw-bold fs-4" style="color: #10b981;">{{ $activeUsersCount }}</div>
                            <div class="small dash-subtext" style="font-size: 0.75rem;">Active</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25);">
                            <div class="fw-bold fs-4" style="color: #ef4444;">{{ $blockedUsersCount }}</div>
                            <div class="small dash-subtext" style="font-size: 0.75rem;">Blocked</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.25);">
                            <div class="fw-bold fs-4" style="color: #f59e0b;">{{ $unverifiedUsersCount }}</div>
                            <div class="small dash-subtext" style="font-size: 0.75rem;">Unverified</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-2 text-end">
                <a href="{{ route('admin.users.index') }}" class="glass-btn-action">
                    <span>Manage registered borrowers</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <!-- Reports -->
    <div class="col-md-6">
        <div class="dash-card p-4">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div>
                    <h6 class="dash-title fw-bold mb-0">Reports</h6>
                    <p class="mb-0 small dash-subtext">Loan, payment, and penalty reports with full history.</p>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('admin.reports.index') }}" class="glass-btn-action">
                    <span>Open Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Audit Logs -->
    <div class="col-md-6">
        <div class="dash-card p-4">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="icon-box" style="background: rgba(56, 189, 248, 0.15); color: #0284c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h6 class="dash-title fw-bold mb-0">Audit Logs</h6>
                    <p class="mb-0 small dash-subtext">Full history of admin and system actions.</p>
                </div>
            </div>
            <div class="mt-3 text-end">
                <a href="{{ route('admin.audit-logs.index') }}" class="glass-btn-action">
                    <span>Open Audit Logs</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection