@extends('layouts.app')

@section('title', 'Payment Report')

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

<h4 class="mb-4">Payment Report</h4>

@if ($payments->isEmpty())
    <div class="alert alert-info">No payments found.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle table-sm">
            <thead class="table-light">
                <tr>
                    <th>Payer</th>
                    <th>Loan</th>
                    <th>Reference #</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name }}</td>
                        <td>#{{ $payment->paymentSchedule->loan_id }} (Month {{ $payment->paymentSchedule->month_number }})</td>
                        <td>{{ $payment->gcash_reference_number }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ match($payment->status) {
                                'verified' => 'success',
                                'rejected' => 'danger',
                                default => 'warning',
                            } }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $payments->links() }}
@endif
@endsection
