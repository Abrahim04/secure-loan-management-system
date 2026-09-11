@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

{{-- Next Due Date Banner --}}
@if ($nextDue)
    <div class="alert {{ $nextDue->status === 'overdue' ? 'alert-danger' : 'alert-primary' }} d-flex justify-content-between align-items-center mb-3">
        <div>
            <strong>{{ $nextDue->status === 'overdue' ? 'Payment Overdue!' : 'Next Payment' }}</strong>:
            ₱{{ number_format($nextDue->amount_due + $nextDue->penalty_amount - $nextDue->amount_paid, 2) }}
            due on {{ $nextDue->due_date->format('M d, Y') }}
            ({{ $nextDue->loan->loanType->name }} #{{ $nextDue->loan_id }}, Month {{ $nextDue->month_number }})
        </div>
        <a href="{{ route('payments.create', $nextDue) }}" class="btn btn-sm {{ $nextDue->status === 'overdue' ? 'btn-light' : 'btn-outline-light' }}">Pay Now</a>
    </div>
@endif

{{-- Row 1: Balance, Repayment Progress, Credit Limit --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm h-100 border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Outstanding Balance</h6>
                <h2 class="text-primary">₱{{ number_format($outstandingBalance, 2) }}</h2>
                <p class="mb-0 small text-muted">Across all active loans</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Repayment Progress</h6>
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $repaymentPercent }}%;" aria-valuenow="{{ $repaymentPercent }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $repaymentPercent }}%
                    </div>
                </div>
                <p class="mb-0 small text-muted">
                    ₱{{ number_format($totalPaidActive, 2) }} paid of ₱{{ number_format($totalPayableActive, 2) }} (active loans)
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100 {{ $isEligibleForReloan ? 'border-success' : '' }}">
            <div class="card-body">
                <h6 class="text-muted">Borrowing Status</h6>
                @if ($isEligibleForReloan)
                    <p class="mb-1 text-success fw-bold">Eligible for re-loan</p>
                    <p class="mb-0">Up to ₱{{ number_format($availableCapacity, 2) }}</p>
                @elseif ($hasOverdue)
                    <p class="mb-1 text-danger fw-bold">Not eligible</p>
                    <p class="mb-0 small text-muted">Resolve your overdue payment first.</p>
                @else
                    <p class="mb-1 text-muted fw-bold">Not eligible</p>
                    <p class="mb-0 small text-muted">You're at your current borrowing limit.</p>
                @endif
                <p class="mb-0 small text-muted mt-2">Estimate only, not a guaranteed approval.</p>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: My Loans, Payment Schedule, Notifications --}}
