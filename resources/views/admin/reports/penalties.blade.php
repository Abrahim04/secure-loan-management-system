@extends('layouts.app')

@section('title', 'Penalty Report')

@section('content')

<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to Reports</span>
    </a>
</div>

<h4 class="mb-4">Penalty Report</h4>

@if ($penalties->isEmpty())
    <div class="alert alert-info">No penalties have been applied.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle table-sm">
            <thead class="table-light">
                <tr>
                    <th>Borrower</th>
                    <th>Loan</th>
                    <th>Days Overdue</th>
                    <th>Penalty Amount</th>
                    <th>Rate/Day</th>
                    <th>Applied</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penalties as $penalty)
                    <tr>
                        <td>{{ $penalty->paymentSchedule->loan->user->name }}</td>
                        <td>#{{ $penalty->paymentSchedule->loan_id }} (Month {{ $penalty->paymentSchedule->month_number }})</td>
                        <td>{{ $penalty->days_overdue }}</td>
                        <td>₱{{ number_format($penalty->amount, 2) }}</td>
                        <td>₱{{ number_format($penalty->rate_per_day, 2) }}</td>
                        <td>{{ $penalty->applied_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $penalties->links() }}
@endif
@endsection
