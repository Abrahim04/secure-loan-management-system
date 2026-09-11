@extends('layouts.app')

@section('title', 'Loan Review')

@section('content')
<h4 class="mb-4">Pending Loan Applications</h4>

@if ($loans->isEmpty())
    <div class="alert alert-info">No pending loan applications.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Applicant</th>
                    <th>Loan Type</th>
                    <th>Principal</th>
                    <th>Term</th>
                    <th>Applied</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->loanType->name }}</td>
                        <td>₱{{ number_format($loan->principal_amount, 2) }}</td>
                        <td>{{ $loan->term_months }} mo.</td>
                        <td>{{ $loan->applied_at->format('M d, Y') }}</td>
                        <td><a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-primary">Review</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $loans->links() }}
@endif
@endsection