<div class="row g-3 mb-3">
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
        <a href="{{ route('payment-schedule.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Payment Schedule</h6>
                    <p class="mb-0">See upcoming and overdue payments across all your loans.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('notifications.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Notifications @if($unreadCount > 0)<span class="badge bg-danger">{{ $unreadCount }}</span>@endif</h6>
                    @if ($unreadNotifications->isEmpty())
                        <p class="mb-0 text-muted">No new notifications.</p>
                    @else
                        <ul class="list-unstyled mb-0 small">
                            @foreach ($unreadNotifications as $n)
                                <li class="text-truncate">• {{ $n->title }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Quick Payment History --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="text-muted mb-0">Recent Payment Submissions</h6>
            <a href="{{ route('payment-schedule.index') }}" class="small">View full schedule →</a>
        </div>
        @if ($recentPayments->isEmpty())
            <p class="text-muted small mb-0">You haven't submitted any payments yet.</p>
        @else
            <table class="table table-sm mb-0">
                <tbody>
                    @foreach ($recentPayments as $payment)
                        <tr>
                            <td class="small">{{ $payment->paymentSchedule->loan->loanType->name }} #{{ $payment->paymentSchedule->loan_id }} — Month {{ $payment->paymentSchedule->month_number }}</td>
                            <td class="small">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="small">
                                <span class="badge bg-{{ match($payment->status) {
                                    'verified' => 'success',
                                    'rejected' => 'danger',
                                    default => 'warning',
                                } }}">
                                    {{ $payment->status === 'pending' ? 'Pending Admin Verification' : ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="small text-muted text-end">{{ $payment->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Quick Loan Calculator --}}
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="mb-3">Quick Loan Calculator</h6>
        <p class="small text-muted">Estimate your monthly payment before applying. This is an estimate only — final terms are confirmed when your application is approved.</p>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Loan Type</label>
                <select id="calc-loan-type" class="form-select">
                    <option value="">Select a loan type</option>
                    @foreach ($loanTypes as $type)
                        <option value="{{ $type->id }}"
                            data-rate="{{ $type->interest_rate }}"
                            data-min-amount="{{ $type->min_amount }}"
                            data-max-amount="{{ $type->max_amount }}"
                            data-min-term="{{ $type->min_term_months }}"
                            data-max-term="{{ $type->max_term_months }}">
                            {{ $type->name }} ({{ $type->interest_rate }}%)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Principal Amount (₱)</label>
                <input type="number" id="calc-amount" class="form-control" step="0.01" placeholder="e.g. 10000">
            </div>
            <div class="col-md-4">
                <label class="form-label">Term (months)</label>
                <input type="number" id="calc-term" class="form-control" placeholder="e.g. 6">
            </div>
        </div>

        <div id="calc-result" class="mt-3 d-none">
            <div class="alert alert-secondary mb-0">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="small text-muted">Interest</div>
                        <div class="fw-bold" id="calc-interest">₱0.00</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Total Payable</div>
                        <div class="fw-bold" id="calc-total">₱0.00</div>
                    </div>
                    <div class="col-md-4">
                        <div class="small text-muted">Est. Monthly Payment</div>
                        <div class="fw-bold text-primary" id="calc-monthly">₱0.00</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="calc-error" class="mt-3 d-none">
            <div class="alert alert-warning mb-0 small" id="calc-error-text"></div>
        </div>

        <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm mt-3">Apply for a Loan</a>
    </div>
</div>

@push('scripts')
<script>
    const calcType = document.getElementById('calc-loan-type');
    const calcAmount = document.getElementById('calc-amount');
    const calcTerm = document.getElementById('calc-term');
    const calcResult = document.getElementById('calc-result');
    const calcErrorBox = document.getElementById('calc-error');
    const calcErrorText = document.getElementById('calc-error-text');

    function formatPeso(n) {
        return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalculate() {
        const opt = calcType.options[calcType.selectedIndex];
        const amount = parseFloat(calcAmount.value);
        const term = parseInt(calcTerm.value);

        calcResult.classList.add('d-none');
        calcErrorBox.classList.add('d-none');

        if (!opt || !opt.value || isNaN(amount) || isNaN(term) || amount <= 0 || term <= 0) {
            return;
        }

        const rate = parseFloat(opt.dataset.rate);
        const minAmount = parseFloat(opt.dataset.minAmount);
        const maxAmount = parseFloat(opt.dataset.maxAmount);
        const minTerm = parseInt(opt.dataset.minTerm);
        const maxTerm = parseInt(opt.dataset.maxTerm);

        if (amount < minAmount || amount > maxAmount) {
            calcErrorText.textContent = `Amount must be between ₱${minAmount} and ₱${maxAmount} for this loan type.`;
            calcErrorBox.classList.remove('d-none');
            return;
        }
        if (term < minTerm || term > maxTerm) {
            calcErrorText.textContent = `Term must be between ${minTerm} and ${maxTerm} months for this loan type.`;
            calcErrorBox.classList.remove('d-none');
            return;
        }

        // Mirrors LoanService::calculate() — flat-rate interest over the full term.
        const interest = Math.round(amount * (rate / 100) * 100) / 100;
        const total = Math.round((amount + interest) * 100) / 100;
        const monthly = Math.round((total / term) * 100) / 100;

        document.getElementById('calc-interest').textContent = formatPeso(interest);
        document.getElementById('calc-total').textContent = formatPeso(total);
        document.getElementById('calc-monthly').textContent = formatPeso(monthly);
        calcResult.classList.remove('d-none');
    }

    [calcType, calcAmount, calcTerm].forEach(el => el.addEventListener('input', recalculate));
</script>
@endpush
@endsection
