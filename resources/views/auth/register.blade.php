<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account - PautangPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-emerald: #10b981;
            --gradient-btn: linear-gradient(90deg, #0284c7 0%, #10b981 100%);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        .auth-container {
            min-height: 100vh;
        }

        .form-section {
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 3.5rem;
            min-height: 100vh;
            overflow-y: auto;
        }

        .brand-logo-img {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        .form-input-container {
            position: relative;
        }

        .form-input-container i.input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            z-index: 5;
        }

        .custom-input {
            padding-left: 2.6rem !important;
            padding-right: 2.6rem !important;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            height: 46px;
            font-size: 0.95rem;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        }

        .eye-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            z-index: 5;
        }

        .btn-submit {
            background: var(--gradient-btn);
            color: white;
            border: none;
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .visual-section {
            background: linear-gradient(135deg, rgba(11, 27, 60, 0.88), rgba(15, 23, 42, 0.92)), 
                        url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4.5rem 4rem;
            min-height: 100vh;
        }

        .feature-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.25);
            border: 1px solid rgba(52, 211, 153, 0.4);
            color: #34d399;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 0.75rem;
        }

        .validation-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 0.6rem 0.8rem;
            font-size: 0.78rem;
            color: #166534;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 auth-container">
        <!-- Left Side Form -->
        <div class="col-lg-6 form-section">
            <!-- Header Logo & Tagline -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" class="brand-logo-img">
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">
                            Pautang<span style="color: var(--primary-emerald);">Pro</span>
                        </div>
                        <div class="text-muted small mt-1" style="font-size: 0.78rem;">Your Loan, Our Priority</div>
                    </div>
                </div>
                <a href="/" class="text-secondary text-decoration-none small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Home
                </a>
            </div>

            <!-- Register Form Area -->
            <div class="my-auto py-3 mx-auto" style="max-width: 410px; width: 100%;">
                <div class="animate-up delay-10">
                    <h2 class="fw-bold text-dark mb-1 fs-2">Create an <span style="color: var(--primary-emerald);">Account</span></h2>
                    <p class="text-muted small mb-3">Join PautangPro in a few quick steps</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="animate-up delay-10">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary mb-1">Full Name *</label>
                        <div class="form-input-container">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="name" class="form-control custom-input" placeholder="Enter your full name" required autofocus>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary mb-1">Email *</label>
                        <div class="form-input-container">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary mb-1">Password *</label>
                        <div class="form-input-container">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" id="regPassword" class="form-control custom-input" placeholder="Create a strong password" required>
                            <button type="button" class="eye-toggle" onclick="togglePassword('regPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Requirements Box -->
<!-- Password Requirements Box -->
<div class="p-3 mb-3 rounded-3" id="reqBox" style="background-color: #f0fdf4; border: 1px solid #dcfce7;">
    <!-- Header with Shield Icon -->
    <div class="d-flex align-items-center gap-2 mb-2 text-secondary fw-semibold" style="font-size: 0.82rem;">
        <i class="bi bi-shield-check text-success" style="font-size: 1rem;"></i>
        <span>Password Requirements:</span>
    </div>

    <!-- Requirements List -->
    <div class="d-flex flex-column gap-1" style="font-size: 0.82rem;">
        <div id="ruleLength" class="text-secondary transition-all d-flex align-items-center gap-2">
            <i class="bi bi-circle" id="iconLength" style="font-size: 0.75rem;"></i> At least 8 characters
        </div>
        <div id="ruleUpper" class="text-secondary transition-all d-flex align-items-center gap-2">
            <i class="bi bi-circle" id="iconUpper" style="font-size: 0.75rem;"></i> One uppercase letter (A-Z)
        </div>
        <div id="ruleLower" class="text-secondary transition-all d-flex align-items-center gap-2">
            <i class="bi bi-circle" id="iconLower" style="font-size: 0.75rem;"></i> One lowercase letter (a-z)
        </div>
        <div id="ruleNumber" class="text-secondary transition-all d-flex align-items-center gap-2">
            <i class="bi bi-circle" id="iconNumber" style="font-size: 0.75rem;"></i> One number (0-9)
        </div>
        <div id="ruleSpecial" class="text-secondary transition-all d-flex align-items-center gap-2">
            <i class="bi bi-circle" id="iconSpecial" style="font-size: 0.75rem;"></i> One special character (!@#$%^&*)
        </div>
    </div>
</div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary mb-1">Confirm Password *</label>
                        <div class="form-input-container">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" name="password_confirmation" id="regConfirmPassword" class="form-control custom-input" placeholder="Confirm your password" required>
                            <button type="button" class="eye-toggle" onclick="togglePassword('regConfirmPassword', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 mb-3">
                        <i class="bi bi-person-plus me-2"></i> Sign Up
                    </button>

                    <div class="text-center text-muted small">
                        Already have an account? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #0284c7;">Log In</a>
                    </div>
                </form>
            </div>

            <div class="text-muted small">
                &copy; {{ date('Y') }} PautangPro. All rights reserved.
            </div>
        </div>

        <!-- Right Side Visual Showcase -->
        <div class="col-lg-6 visual-section d-none d-lg-flex">
            <div></div>

            <div class="animate-up delay-10">
                <h1 class="display-4 fw-bold mb-3 text-white">
                    Welcome to <br><span style="color: #34d399;">PautangPro!</span>
                </h1>
                <p class="text-light opacity-75 fs-5 lh-base" style="max-width: 460px;">
                    Manage your loan applications, track payment schedules, and view account balances under our priority guarantee.
                </p>
            </div>

            <div class="row g-2 animate-up delay-10 pt-4">
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-lightning-charge"></i></div>
                    <div class="fw-semibold small text-white">Easy Apply</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Quick submission process.</div>
                </div>
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-bar-chart-line"></i></div>
                    <div class="fw-semibold small text-white">Track Payments</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Real-time ledger updates.</div>
                </div>
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-shield-lock"></i></div>
                    <div class="fw-semibold small text-white">Secure Data</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Encrypted & protected.</div>
                </div>
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-headset"></i></div>
                    <div class="fw-semibold small text-white">Priority Support</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">We're here for you.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function para sa Password Eye Toggle
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

    // Real-time Dynamic Password Validation
    document.getElementById('regPassword').addEventListener('input', function () {
    const val = this.value;

    function updateRule(ruleId, iconId, isValid) {
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

    updateRule('ruleLength',  'iconLength',  hasLength);
    updateRule('ruleUpper',   'iconUpper',   hasUpper);
    updateRule('ruleLower',   'iconLower',   hasLower);
    updateRule('ruleNumber',  'iconNumber',  hasNumber);
    updateRule('ruleSpecial', 'iconSpecial', hasSpecial);
});
</script>
</body>
</html>