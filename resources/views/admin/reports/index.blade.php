@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<h4 class="mb-4">Reports</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Borrowers</h6>
                <h3>{{ $stats['total_users'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Active Loans</h6>
                <h3>{{ $stats['active_loans'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Completed Loans</h6>
                <h3>{{ $stats['completed_loans'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Overdue Installments</h6>
                <h3 class="text-danger">{{ $stats['overdue_installments'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Pending Loan Applications</h6>
                <h3>{{ $stats['pending_loans'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Pending Payment Verifications</h6>
                <h3>{{ $stats['pending_payments'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Loans (all-time)</h6>
                <h3>{{ $stats['total_loans'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Penalties Collected</h6>
                <h3>₱{{ number_format($stats['total_penalties_collected'], 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3">Detailed Reports</h5>
<div class="list-group">
    <a href="{{ route('admin.reports.loans') }}" class="list-group-item list-group-item-action">Loan Report (all statuses, filterable)</a>
    <a href="{{ route('admin.reports.payments') }}" class="list-group-item list-group-item-action">Payment Report</a>
    <a href="{{ route('admin.reports.penalties') }}" class="list-group-item list-group-item-action">Penalty Report</a>
</div>
@endsection
