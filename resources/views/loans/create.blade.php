@extends('layouts.app')

@section('title', 'Apply for a Loan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Apply for a Loan</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('loans.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Loan Type</label>
                        <select name="loan_type_id" class="form-select" required>
                            <option value="">Select a loan type</option>
                            @foreach ($loanTypes as $type)
                                <option value="{{ $type->id }}"
                                    data-min-amount="{{ $type->min_amount }}"
                                    data-max-amount="{{ $type->max_amount }}"
                                    data-min-term="{{ $type->min_term_months }}"
                                    data-max-term="{{ $type->max_term_months }}"
                                    data-rate="{{ $type->interest_rate }}"
                                    {{ old('loan_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->interest_rate }}% interest)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Principal Amount (₱)</label>
                        <input type="number" step="0.01" name="principal_amount" class="form-control" value="{{ old('principal_amount') }}" required>
                        <div class="form-text" id="amount-hint">Select a loan type to see the allowed range.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Term (months)</label>
                        <input type="number" name="term_months" class="form-control" value="{{ old('term_months') }}" required>
                        <div class="form-text" id="term-hint">Select a loan type to see the allowed range.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Purpose (optional)</label>
                        <textarea name="purpose" class="form-control" rows="3">{{ old('purpose') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const select = document.querySelector('select[name="loan_type_id"]');
    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt.value) return;
        document.getElementById('amount-hint').textContent =
            `Allowed: ₱${opt.dataset.minAmount} – ₱${opt.dataset.maxAmount}`;
        document.getElementById('term-hint').textContent =
            `Allowed: ${opt.dataset.minTerm} – ${opt.dataset.maxTerm} months`;
    });
</script>
@endpush
@endsection
