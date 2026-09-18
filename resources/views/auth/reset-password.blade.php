@extends('layouts.app')

@section('title', 'Set New Password')

@push('styles')
<style>
    /* Fullscreen Background Layout */
    .auth-page-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(rgba(15, 23, 42, 0.70), rgba(15, 23, 42, 0.70)), 
                    url("{{ asset('images/auth-bg.png') }}") no-repeat center center / cover;
    }

    .auth-container {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    /* Glass Card Styling */
    .auth-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }

    /* Form Inputs Base Style */
    .form-control-dark {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
        transition: all 0.2s ease-in-out;
        background-image: none !important; /* Alis ang default Bootstrap validation check icons */
        padding-right: 2.75rem !important;
    }

    /* Override Autofill Background at Text Color */
    .form-control-dark:-webkit-autofill,
    .form-control-dark:-webkit-autofill:hover, 
    .form-control-dark:-webkit-autofill:focus, 
    .form-control-dark:-webkit-autofill:active {
        -webkit-text-fill-color: #f8fafc !important;
        -webkit-box-shadow: 0 0 0px 1000px #1e293b inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Standard Focus: Cyan/Blue outline lang habang nagta-type */
    .form-control-dark:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.2) !important;
        background-color: #0f172a !important;
    }

    /* Pag FIT/VALID na sa lahat ng requirements: EMERALD GREEN GLOW */
    .form-control-dark.input-valid,
    .form-control-dark.input-valid:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.3) !important;
    }

    /* Pag may kulang na requirement o hindi match: RED GLOW */
    .form-control-dark.input-invalid,
    .form-control-dark.input-invalid:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25) !important;
    }

    .form-control-dark::placeholder {
        color: #64748b !important;
    }

    /* Password Toggle Icon */
    .password-toggle-btn {
        transition: color 0.2s ease;
        z-index: 5;
    }
    .password-toggle-btn:hover {
        color: #10b981 !important;
    }

    /* Emerald Button Hover Effects */
    .btn-emerald {
        background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .btn-emerald:hover {
        opacity: 0.95;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
    }

    .btn-emerald:active {
        transform: translateY(1px);
        box-shadow: none !important;
    }

    /* Professional Back to Login Button Styling */
    .btn-outline-glass {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #94a3b8 !important;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.25s ease-in-out;
        text-decoration: none;
    }

    .btn-outline-glass:hover {
        background: rgba(16, 185, 129, 0.08);
        border-color: rgba(16, 185, 129, 0.4);
        color: #10b981 !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .btn-outline-glass .back-icon {
        transition: transform 0.25s ease-in-out;
    }

    .btn-outline-glass:hover .back-icon {
        transform: translateX(-4px);
    }

    /* Checklist Item Colors */
    #reset-password-checklist li {
        font-size: 0.825rem;
        transition: color 0.2s ease;
    }
    
    #reset-password-checklist li.text-success {
        color: #34d399 !important;
    }

    #reset-password-checklist li.text-danger {
        color: #f87171 !important;
    }

    #reset-password-checklist li.text-muted {
        color: #94a3b8 !important;
    }

    /* Entrance Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
</style>
@endpush

@section('content')
<div class="auth-page-wrapper">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-6 col-lg-4 animate-up">
                <div class="card auth-card text-white">
                    <div class="card-body p-4 p-md-5">

                        <!-- Logo Header -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" style="height: 50px; width: auto;">
                        </div>

                        <h4 class="fw-bold mb-1 text-center">Set a New Password</h4>
                        <p class="text-secondary small mb-4 text-center">Choose a new password for your account.</p>

                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger text-start small border-0 mb-4" style="background-color: rgba(239, 68, 68, 0.15); color: #f87171;">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.reset.update') }}">
                            @csrf

                            <!-- New Password Field -->
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-semibold">New Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="reset-password" class="form-control form-control-lg form-control-dark" placeholder="Enter new password" required autofocus autocomplete="new-password">
                                    <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent text-secondary" data-target="reset-password" aria-label="Show password"></button>
                                </div>

                                <!-- Password Validation Rules Checklist -->
                                <div class="mt-3 p-3 rounded" style="background-color: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255, 255, 255, 0.05);">
                                    <ul class="list-unstyled mb-0" id="reset-password-checklist">
                                        <li data-rule="length" class="text-muted"><span class="rule-icon me-2">○</span> At least 8 characters</li>
                                        <li data-rule="upper" class="text-muted"><span class="rule-icon me-2">○</span> One uppercase letter (A-Z)</li>
                                        <li data-rule="lower" class="text-muted"><span class="rule-icon me-2">○</span> One lowercase letter (a-z)</li>
                                        <li data-rule="number" class="text-muted"><span class="rule-icon me-2">○</span> One number (0-9)</li>
                                        <li data-rule="symbol" class="text-muted"><span class="rule-icon me-2">○</span> One special character (!@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label class="form-label text-secondary small fw-semibold">Confirm New Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="reset-password-confirm" class="form-control form-control-lg form-control-dark" placeholder="Confirm new password" required autocomplete="new-password">
                                    <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent text-secondary" data-target="reset-password-confirm" aria-label="Show password"></button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-emerald btn-lg w-100 shadow-sm">
                                Update Password
                            </button>

                            <!-- Professional Back to Login Button -->
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-glass w-100 d-inline-flex align-items-center justify-content-center gap-2 py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="back-icon">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    <span>Back to Login</span>
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const EYE_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    const EYE_OFF_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

    // Eye toggle button logic
    document.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
        btn.innerHTML = EYE_SVG;
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            this.innerHTML = showing ? EYE_SVG : EYE_OFF_SVG;
        });
    });

    // Password Validation & Glow Indicator Logic
    function attachPasswordChecklist() {
        const passInput = document.getElementById('reset-password');
        const confirmInput = document.getElementById('reset-password-confirm');
        const checklist = document.getElementById('reset-password-checklist');
        
        if (!passInput || !checklist) return;

        const rules = {
            length: v => v.length >= 8,
            upper: v => /[A-Z]/.test(v),
            lower: v => /[a-z]/.test(v),
            number: v => /[0-9]/.test(v),
            symbol: v => /[^A-Za-z0-9]/.test(v),
        };

        function validatePassword() {
            const value = passInput.value;
            let allValid = true;

            Object.keys(rules).forEach(function (key) {
                const li = checklist.querySelector('[data-rule="' + key + '"]');
                if (!li) return;
                const valid = rules[key](value);
                if (!valid) allValid = false;

                li.classList.toggle('text-success', valid);
                li.classList.toggle('text-danger', !valid && value.length > 0);
                li.classList.toggle('text-muted', value.length === 0);
                li.querySelector('.rule-icon').textContent = valid ? '✓' : '○';
            });

            // Mag-e-green LAMANG kung pasok sa LAHAT ng 5 rules
            if (value.length > 0) {
                if (allValid) {
                    passInput.classList.add('input-valid');
                    passInput.classList.remove('input-invalid');
                } else {
                    passInput.classList.add('input-invalid');
                    passInput.classList.remove('input-valid');
                }
            } else {
                passInput.classList.remove('input-valid', 'input-invalid');
            }

            validateConfirmPassword(allValid);
        }

        function validateConfirmPassword(isMainPassValid) {
            if (!confirmInput) return;
            const confirmVal = confirmInput.value;
            const passVal = passInput.value;

            if (confirmVal.length > 0) {
                // Mag-e-green LAMANG kung katugma ng password AT valid ang main password
                if (confirmVal === passVal && isMainPassValid) {
                    confirmInput.classList.add('input-valid');
                    confirmInput.classList.remove('input-invalid');
                } else {
                    confirmInput.classList.add('input-invalid');
                    confirmInput.classList.remove('input-valid');
                }
            } else {
                confirmInput.classList.remove('input-valid', 'input-invalid');
            }
        }

        passInput.addEventListener('input', validatePassword);
        if (confirmInput) {
            confirmInput.addEventListener('input', function() {
                const passVal = passInput.value;
                const isMainValid = Object.keys(rules).every(k => rules[k](passVal));
                validateConfirmPassword(isMainValid);
            });
        }
    }

    attachPasswordChecklist();
</script>
@endpush
@endsection