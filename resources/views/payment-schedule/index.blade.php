@extends('layouts.app')

@section('title', 'Payment Schedule')

@section('content')
<h4 class="mb-4">Payment Schedule</h4>

@if ($schedules->isEmpty())
    <div class="alert alert-info">You have no payment schedules yet. Apply for a loan and get it approved to see installments here.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Loan</th>
                    <th>Month</th>
                    <th>Due Date</th>
                    <th>Amount Due</th>
                    <th>Penalty</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr class="{{ $schedule->status === 'overdue' ? 'table-danger' : '' }}">
                        <td>
                            <a href="{{ route('loans.show', $schedule->loan_id) }}">
                                {{ $schedule->loan->loanType->name }} #{{ $schedule->loan_id }}
                            </a>
                        </td>
                        <td>{{ $schedule->month_number }}</td>
                        <td>{{ $schedule->due_date->format('M d, Y') }}</td>
                        <td>₱{{ number_format($schedule->amount_due, 2) }}</td>
                        <td>₱{{ number_format($schedule->penalty_amount, 2) }}</td>
                        <td>₱{{ number_format($schedule->amount_paid, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ match($schedule->status) {
                                'paid' => 'success',
                                'overdue' => 'danger',
                                'partially_paid' => 'warning',
                                default => 'secondary',
                            } }}">
                                {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                            </span>
                        </td>
                        <td>
                            @if ($schedule->status !== 'paid')
                                <a href="{{ route('payments.create', $schedule) }}" class="btn btn-sm btn-outline-primary">Pay via GCash</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $schedules->links() }}
@endif
@endsection
