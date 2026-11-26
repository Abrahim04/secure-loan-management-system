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

    /* Alisin ang default Bootstrap validation icon para hindi sumabay sa eye icon */
    .form-control-glass.is-invalid {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.05) !important;
        background-image: none !important;
        padding-right: 2.8rem !important;
    }

    /* Eye Toggle Button for Glass Inputs */
    .eye-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted, #94a3b8);
        cursor: pointer;
        z-index: 5;
    }

    .eye-toggle:hover {
        color: var(--text-main, #f8fafc);
    }

    /* Glass Primary Action Button */
    .btn-glass-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-glass-primary:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
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

    /* Auto-Dismissing Custom Glass Alerts */
    .glass-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .glass-alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }
    .glass-alert.fade-out {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<div class="container-fluid px-0">
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Account Settings</h2>
        <p class="text-muted small mb-0">Manage your profile, security preferences, and account configurations.</p>
    </div>

    {{-- Auto-Dismissing Flash Alert --}}
    @if (session('status'))
        <div id="autoDismissAlert" class="glass-alert glass-alert-success mb-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="row g-4">
        {{-- KALIWA: Settings Navigation Side Menu --}}
        <div class="col-lg-3">
            <div class="dash-card p-3">
                <span class="text-muted small text-uppercase fw-bold d-block px-2 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Categories</span>
                
                <div class="settings-nav-group">
                    <button type="button" class="settings-nav-item active" onclick="switchTab('profileCat', this)">
                        <span class="nav-title">Profile Information</span>
                        <span class="nav-desc">Personal details & contact info</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchTab('securityCat', this)">
                        <span class="nav-title">Password & Security</span>
                        <span class="nav-desc">Change password & account protection</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchTab('notificationsCat', this)">
                        <span class="nav-title">Notification Preferences</span>
                        <span class="nav-desc">Email reminders & loan alerts</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- KANAN: Selected Setting Category Content --}}
        <div class="col-lg-9">
            {{-- CATEGORY 1: Profile Information --}}
            <div id="profileCat" class="settings-content-panel">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Profile Information</h5>
                    <p class="text-muted small mb-4">Update your account's profile details and mobile contact info.</p>

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
                                <input type="text" name="phone" class="form-control form-control-glass" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="e.g. 09123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Address</label>
                                <input type="text" name="address" class="form-control form-control-glass" value="{{ old('address', auth()->user()->address ?? '') }}" placeholder="Enter complete address">
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-glass-primary">Save Personal Details</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 2: Password & Security --}}
            <div id="securityCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Account Security</h5>
                    <p class="text-muted small mb-4">Ensure your account is using a strong password with letters, numbers, and symbols.</p>

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            {{-- Current Password --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Current Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="current_password" id="user_current_password" class="form-control form-control-glass @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('user_current_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">New Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="userNewPassword" class="form-control form-control-glass @error('password') is-invalid @enderror" placeholder="Create a strong password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('userNewPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Confirm New Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="userConfirmPassword" class="form-control form-control-glass" placeholder="Confirm your password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('userConfirmPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Live Password Requirements Box --}}
                        <div class="p-3 my-3 rounded-3 transition-all" id="userReqBox" style="background-color: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="d-flex align-items-center gap-2 mb-2 text-secondary fw-semibold" style="font-size: 0.82rem;">
                                <i class="bi bi-shield text-secondary" id="userShieldHeaderIcon" style="font-size: 1rem;"></i>
                                <span id="userShieldHeaderText">Password Requirements:</span>
                            </div>

                            <div class="d-flex flex-column gap-1" style="font-size: 0.82rem;">
                                <div id="userRuleLength" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="userIconLength" style="font-size: 0.75rem;"></i> At least 8 characters
                                </div>
                                <div id="userRuleUpper" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="userIconUpper" style="font-size: 0.75rem;"></i> One uppercase letter (A-Z)
                                </div>
                                <div id="userRuleLower" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="userIconLower" style="font-size: 0.75rem;"></i> One lowercase letter (a-z)
                                </div>
                                <div id="userRuleNumber" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="userIconNumber" style="font-size: 0.75rem;"></i> One number (0-9)
                                </div>
                                <div id="userRuleSpecial" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="userIconSpecial" style="font-size: 0.75rem;"></i> One special character (!@#$%^&*)
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-pill-danger">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 3: Notification Preferences --}}
            <div id="notificationsCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Notification Preferences</h5>
                    <p class="text-muted small mb-4">Manage how and when you receive email alerts about your loan updates and payment schedules.</p>

                    <div class="glass-info-box mb-4">
                        Notifications for loan approvals, disbursement updates, and payment reminders will be sent to <strong>{{ auth()->user()->email }}</strong>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="notify_loan_updates" checked>
                            <label class="form-check-label fw-semibold text-main ms-2" for="notify_loan_updates">Loan Status Updates</label>
                        </div>
                        <span class="text-muted small d-block ms-4">Receive real-time email notifications when your loan is approved, released, or updated.</span>
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" class="form-check-input" id="notify_payment_due" checked>
                            <label class="form-check-label fw-semibold text-main ms-2" for="notify_payment_due">Payment Reminders</label>
                        </div>
                        <span class="text-muted small d-block ms-4">Receive email alerts 3 days before your upcoming loan repayment due date.</span>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn-glass-primary">Save Notification Preferences</button>
                    </div>
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
        if (btn) btn.classList.add('active');
    }

    function switchTab(catId, btn) {
        showSettingsCategory(catId, btn);
        localStorage.setItem('user_settings_active_tab', catId);
    }

    // Eye Toggle Script
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Live Password Requirement Checker Script para sa User
    const userNewPassInput = document.getElementById('userNewPassword');
    if (userNewPassInput) {
        userNewPassInput.addEventListener('input', function () {
            const val = this.value;
            function updateUserRule(ruleId, iconId, isValid) {
                const ruleEl = document.getElementById(ruleId);
                const iconEl = document.getElementById(iconId);
                if (isValid) {
                    ruleEl.className = 'text-success fw-medium transition-all d-flex align-items-center gap-2';
                    iconEl.className = 'bi bi-check-circle-fill text-success';
                } else {
                    ruleEl.className = 'text-secondary transition-all d-flex align-items-center gap-2';
                    iconEl.className = 'bi bi-circle text-secondary';
                }
            }
            const hasLength  = val.length >= 8;
            const hasUpper   = /[A-Z]/.test(val);
            const hasLower   = /[a-z]/.test(val);
            const hasNumber  = /[0-9]/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);

            updateUserRule('userRuleLength',  'userIconLength',  hasLength);
            updateUserRule('userRuleUpper',   'userIconUpper',   hasUpper);
            updateUserRule('userRuleLower',   'userIconLower',   hasLower);
            updateUserRule('userRuleNumber',  'userIconNumber',  hasNumber);
            updateUserRule('userRuleSpecial', 'userIconSpecial', hasSpecial);

            const allValid = hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
            const reqBox = document.getElementById('userReqBox');
            const shieldIcon = document.getElementById('userShieldHeaderIcon');

            if (allValid) {
                reqBox.style.backgroundColor = 'rgba(16, 185, 129, 0.08)';
                reqBox.style.borderColor = 'rgba(16, 185, 129, 0.2)';
                shieldIcon.className = 'bi bi-shield-check text-success';
            } else {
                reqBox.style.backgroundColor = 'rgba(100, 116, 139, 0.05)';
                reqBox.style.borderColor = 'var(--dash-card-border, rgba(255, 255, 255, 0.08))';
                shieldIcon.className = 'bi bi-shield text-secondary';
            }
        });
    }

    // Auto-Dismiss Alert & Tab Persistence / Error Handling
    document.addEventListener('DOMContentLoaded', function() {
        const alertBox = document.getElementById('autoDismissAlert');
        if (alertBox) {
            setTimeout(function() {
                alertBox.classList.add('fade-out');
                setTimeout(function() {
                    alertBox.remove();
                }, 500);
            }, 3500);
        }

        // Suriin kung may validation errors para awtomatikong lumipat sa tamang tab
        @if ($errors->any())
            @if ($errors->has('current_password') || $errors->has('password'))
                const secBtn = document.querySelector('[onclick*="securityCat"]');
                switchTab('securityCat', secBtn);
            @elseif ($errors->has('name') || $errors->has('phone') || $errors->has('address'))
                const profBtn = document.querySelector('[onclick*="profileCat"]');
                switchTab('profileCat', profBtn);
            @endif
        @else
            // Kung walang error, gamitin ang huling binuksang tab
            const savedTab = localStorage.getItem('user_settings_active_tab');
            if (savedTab && document.getElementById(savedTab)) {
                const btn = document.querySelector(`[onclick*="${savedTab}"]`);
                if (btn) {
                    showSettingsCategory(savedTab, btn);
                }
            }
        @endif
    });
</script>
@endsection