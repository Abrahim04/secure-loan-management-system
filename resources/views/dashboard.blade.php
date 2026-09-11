@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('loans.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">My Loans</h6>
                    <p class="mb-0">View active and past loan applications.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('loans.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Payment Schedule</h6>
                    <p class="mb-0">See upcoming and overdue payments (open a loan to view its schedule).</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Notifications</h6>
                    <p class="mb-0">Loan and payment updates.</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
