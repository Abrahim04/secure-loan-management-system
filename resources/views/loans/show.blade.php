@extends('layouts.app')

@section('title', 'Loan Details')

@section('content')
<h4 class="mb-4">Loan Details</h4>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>Loan Type:</strong><br>{{ $loan->loanType->name }}</div>
            <div class="col-md-3"><strong>Principal:</strong><br>₱{{ number_format($loan->principal_amount, 2) }}</div>
            <div class="col-md-3"><strong>Interest:</strong><br>₱{{ number_format($loan->interest_amount, 2) }}</div>
            <div class="col-md-3"><strong>Total Payable:</strong><br>₱{{ number_format($loan->total_payable, 2) }}</div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3"><strong>Term:</strong><br>{{ $loan->term_months }} months</div>
            <div class="col-md-3"><strong>Monthly Payment:</strong><br>₱{{ number_format($loan->monthly_payment, 2) }}</div>
            <div class="col-md-3"><strong>Status:</strong><br>{{ ucfirst($loan->status) }}</div>
            <div class="col-md-3"><strong>Applied:</strong><br>{{ $loan->applied_at->format('M d, Y') }}</div>
        </div>
        @if ($loan->status === 'rejected')
            <hr>
            <div class="text-danger"><strong>Rejection reason:</strong> {{ $loan->rejection_reason }}</div>
        @endif
    </div>
</div>

@if ($loan->paymentSchedules->isNotEmpty())
    <h5 class="mb-3">Payment Schedule</h5>
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
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
                @foreach ($loan->paymentSchedules as $schedule)
                    <tr>
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
@endif
@endsection
