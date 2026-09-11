@extends('layouts.app')

@section('title', 'Penalty Report')

@section('content')
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
