@extends('layouts.app')

@section('title', 'Payment Verification')

@section('content')
<h4 class="mb-4">Pending Payments</h4>

@if ($payments->isEmpty())
    <div class="alert alert-info">No pending payments to verify.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Payer</th>
                    <th>Loan</th>
                    <th>Reference #</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th></th>
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
                        <td><a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-primary">Review</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $payments->links() }}
@endif
@endsection
