@extends('layouts.app')

@section('title', 'Apply for a Loan')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling (Matches Admin & Loan Review Theme) -->
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Modern Back Pill Button */
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.1);
        border: 1px solid rgba(100, 116, 139, 0.25);
        color: var(--text-main, #334155);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.2);
        color: var(--text-main, #0f172a);
        transform: translateX(-3px);
    }

    /* Glass Form Inputs */
    .glass-input {
        background-color: var(--input-bg, var(--dash-card-bg, #ffffff)) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.15)) !important;
        color: var(--text-main, #0f172a) !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        font-size: 0.9rem !important;
        transition: all 0.2s ease !important;
    }
    .glass-input:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25) !important;
        outline: none;
    }

    /* Custom Dropdown Container & Menu Styling (Same as Dashboard) */
    .custom-dropdown-container {
        position: relative;
        width: 100%;
    }
    .custom-dropdown-toggle {
        width: 100%;
        background-color: var(--input-bg, var(--dash-card-bg, #ffffff));
        color: var(--text-main, #0f172a);
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.15));
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }
    .custom-dropdown-toggle:hover,
    .custom-dropdown-container.show .custom-dropdown-toggle {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
    }
    .custom-dropdown-toggle i {
        transition: transform 0.2s ease;
        font-size: 0.85rem;
    }
    .custom-dropdown-container.show .custom-dropdown-toggle i {
        transform: rotate(180deg);
    }

    .custom-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background-color: var(--dash-card-bg, #ffffff) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.12));
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        z-index: 1050;
        display: none;
        max-height: 240px;
        overflow-y: auto;
    }
    .custom-dropdown-container.show .custom-dropdown-menu {
        display: block;
        animation: dropDownFade 0.15s ease-out;
    }
    @keyframes dropDownFade {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Dropdown Item Styling */
    .custom-dropdown-item {
        padding: 9px 14px;
        color: var(--text-main, #334155) !important;
        font-weight: 500;
        font-size: 0.9rem;
        border-radius: 8px;
        cursor: pointer;
        background-color: transparent !important;
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    .custom-dropdown-item.selected {
        background-color: rgba(2, 132, 199, 0.12) !important;
        color: #0284c7 !important;
        font-weight: 600;
    }
    .custom-dropdown-item:hover {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    .custom-dropdown-menu:hover .custom-dropdown-item.selected:not(:hover) {
        background-color: transparent !important;
        color: var(--text-main, #334155) !important;
        font-weight: 500;
    }

    /* Primary Rounded Action Button (Centered, No Icon) */
    .btn-submit-glass {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 11px 24px;
        border-radius: 50px;
        width: 100%;
        transition: all 0.25s ease;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
    }
    .btn-submit-glass:hover {
        background: #0369a1;
        border-color: #0369a1;
        box-shadow: 0 6px 18px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('loans.index') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to My Loans</span>
        </a>
    </div>

    {{-- Title Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Apply for a Loan</h2>
        <p class="text-muted small mb-0">Fill out the form below to apply for a new loan.</p>
    </div>

    {{-- Form Card --}}
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="dash-card p-4 p-md-5">

                @if ($errors->any())
                    <div class="p-3 mb-4 rounded-3" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);">
                        <ul class="mb-0 text-danger small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('loans.store') }}" id="loanForm">
                    @csrf

                    {{-- Loan Type Custom Dropdown --}}
                    <div class="mb-4">
                        <label class="form-label text-main fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Loan Type</label>

                        <!-- Hidden input para ma-submit pa rin sa controller ang napiling loan_type_id -->
                        <input type="hidden" name="loan_type_id" id="loan_type_id" value="{{ old('loan_type_id') }}" required>

                        <div class="custom-dropdown-container" id="loanTypeDropdown">
                            <div class="custom-dropdown-toggle">
                                <span id="selectedLoanTypeLabel">Select a loan type</span>
                                <i class="bi bi-chevron-down ms-2"></i>
                            </div>
                            <div class="custom-dropdown-menu">
                                <div class="custom-dropdown-item selected" data-value="" data-label="Select a loan type">
                                    Select a loan type
                                </div>
                                @foreach ($loanTypes as $type)
                                    <div class="custom-dropdown-item {{ old('loan_type_id') == $type->id ? 'selected' : '' }}" 
                                        data-value="{{ $type->id }}"
                                        data-label="{{ $type->name }} ({{$type->interest_rate }}% interest)"
                                        data-min-amount="{{ $type->min_amount }}"
                                        data-max-amount="{{ $type->max_amount }}"
                                        data-min-term="{{ $type->min_term_months }}"
                                        data-max-term="{{ $type->max_term_months }}">
                                        {{ $type->name }} ({{$type->interest_rate }}% interest)
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Principal Amount --}}
                    <div class="mb-4">
                        <label class="form-label text-main fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Principal Amount (₱)</label>
                        <input type="number" step="0.01" name="principal_amount" class="form-control glass-input" value="{{ old('principal_amount') }}" placeholder="e.g. 10000" required>
                        <div class="text-muted extra-small mt-1 fs-7" id="amount-hint" style="font-size: 0.8rem;">Select a loan type to see the allowed range.</div>
                    </div>

                    {{-- Term --}}
                    <div class="mb-4">
                        <label class="form-label text-main fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Term (months)</label>
                        <input type="number" name="term_months" class="form-control glass-input" value="{{ old('term_months') }}" placeholder="e.g. 6" required>
                        <div class="text-muted extra-small mt-1 fs-7" id="term-hint" style="font-size: 0.8rem;">Select a loan type to see the allowed range.</div>
                    </div>

                    {{-- Purpose --}}
                    <div class="mb-4">
                        <label class="form-label text-main fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Purpose (optional)</label>
                        <textarea name="purpose" class="form-control glass-input" rows="3" placeholder="Write the reason or purpose of the loan...">{{ old('purpose') }}</textarea>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" class="btn-submit-glass">
                            <span>Submit Application</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownContainer = document.getElementById('loanTypeDropdown');
        const dropdownToggle = dropdownContainer.querySelector('.custom-dropdown-toggle');
        const selectedLabel = document.getElementById('selectedLoanTypeLabel');
        const dropdownItems = dropdownContainer.querySelectorAll('.custom-dropdown-item');
        const hiddenInput = document.getElementById('loan_type_id');

        const amountHint = document.getElementById('amount-hint');
        const termHint = document.getElementById('term-hint');

        // Toggle Custom Dropdown
        dropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownContainer.classList.toggle('show');
        });

        // Close Dropdown on outside click
        document.addEventListener('click', () => {
            dropdownContainer.classList.remove('show');
        });

        function updateHints(item) {
            if (!item || !item.dataset.value) {
                amountHint.textContent = 'Select a loan type to see the allowed range.';
                termHint.textContent = 'Select a loan type to see the allowed range.';
                return;
            }
            const minAmount = parseFloat(item.dataset.minAmount);
            const maxAmount = parseFloat(item.dataset.maxAmount);
            const minTerm = item.dataset.minTerm;
            const maxTerm = item.dataset.maxTerm;

            amountHint.textContent = `Allowed: ₱${minAmount.toLocaleString()} – ₱${maxAmount.toLocaleString()}`;
            termHint.textContent = `Allowed: ${minTerm} – ${maxTerm} months`;
        }

        // Selection Event
        dropdownItems.forEach(item => {
            if (item.classList.contains('selected') && item.dataset.value) {
                selectedLabel.textContent = item.dataset.label;
                updateHints(item);
            }

            item.addEventListener('click', () => {
                dropdownItems.forEach(i => i.classList.remove('selected'));
                item.classList.add('selected');
                
                selectedLabel.textContent = item.dataset.label;
                hiddenInput.value = item.dataset.value;
                
                updateHints(item);
                dropdownContainer.classList.remove('show');
            });
        });
    });
</script>
@endpush
@endsection