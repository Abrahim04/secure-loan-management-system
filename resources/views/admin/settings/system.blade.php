@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<!-- Adaptive Glassmorphism & Modern Vertical Settings Layout -->
<style>
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: all 0.2s ease;
    }

    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Vertical Settings Sub-Navigation Menu */
    .settings-nav-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .settings-nav-item {
        display: flex;
        flex-direction: column;
        padding: 12px 16px;
        border-radius: 12px;
        background: transparent;
        border: 1px solid transparent;
        color: var(--text-muted, #94a3b8);
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .settings-nav-item:hover {
        background: rgba(100, 116, 139, 0.1);
        color: var(--text-main, #f8fafc);
    }

    .settings-nav-item.active {
        background: rgba(2, 132, 199, 0.12);
        border-color: rgba(2, 132, 199, 0.3);
        color: #0284c7;
    }

    .settings-nav-item .nav-title {
        font-weight: 600;
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .settings-nav-item.active .nav-title {
        color: #38bdf8;
    }

    .settings-nav-item .nav-desc {
        font-size: 0.75rem;
        opacity: 0.75;
        margin-top: 2px;
    }

    .form-control-glass {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control-glass:focus {
        background-color: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        outline: none;
    }

    .btn-pill-primary {
        background: #0284c7;
        border: 1px solid #0369a1;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-pill-primary:hover {
        background: #0369a1;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-pill-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }

    .btn-pill-danger:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
    }

    .glass-info-box {
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid rgba(2, 132, 199, 0.2);
        border-radius: 12px;
        padding: 14px 18px;
        color: var(--text-main, #f8fafc);
        font-size: 0.85rem;
    }

    .glass-info-box code {
        background: rgba(255, 255, 255, 0.1);
        color: #38bdf8;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    /* Seamless Glassmorphism Table Styling */
    .glass-table {
        background: transparent !important;
        color: var(--text-main, #f8fafc) !important;
    }

    .glass-table th,
    .glass-table td {
        background: transparent !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: var(--text-main, #f8fafc) !important;
    }

    .glass-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .glass-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
</style>

<div class="container-fluid px-0">
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Settings</h2>
        <p class="text-muted small mb-0">Select a category from the menu to manage preferences and configurations.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KALIWA: Settings Navigation Side Menu --}}
        <div class="col-lg-3">
            <div class="dash-card p-3">
                <span class="text-muted small text-uppercase fw-bold d-block px-2 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Categories</span>
                
                <div class="settings-nav-group">
                    <button type="button" class="settings-nav-item active" onclick="showSettingsCategory('accountCat', this)">
                        <span class="nav-title">Account & Security</span>
                        <span class="nav-desc">Personal details & password</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="showSettingsCategory('authCat', this)">
                        <span class="nav-title">Authentication (MFA/OTP)</span>
                        <span class="nav-desc">OTP duration & attempt limits</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="showSettingsCategory('maintenanceCat', this)">
                        <span class="nav-title">System Operational State</span>
                        <span class="nav-desc">Operational, maintenance & shutdown</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="showSettingsCategory('loanDefaultsCat', this)">
                        <span class="nav-title">Loan Defaults</span>
                        <span class="nav-desc">Interest rates & max loan terms</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="showSettingsCategory('alertsCat', this)">
                        <span class="nav-title">Notification & Alerts</span>
                        <span class="nav-desc">Email alert triggers for admin</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="showSettingsCategory('penaltyCat', this)">
                        <span class="nav-title">Penalty Policy</span>
                        <span class="nav-desc">Overdue rates & grace periods</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- KANAN: Selected Setting Category Content --}}
        <div class="col-lg-9">
            {{-- CATEGORY 1: Account & Security --}}
            <div id="accountCat" class="settings-content-panel">
                <div class="dash-card p-4 mb-4">
                    <h5 class="fw-bold text-main mb-1">Personal Information</h5>
                    <p class="text-muted small mb-4">Update your basic name, email, and contact number.</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-glass" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Email Address</label>
                                <input type="email" class="form-control form-control-glass" value="{{ auth()->user()->email }}" disabled readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-glass" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. 09123456789">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Address</label>
                                <textarea name="address" class="form-control form-control-glass" rows="2" placeholder="Enter complete address">{{ old('address', auth()->user()->address) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-pill-primary">Save Personal Details</button>
                        </div>
                    </form>
                </div>

                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Account Security</h5>
                    <p class="text-muted small mb-4">Update your password to keep your admin account safe.</p>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Current Password</label>
                                <input type="password" name="current_password" class="form-control form-control-glass" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">New Password</label>
                                <input type="password" name="password" class="form-control form-control-glass" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-glass" required>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-pill-danger">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 2: Authentication (MFA/OTP) --}}
            <div id="authCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Authentication & OTP Parameters</h5>
                    <p class="text-muted small mb-4">Configure email OTP duration and attempt restrictions.</p>

                    <div class="glass-info-box mb-4">
                        These parameters control the MFA flow for user logins, verification codes, and password resets.
                    </div>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="maintenance_mode" value="{{ $settings['maintenance_mode'] }}">
                        @if($settings['admin_notify_loan_application'] == '1') <input type="hidden" name="admin_notify_loan_application" value="1"> @endif
                        @if($settings['admin_notify_large_repayment'] == '1') <input type="hidden" name="admin_notify_large_repayment" value="1"> @endif

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">OTP Expiry (minutes)</label>
                                <input type="number" name="otp_expiry_minutes" class="form-control form-control-glass" value="{{ old('otp_expiry_minutes', $settings['otp_expiry_minutes']) }}" min="1" max="60" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Max OTP Attempts</label>
                                <input type="number" name="otp_max_attempts" class="form-control form-control-glass" value="{{ old('otp_max_attempts', $settings['otp_max_attempts']) }}" min="1" max="10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Free Resend Attempts</label>
                                <input type="number" name="otp_free_resend_attempts" class="form-control form-control-glass" value="{{ old('otp_free_resend_attempts', $settings['otp_free_resend_attempts']) }}" min="0" max="20" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Resend Cooldown (seconds)</label>
                                <input type="number" name="otp_resend_cooldown_seconds" class="form-control form-control-glass" value="{{ old('otp_resend_cooldown_seconds', $settings['otp_resend_cooldown_seconds']) }}" min="10" max="600" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-pill-primary">Save OTP Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 3: System Operational State (Maintenance & Shutdown) --}}
            <div id="maintenanceCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">System Operational State</h5>
                    <p class="text-muted small mb-4">Control system access, schedule maintenance, or execute an emergency system shutdown for borrowers.</p>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="otp_expiry_minutes" value="{{ $settings['otp_expiry_minutes'] }}">
                        <input type="hidden" name="otp_max_attempts" value="{{ $settings['otp_max_attempts'] }}">
                        <input type="hidden" name="otp_resend_cooldown_seconds" value="{{ $settings['otp_resend_cooldown_seconds'] }}">
                        <input type="hidden" name="otp_free_resend_attempts" value="{{ $settings['otp_free_resend_attempts'] }}">
                        @if($settings['admin_notify_loan_application'] == '1') <input type="hidden" name="admin_notify_loan_application" value="1"> @endif
                        @if($settings['admin_notify_large_repayment'] == '1') <input type="hidden" name="admin_notify_large_repayment" value="1"> @endif

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-muted mb-2">Select Operational State</label>
                            <select name="maintenance_mode" class="form-control form-control-glass">
                                <option value="0" {{ $settings['maintenance_mode'] == '0' ? 'selected' : '' }}>🟢 0 - Operational (Normal Operation)</option>
                                <option value="1" {{ $settings['maintenance_mode'] == '1' ? 'selected' : '' }}>🟡 1 - Under Maintenance (App Closed for Borrowers)</option>
                                <option value="2" {{ $settings['maintenance_mode'] == '2' ? 'selected' : '' }}>🔴 2 - Emergency Shutdown / System Offline (Service Halted)</option>
                            </select>
                            <div class="form-text text-muted small mt-2">
                                <strong>Operational:</strong> Fully accessible to everyone.<br>
                                <strong>Maintenance:</strong> Borrowers are temporarily restricted with a maintenance notice.<br>
                                <strong>Shutdown:</strong> Complete offline state for borrowers (Flowchart End state) until the admin restarts/switches it back.
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-pill-primary">Save Operational State</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 4: Loan Defaults (Loan Types Specific) --}}
            <div id="loanDefaultsCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Loan Product Specific Configurations</h5>
                    <p class="text-muted small mb-4">Adjust baseline interest rates, max allowable terms, and amount ranges for each specific loan type.</p>

                    <form method="POST" action="{{ route('admin.settings.loantypes.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive mb-4">
                            <table class="table glass-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="small text-muted text-uppercase" style="width: 25%;">Loan Type</th>
                                        <th class="small text-muted text-uppercase" style="width: 15%;">Interest Rate (%)</th>
                                        <th class="small text-muted text-uppercase" style="width: 15%;">Max Term (mos)</th>
                                        <th class="small text-muted text-uppercase" style="width: 45%;">Amount Range (₱)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loanTypes as $type)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-main d-block">{{ $type->name }}</span>
                                                <small class="text-muted">{{ Str::limit($type->description, 35) }}</small>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="loan_types[{{ $type->id }}][interest_rate]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.interest_rate', $type->interest_rate) }}" min="0" required style="max-width: 90px;">
                                            </td>
                                            <td>
                                                <input type="number" name="loan_types[{{ $type->id }}][max_term_months]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.max_term_months', $type->max_term_months) }}" min="1" required style="max-width: 90px;">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="number" step="0.01" name="loan_types[{{ $type->id }}][min_amount]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.min_amount', $type->min_amount) }}" min="0" required placeholder="Min ₱" style="max-width: 110px;">
                                                    <span class="text-muted">-</span>
                                                    <input type="number" step="0.01" name="loan_types[{{ $type->id }}][max_amount]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.max_amount', $type->max_amount) }}" min="0" required placeholder="Max ₱" style="max-width: 120px;">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-pill-primary">Save Loan Type Configurations</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 5: Notification & Alerts --}}
            <div id="alertsCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Admin Alert Preferences</h5>
                    <p class="text-muted small mb-4">Choose which events trigger real-time system and email alerts.</p>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="otp_expiry_minutes" value="{{ $settings['otp_expiry_minutes'] }}">
                        <input type="hidden" name="otp_max_attempts" value="{{ $settings['otp_max_attempts'] }}">
                        <input type="hidden" name="otp_resend_cooldown_seconds" value="{{ $settings['otp_resend_cooldown_seconds'] }}">
                        <input type="hidden" name="otp_free_resend_attempts" value="{{ $settings['otp_free_resend_attempts'] }}">
                        <input type="hidden" name="maintenance_mode" value="{{ $settings['maintenance_mode'] }}">

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="admin_notify_loan_application" name="admin_notify_loan_application" value="1" {{ $settings['admin_notify_loan_application'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-main ms-2" for="admin_notify_loan_application">New Loan Application Alerts</label>
                            </div>
                            <span class="text-muted small d-block ms-4">Receive notification whenever a borrower submits a new loan request.</span>
                        </div>

                        <div class="p-3 rounded-3 mb-4" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="admin_notify_large_repayment" name="admin_notify_large_repayment" value="1" {{ $settings['admin_notify_large_repayment'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-main ms-2" for="admin_notify_large_repayment">Large Repayment Notifications</label>
                            </div>
                            <span class="text-muted small d-block ms-4">Get alerted when a payment above ₱10,000 is logged into the system.</span>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-pill-primary">Save Alert Preferences</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 6: Penalty Policy --}}
            <div id="penaltyCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Penalty Policy</h5>
                    <p class="text-muted small mb-4">Configure late charges and maximum penalty caps.</p>

                    <div class="glass-info-box mb-4">
                        These values control the automatic overdue detection command (<code>php artisan loans:apply-penalties</code>).
                    </div>

                    <form method="POST" action="{{ route('admin.settings.penalties.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Penalty per Day (₱)</label>
                                <input type="number" step="0.01" name="penalty_per_day" class="form-control form-control-glass" value="{{ old('penalty_per_day', $penaltySettings['penalty_per_day']) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Grace Period (days)</label>
                                <input type="number" name="grace_period_days" class="form-control form-control-glass" value="{{ old('grace_period_days', $penaltySettings['grace_period_days']) }}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Maximum Penalty Cap (₱)</label>
                                <input type="number" step="0.01" name="max_penalty" class="form-control form-control-glass" value="{{ old('max_penalty', $penaltySettings['max_penalty']) }}" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-pill-primary">Save Penalty Policy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSettingsCategory(catId, btn) {
        document.querySelectorAll('.settings-content-panel').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.settings-nav-item').forEach(el => el.classList.remove('active'));
        document.getElementById(catId).style.display = 'block';
        btn.classList.add('active');
    }
</script>
@endsection