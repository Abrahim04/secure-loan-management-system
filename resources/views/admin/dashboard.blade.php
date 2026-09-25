@extends('layouts.app')

@section('title', 'Admin Dashboard')

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
        color: var(--text-main) !important;
    }
    .dash-subtext {
        color: var(--text-muted) !important;
    }

    /* Unified Accent Blue Pill Action Buttons */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        border-radius: 50px;
        padding: 6px 14px;
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

    .activity-table td {
        border-bottom: 1px solid var(--table-border) !important;
        padding: 12px 8px !important;
        background: transparent !important;
        color: var(--text-main) !important;
    }
</style>

{{-- Welcome Header Section --}}
<div class="mb-4">
    <h2 class="fw-bold dash-title mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
    <p class="dash-subtext mb-0">Here's what's happening with your loan system today.</p>
</div>

{{-- Total Portfolio Summary --}}
<div class="row g-3 mb-1">
    <!-- Total Released Loans (With Clean Peso Sign) -->
    <div class="col-md-6">
        <div class="dash-card h-100 p-4" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="icon-box" style="background: rgba(16, 185, 129, 0.15); color: #059669;">
                    <span class="fw-bold fs-4" style="line-height: 1;">₱</span>
                </div>
                <h6 class="dash-subtext mb-0 fw-semibold">Total Released Loans</h6>
            </div>
            <h2 class="fw-bold my-1" style="color: #10b981 !important;">₱{{ number_format($totalReleased, 2) }}</h2>
            <p class="mb-0 small dash-subtext">Total disbursed capital (active + completed loans)</p>
        </div>
    </div>

    <!-- Total Collected Amount -->
    <div class="col-md-6">
        <div class="dash-card h-100 p-4" style="border-left: 4px solid #0284c7 !important;">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="icon-box" style="background: rgba(56, 189, 248, 0.15); color: #0284c7;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4z"></path>
                    </svg>
                </div>
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
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="icon-box" style="background: rgba(96, 165, 250, 0.15); color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <div>
                        <h6 class="dash-subtext mb-0 small">Pending Loans</h6>
                        <h3 class="dash-title fw-bold mb-0">{{ $pendingLoansCount }}</h3>
                    </div>
                </div>
                <p class="mb-0 small dash-subtext">Review new loan applications</p>
            </div>
        </a>
    </div>

    <!-- Pending Payments -->
    <div class="col-md-3">
        <a href="{{ route('admin.payments.index') }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="icon-box" style="background: rgba(251, 191, 36, 0.15); color: #d97706;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </div>
                    <div>
                        <h6 class="dash-subtext mb-0 small">Pending Payments</h6>
                        <h3 class="dash-title fw-bold mb-0">{{ $pendingPaymentsCount }}</h3>
                    </div>
                </div>
                <p class="mb-0 small dash-subtext">Verify submitted GCash payments</p>
            </div>
        </a>
    </div>

    <!-- Overdue Installments -->
    <div class="col-md-3">
        <a href="{{ route('admin.reports.loans', ['status' => 'active']) }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="icon-box" style="background: rgba(248, 113, 113, 0.15); color: #dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div>
                        <h6 class="dash-subtext mb-0 small">Overdue Installments</h6>
                        <h3 class="fw-bold mb-0" style="color: #ef4444 !important;">{{ $overdueCount }}</h3>
                    </div>
                </div>
                <p class="mb-0 small dash-subtext">Monitor overdue accounts & penalties</p>
            </div>
        </a>
    </div>

    <!-- Security Events -->
    <div class="col-md-3">
        <a href="{{ route('admin.security-events.index') }}" class="text-decoration-none">
            <div class="dash-card h-100 p-3">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="icon-box" style="background: rgba(56, 189, 248, 0.15); color: #0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div>
                        <h6 class="dash-subtext mb-0 small">Security Events (24h)</h6>
                        <h3 class="dash-title fw-bold mb-0">{{ $recentSecurityEventsCount }}</h3>
                    </div>
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
                <!-- Unified Action Button -->
                <a href="{{ route('admin.audit-logs.index') }}" class="glass-btn-action">
                    <span>View all audit logs</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
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
            <!-- Unified Action Button -->
            <div class="mt-3 pt-2 text-end">
                <a href="{{ route('admin.users.index') }}" class="glass-btn-action">
                    <span>Manage registered borrowers</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
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
            <!-- Unified Action Button -->
            <div class="mt-3 text-end">
                <a href="{{ route('admin.reports.index') }}" class="glass-btn-action">
                    <span>Open Reports</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
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
            <!-- Unified Action Button -->
            <div class="mt-3 text-end">
                <a href="{{ route('admin.audit-logs.index') }}" class="glass-btn-action">
                    <span>Open Audit Logs</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection