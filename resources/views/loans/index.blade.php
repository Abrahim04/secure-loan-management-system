@extends('layouts.app')

@section('title', 'My Loans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Loans</h4>
    <a href="{{ route('loans.create') }}" class="btn btn-primary">Apply for a Loan</a>
</div>

@if ($loans->isEmpty())
    <div class="alert alert-info">You haven't applied for any loans yet.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Loan Type</th>
                    <th>Principal</th>
                    <th>Term</th>
                    <th>Monthly Payment</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loan->loanType->name }}</td>
                        <td>₱{{ number_format($loan->principal_amount, 2) }}</td>
                        <td>{{ $loan->term_months }} mo.</td>
                        <td>₱{{ number_format($loan->monthly_payment, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ match($loan->status) {
                                'pending' => 'warning',
                                'approved', 'active' => 'success',
                                'rejected' => 'danger',
                                'completed' => 'secondary',
                                default => 'light',
                            } }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td>{{ $loan->applied_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $loans->links() }}
@endif
@endsection
