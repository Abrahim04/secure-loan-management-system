<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - PautangPro</title>
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
            height: 48px;
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

        /* Professional Glass Button */
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

/* Fix sa Autofill Background Color */
.form-control-dark:-webkit-autofill,
.form-control-dark:-webkit-autofill:hover, 
.form-control-dark:-webkit-autofill:focus, 
.form-control-dark:-webkit-autofill:active {
    -webkit-text-fill-color: #f8fafc !important;
    -webkit-box-shadow: 0 0 0px 1000px #1e293b inset !important;
    transition: background-color 5000s ease-in-out 0s;
}

.btn-back-home {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    color: #475569;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.25s ease-in-out;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.btn-back-home:hover {
    background-color: #e6f4ea;
    border-color: #10b981;
    color: #059669;
    transform: translateX(-3px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

       .custom-input.is-invalid {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}
.no-animation {
    animation: none !important;
    opacity: 1 !important;
    transform: none !important;
}
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 auth-container">
        <!-- Left Side Form -->
        <div class="col-lg-6 form-section">
            <!-- Header Logo & Tagline -->
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" class="brand-logo-img">
                    <div>
                        <div class="fw-bold fs-4 lh-1 text-dark">
                            Pautang<span style="color: var(--primary-emerald);">Pro</span>
                        </div>
                        <div class="text-muted small mt-1" style="font-size: 0.78rem;">Your Loan, Our Priority</div>
                    </div>
                </div>
                <a href="{{ url('/') }}" class="btn-back-home">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    <span>Back to Home</span>
</a>
            </div>

            <!-- Login Form Area -->
            <div class="my-auto py-4 mx-auto" style="max-width: 400px; width: 100%;">
                <div class="{{ $errors->any() ? 'no-animation' : 'animate-up delay-10' }}">
                    <h2 class="fw-bold text-dark mb-1 fs-2">Log In</h2>
                    <p class="text-muted small mb-4">Sign in to access your PautangPro account</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="{{ $errors->any() ? 'no-animation' : 'animate-up delay-10' }}">
                    @csrf
                    
                    <!-- Email Field -->
<div class="mb-3">
    <label class="form-label small fw-semibold text-secondary mb-1">Email *</label>
    <div class="form-input-container">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" 
               name="email" 
               value="{{ old('email') }}" 
               class="form-control custom-input @error('email') is-invalid @enderror"
               placeholder="Enter your email address" 
               required 
               autofocus>
    </div>
    @error('email')
        <div class="text-danger small mt-1" style="font-size: 0.8rem;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>

<!-- Password Field -->
<div class="mb-2">
    <label class="form-label small fw-semibold text-secondary mb-1">Password *</label>
    <div class="form-input-container">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" 
               name="password" 
               id="loginPassword" 
               class="form-control custom-input @error('password') is-invalid @enderror"
               placeholder="Enter your password" 
               required>
        <button type="button" class="eye-toggle" onclick="togglePassword('loginPassword', this)">
            <i class="bi bi-eye"></i>
        </button>
    </div>
    @error('password')
        <div class="text-danger small mt-1" style="font-size: 0.8rem;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>

<!-- Forgot Password Link Below Input Field -->
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('password.reset.request') }}" class="small text-decoration-none fw-semibold" style="color: #0284c7; font-size: 0.8rem;">
        Forgot your password?
    </a>
</div>

                    <button type="submit" class="btn btn-submit w-100 mb-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>

                    <div class="text-center text-muted small">
                        Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #0284c7;">Register</a>
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

            <div class="{{ $errors->any() ? 'no-animation' : 'animate-up delay-10' }}">
                <h1 class="display-4 fw-bold mb-3 text-white">
                    Welcome <span style="color: #34d399;">Back!</span>
                </h1>
                <p class="text-light opacity-75 fs-5 lh-base" style="max-width: 460px;">
                    Manage your loan applications, track payment schedules, and view account balances under our priority guarantee.
                </p>
            </div>

            <div class="row g-2 {{ $errors->any() ? 'no-animation' : 'animate-up delay-10' }} pt-4">
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="fw-semibold small text-white">Track Applications</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Stay updated in real-time.</div>
                </div>
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-calendar-check"></i></div>
                    <div class="fw-semibold small text-white">Payment Schedules</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Never miss a due date.</div>
                </div>
                <div class="col-3 text-center">
                    <div class="feature-circle mx-auto"><i class="bi bi-shield-check"></i></div>
                    <div class="fw-semibold small text-white">Secure & Reliable</div>
                    <div class="text-light opacity-50" style="font-size: 0.72rem;">Your data is protected.</div>
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
</script>
</body>
</html>