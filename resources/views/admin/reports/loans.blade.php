@extends('layouts.app')

@section('title', 'Loan Report')

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

<h4 class="mb-4">Loan Report</h4>

<form method="GET" class="mb-4">
    <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
        @foreach (['all' => 'All Statuses', 'pending' => 'Pending', 'active' => 'Active', 'completed' => 'Completed', 'rejected' => 'Rejected'] as $value => $label)
            <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</form>

@if ($loans->isEmpty())
    <div class="alert alert-info">No loans found for this filter.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle table-sm">
            <thead class="table-light">
                <tr>
                    <th>Borrower</th>
                    <th>Loan Type</th>
                    <th>Principal</th>
                    <th>Total Payable</th>
                    <th>Term</th>
                    <th>Status</th>
                    <th>Applied</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $loan)
                    <tr>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->loanType->name }}</td>
                        <td>₱{{ number_format($loan->principal_amount, 2) }}</td>
                        <td>₱{{ number_format($loan->total_payable, 2) }}</td>
                        <td>{{ $loan->term_months }} mo.</td>
                        <td>{{ ucfirst($loan->status) }}</td>
                        <td>{{ $loan->applied_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $loans->links() }}
@endif
@endsection
