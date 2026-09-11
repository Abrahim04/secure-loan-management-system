@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h3 class="mb-4">Admin Dashboard</h3>

<div class="row g-3">
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