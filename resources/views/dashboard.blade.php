@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Dynamic Theme Compatible Styling (Supporting Light & Dark Modes) -->
<style>
    /* Base Cards - Theme Responsive */
    .user-dash-card {
        background-color: var(--dash-card-bg, #ffffff) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.08)) !important;
        border-radius: 14px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: var(--dash-card-shadow, 0 4px 20px rgba(0, 0, 0, 0.05));
    }
    .user-dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Icon Box Base */
    .user-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Clean & Professional Banners */
    .banner-overdue {
        background-color: var(--dash-card-bg, #ffffff) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.12)) !important;
        border-left: 4px solid #e11d48 !important;
        border-radius: 12px;
    }
    .banner-normal {
        background-color: var(--dash-card-bg, #ffffff) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.12)) !important;
        border-left: 4px solid #0284c7 !important;
        border-radius: 12px;
    }

    /* Simple & Professional Plain Text Status Colors */
    .status-text-verified {
        color: #16a34a !important;
        font-weight: 600;
    }
    .status-text-rejected {
        color: #dc2626 !important;
        font-weight: 600;
    }
    .status-text-pending {
        color: #d97706 !important;
        font-weight: 600;
    }

    /* Form Controls & Inputs - Theme Compatible */
    .user-form-input {
        background-color: var(--input-bg, var(--dash-card-bg, #ffffff)) !important;
        color: var(--text-main, #0f172a) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.15)) !important;
        border-radius: 10px !important;
        padding: 0.65rem 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .user-form-input:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25) !important;
        outline: none;
    }
    .user-form-input::placeholder {
        color: var(--text-muted, #64748b) !important;
        opacity: 0.7;
    }

    /* Custom Dropdown Trigger Container */
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
        padding: 0.65rem 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
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

    /* Dynamic Menu List (Configured to Drop UP) */
    .custom-dropdown-menu {
        position: absolute;
        bottom: calc(100% + 6px); /* Pataas ang labas ng menu sa itaas ng input box */
        top: auto;
        left: 0;
        right: 0;
        background-color: var(--dash-card-bg, #ffffff) !important;
        border: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.12));
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.15), 0 -8px 10px -6px rgba(0, 0, 0, 0.05);
        z-index: 1050;
        display: none;
        max-height: 240px;
        overflow-y: auto;
    }
    .custom-dropdown-container.show .custom-dropdown-menu {
        display: block;
        animation: dropUpFade 0.15s ease-out;
    }
    @keyframes dropUpFade {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Dropdown Item Base Styling */
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

    /* Action Buttons */
    .glass-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7 !important;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.825rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .glass-btn-action:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Modern Glass Primary Button */
    .glass-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #0284c7 !important;
        border: 1px solid #0284c7 !important;
        color: #ffffff !important;
        border-radius: 50px !important;
        padding: 8px 22px !important;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    }
    .glass-btn-primary:hover {
        background: #0369a1 !important;
        border-color: #0369a1 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }

    /* Dynamic Table Styles */
    .user-table {
        background-color: transparent !important;
    }
    .user-table th, 
    .user-table td {
        background-color: transparent !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(0, 0, 0, 0.08)) !important;
        color: var(--text-main, #0f172a) !important;
    }

    .calc-summary-box {
        background: rgba(2, 132, 199, 0.08) !important;
        border: 1px solid rgba(2, 132, 199, 0.25) !important;
        border-radius: 12px;
    }
</style>

<div class="container-fluid px-0">
    <!-- Header Greeting -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--text-main, #0f172a);">Welcome back, <span style="color: #0284c7;">{{ auth()->user()->name }}</span> </h2>
            <p class="mb-0 small" style="color: var(--text-muted, #64748b);">Here is the summary of your loans and obligations as of today.</p>
        </div>
    </div>

    {{-- Next Due Date Banner --}}
    @if ($nextDue)
        <div class="p-3 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 {{ $nextDue->status === 'overdue' ? 'banner-overdue' : 'banner-normal' }}">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: {{ $nextDue->status === 'overdue' ? '#e11d48' : '#0284c7' }};">
                        {{ $nextDue->status === 'overdue' ? 'Payment Overdue' : 'Next Payment Due' }}
                    </span>
                </div>
                <div class="fw-bold fs-5" style="color: var(--text-main, #0f172a);">
                    ₱{{ number_format($nextDue->amount_due + $nextDue->penalty_amount -$nextDue->amount_paid, 2) }}
                    <span class="fs-6 fw-normal" style="color: var(--text-muted, #64748b);">due on {{ $nextDue->due_date->format('M d, Y') }}</span>
                </div>
                <div class="small" style="color: var(--text-muted, #64748b);">
                    {{ $nextDue->loan->loanType->name }} #{{ $nextDue->loan_id }} • Month {{ $nextDue->month_number }}
                </div>
            </div>
            <a href="{{ route('payments.create', $nextDue) }}" class="btn {{ $nextDue->status === 'overdue' ? 'btn-outline-danger' : 'btn-primary' }} px-4 py-2 fw-semibold shadow-sm text-nowrap" style="border-radius: 8px;">
                Pay Now 
            </a>
        </div>
    @endif

    {{-- Row 1: Key Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card user-dash-card h-100 p-3">
                <div class="card-body d-flex flex-column justify-content-between p-1">
                    <div>
                        <div class="mb-3">
                            <span class="small fw-bold text-uppercase" style="color: var(--text-muted, #64748b);">Total Outstanding Balance</span>
                        </div>
                        <h2 class="fw-bold mb-1" style="color: #0284c7;">₱{{ number_format($outstandingBalance, 2) }}</h2>
                    </div>
                    <p class="mb-0 small mt-2" style="color: var(--text-muted, #64748b);"><i class="bi bi-info-circle me-1"></i> Across all active loans</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card user-dash-card h-100 p-3">
                <div class="card-body d-flex flex-column justify-content-between p-1">
                    <div>
                        <div class="mb-3">
                            <span class="small fw-bold text-uppercase" style="color: var(--text-muted, #64748b);">Repayment Progress</span>
                        </div>
                        <h2 class="fw-bold mb-2" style="color: var(--text-main, #0f172a);">{{ $repaymentPercent }}%</h2>
                        <div class="progress mb-2" style="height: 8px; background-color: rgba(0,0,0,0.08);">
                            <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: {{ $repaymentPercent }}%;" aria-valuenow="{{ $repaymentPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <p class="mb-0 small mt-1" style="color: var(--text-muted, #64748b);">
                        ₱{{ number_format($totalPaidActive, 2) }} paid of ₱{{ number_format($totalPayableActive, 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card user-dash-card h-100 p-3">
                <div class="card-body d-flex flex-column justify-content-between p-1">
                    <div>
                        <div class="mb-3">
                            <span class="small fw-bold text-uppercase" style="color: var(--text-muted, #64748b);">Borrowing Status</span>
                        </div>
                        @if ($isEligibleForReloan)
                            <div class="text-success fw-bold fs-6 mb-1"><i class="bi bi-check-circle-fill me-1"></i> Eligible for re-loan</div>
                            <div class="fs-6 fw-semibold" style="color: var(--text-main, #0f172a);">Up to ₱{{ number_format($availableCapacity, 2) }}</div>
                        @elseif ($hasOverdue)
                            <div class="text-danger fw-bold fs-6 mb-1"><i class="bi bi-x-circle-fill me-1"></i> Not eligible</div>
                            <p class="mb-0 small" style="color: var(--text-muted, #64748b);">Resolve your overdue payment first.</p>
                        @else
                            <div class="fw-bold fs-6 mb-1" style="color: var(--text-muted, #64748b);"><i class="bi bi-dash-circle-fill me-1"></i> Not eligible</div>
                            <p class="mb-0 small" style="color: var(--text-muted, #64748b);">You're at your current borrowing limit.</p>
                        @endif
                    </div>
                    <p class="mb-0 small mt-2 opacity-75" style="color: var(--text-muted, #64748b);"><i class="bi bi-exclamation-circle me-1"></i> Estimate only, not guaranteed.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Navigation Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('loans.index') }}" class="text-decoration-none">
                <div class="card user-dash-card h-100 p-3">
                    <div class="card-body d-flex align-items-center gap-3 p-1">
                        <div class="user-icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                            <i class="bi bi-journal-bookmark-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main, #0f172a);">My Loans</h6>
                            <p class="mb-0 small" style="color: var(--text-muted, #64748b);">View active and past loan applications.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('payment-schedule.index') }}" class="text-decoration-none">
                <div class="card user-dash-card h-100 p-3">
                    <div class="card-body d-flex align-items-center gap-3 p-1">
                        <div class="user-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                            <i class="bi bi-calendar3 fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" style="color: var(--text-main, #0f172a);">Payment Schedule</h6>
                            <p class="mb-0 small" style="color: var(--text-muted, #64748b);">See upcoming and overdue payments.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('notifications.index') }}" class="text-decoration-none">
                <div class="card user-dash-card h-100 p-3">
                    <div class="card-body d-flex align-items-center gap-3 p-1">
                        <div class="user-icon-box" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                            <i class="bi bi-bell-fill fs-5"></i>
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0" style="color: var(--text-main, #0f172a);">Notifications</h6>
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unreadCount }} new</span>
                                @endif
                            </div>
                            @if ($unreadNotifications->isEmpty())
                                <p class="mb-0 small" style="color: var(--text-muted, #64748b);">No new notifications.</p>
                            @else
                                <ul class="list-unstyled mb-0 small" style="color: var(--text-muted, #64748b);">
                                    @foreach ($unreadNotifications as $n)
                                        <li class="text-truncate">• {{ $n->title }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Payments Table --}}
    <div class="card user-dash-card p-3 mb-4">
        <div class="card-body p-1">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="user-icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7; width: 32px; height: 32px;">
                        <i class="bi bi-clock-history fs-6"></i>
                    </div>
                    <h6 class="fw-bold mb-0" style="color: var(--text-main, #0f172a);">Recent Payment Submissions</h6>
                </div>
                <a href="{{ route('payment-schedule.index') }}" class="glass-btn-action">
                    <span>View Full Schedule</span>
                </a>
            </div>
            @if ($recentPayments->isEmpty())
                <p class="small mb-0 py-2" style="color: var(--text-muted, #64748b);">You haven't submitted any payments yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table user-table align-middle mb-0">
                        <thead>
                            <tr class="small text-uppercase" style="color: var(--text-muted, #64748b);">
                                <th>Loan Details</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentPayments as $payment)
                                <tr>
                                    <td class="small fw-medium">
                                        {{ $payment->paymentSchedule->loan->loanType->name }} #{{ $payment->paymentSchedule->loan_id }}
                                        <span class="fw-normal" style="color: var(--text-muted, #64748b);">— Month {{ $payment->paymentSchedule->month_number }}</span>
                                    </td>
                                    <td class="small fw-semibold">₱{{ number_format($payment->amount, 2) }}</td>
                                    <td class="small">
                                        <span class="{{ match($payment->status) {
                                            'verified' => 'status-text-verified',
                                            'rejected' => 'status-text-rejected',
                                            default => 'status-text-pending',
                                        } }}">
                                            {{ $payment->status === 'pending' ? 'Pending' : ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="small text-end" style="color: var(--text-muted, #64748b);">{{ $payment->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Loan Calculator --}}
    <div class="card user-dash-card p-3">
        <div class="card-body p-1">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="user-icon-box" style="background: rgba(2, 132, 199, 0.15); color: #0284c7; width: 36px; height: 36px;">
                    <i class="bi bi-calculator-fill fs-5"></i>
                </div>
                <h6 class="fw-bold mb-0" style="color: var(--text-main, #0f172a);">Quick Loan Calculator</h6>
            </div>
            <p class="small mb-3" style="color: var(--text-muted, #64748b);">Estimate your monthly payment before applying. Final terms are confirmed upon approval.</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-medium" style="color: var(--text-muted, #64748b);">Loan Type</label>
                    
                    <!-- CUSTOM THEME-AWARE DROPUP DROPDOWN -->
                    <div class="custom-dropdown-container" id="loanTypeDropdown">
                        <div class="custom-dropdown-toggle">
                            <span id="selectedLoanTypeLabel">Select a loan type</span>
                            <i class="bi bi-chevron-up ms-2"></i>
                        </div>
                        <div class="custom-dropdown-menu">
                            <div class="custom-dropdown-item selected" data-value="" data-label="Select a loan type">
                                Select a loan type
                            </div>
                            @foreach ($loanTypes as $type)
                                <div class="custom-dropdown-item" 
                                    data-value="{{ $type->id }}"
                                    data-label="{{ $type->name }} ({{$type->interest_rate }}%)"
                                    data-rate="{{ $type->interest_rate }}"
                                    data-min-amount="{{ $type->min_amount }}"
                                    data-max-amount="{{ $type->max_amount }}"
                                    data-min-term="{{ $type->min_term_months }}"
                                    data-max-term="{{ $type->max_term_months }}">
                                    {{ $type->name }} ({{$type->interest_rate }}%)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-medium" style="color: var(--text-muted, #64748b);">Principal Amount (₱)</label>
                    <input type="number" id="calc-amount" class="form-control user-form-input" step="0.01" placeholder="e.g. 10000">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-medium" style="color: var(--text-muted, #64748b);">Term (months)</label>
                    <input type="number" id="calc-term" class="form-control user-form-input" placeholder="e.g. 6">
                </div>
            </div>

            <div id="calc-result" class="mt-4 d-none">
                <div class="p-3 calc-summary-box">
                    <div class="row text-center">
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="small" style="color: var(--text-muted, #64748b);">Estimated Interest</div>
                            <div class="fw-bold fs-5" id="calc-interest" style="color: var(--text-main, #0f172a);">₱0.00</div>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <div class="small" style="color: var(--text-muted, #64748b);">Total Payable</div>
                            <div class="fw-bold fs-5" id="calc-total" style="color: var(--text-main, #0f172a);">₱0.00</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small" style="color: var(--text-muted, #64748b);">Est. Monthly Payment</div>
                            <div class="fw-bold fs-4" id="calc-monthly" style="color: #0284c7;">₱0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="calc-error" class="mt-3 d-none">
                <div class="alert alert-warning mb-0 small" id="calc-error-text"></div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('loans.create') }}" class="glass-btn-primary">
                    <span>Apply for a Loan</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const dropdownContainer = document.getElementById('loanTypeDropdown');
    const dropdownToggle = dropdownContainer.querySelector('.custom-dropdown-toggle');
    const selectedLabel = document.getElementById('selectedLoanTypeLabel');
    const dropdownItems = dropdownContainer.querySelectorAll('.custom-dropdown-item');

    const calcAmount = document.getElementById('calc-amount');
    const calcTerm = document.getElementById('calc-term');
    const calcResult = document.getElementById('calc-result');
    const calcErrorBox = document.getElementById('calc-error');
    const calcErrorText = document.getElementById('calc-error-text');

    let activeOption = dropdownItems[0];

    // Toggle Dropdown
    dropdownToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownContainer.classList.toggle('show');
    });

    // Close Dropdown on outside click
    document.addEventListener('click', () => {
        dropdownContainer.classList.remove('show');
    });

    // Option Selection
    dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
            dropdownItems.forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            selectedLabel.textContent = item.dataset.label;
            activeOption = item;
            dropdownContainer.classList.remove('show');
            recalculate();
        });
    });

    function formatPeso(n) {
        return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function recalculate() {
        const amount = parseFloat(calcAmount.value);
        const term = parseInt(calcTerm.value);

        calcResult.classList.add('d-none');
        calcErrorBox.classList.add('d-none');

        if (!activeOption || !activeOption.dataset.value || isNaN(amount) || isNaN(term) || amount <= 0 || term <= 0) {
            return;
        }

        const rate = parseFloat(activeOption.dataset.rate);
        const minAmount = parseFloat(activeOption.dataset.minAmount);
        const maxAmount = parseFloat(activeOption.dataset.maxAmount);
        const minTerm = parseInt(activeOption.dataset.minTerm);
        const maxTerm = parseInt(activeOption.dataset.maxTerm);

        if (amount < minAmount || amount > maxAmount) {
            calcErrorText.textContent = `Amount must be between ₱${minAmount.toLocaleString()} and ₱${maxAmount.toLocaleString()} for this loan type.`;
            calcErrorBox.classList.remove('d-none');
            return;
        }
        if (term < minTerm || term > maxTerm) {
            calcErrorText.textContent = `Term must be between ${minTerm} and ${maxTerm} months for this loan type.`;
            calcErrorBox.classList.remove('d-none');
            return;
        }

        const interest = Math.round(amount * (rate / 100) * 100) / 100;
        const total = Math.round((amount + interest) * 100) / 100;
        const monthly = Math.round((total / term) * 100) / 100;

        document.getElementById('calc-interest').textContent = formatPeso(interest);
        document.getElementById('calc-total').textContent = formatPeso(total);
        document.getElementById('calc-monthly').textContent = formatPeso(monthly);
        calcResult.classList.remove('d-none');
    }

    [calcAmount, calcTerm].forEach(el => el.addEventListener('input', recalculate));
</script>
@endpush
@endsection