@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h3 class="mb-4">Admin Dashboard</h3>

{{-- Total Portfolio Summary --}}
<div class="row g-3 mb-1">
    <div class="col-md-6">
        <div class="card shadow-sm border-success h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Released Loans</h6>
                <h2 class="text-success">₱{{ number_format($totalReleased, 2) }}</h2>
                <p class="mb-0 small text-muted">Total disbursed capital (active + completed loans)</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-primary h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Collected Amount</h6>
                <h2 class="text-primary">₱{{ number_format($totalCollected, 2) }}</h2>
                <p class="mb-0 small text-muted">Sum of all verified payments</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-3">
        <a href="{{ route('admin.loans.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Pending Loans</h6>
                    <h3>{{ $pendingLoansCount }}</h3>
                    <p class="mb-0 small text-muted">Review new loan applications</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.payments.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Pending Payments</h6>
                    <h3>{{ $pendingPaymentsCount }}</h3>
                    <p class="mb-0 small text-muted">Verify submitted GCash payments</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.reports.loans', ['status' => 'active']) }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Overdue Installments</h6>
                    <h3 class="text-danger">{{ $overdueCount }}</h3>
                    <p class="mb-0 small text-muted">Monitor overdue accounts and penalties</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.security-events.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Security Events (24h)</h6>
                    <h3>{{ $recentSecurityEventsCount }}</h3>
                    <p class="mb-0 small text-muted">Failed logins and suspicious activity</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Recent Activity --}}
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted mb-0">Recent Activity</h6>
                    <a href="{{ route('admin.audit-logs.index') }}" class="small">View all audit logs →</a>
                </div>
                @if ($recentActivity->isEmpty())
                    <p class="text-muted small mb-0">No recent activity.</p>
                @else
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach ($recentActivity as $log)
                                <tr>
                                    <td class="small">{{ $log->user->name ?? 'System' }}</td>
                                    <td class="small">{{ $log->action }}</td>
                                    <td class="small text-muted text-end">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- User Management quick access --}}
    <div class="col-md-5">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted mb-3">User Management</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold text-success fs-5">{{ $activeUsersCount }}</div>
                            <div class="small text-muted">Active</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-danger fs-5">{{ $blockedUsersCount }}</div>
                            <div class="small text-muted">Blocked</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning fs-5">{{ $unverifiedUsersCount }}</div>
                            <div class="small text-muted">Unverified</div>
                        </div>
                    </div>
                    <p class="mb-0 small text-muted mt-3">Manage registered borrowers →</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Reports</h6>
                <p class="mb-2">Loan, payment, and penalty reports with full history.</p>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-primary">Open Reports</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Audit Logs</h6>
                <p class="mb-2">Full history of admin and system actions.</p>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-sm btn-outline-primary">Open Audit Logs</a>
            </div>
        </div>
    </div>
</div>
@endsection
