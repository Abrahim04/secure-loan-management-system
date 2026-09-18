@extends('layouts.app')

@section('title', 'Verify Code')

@push('styles')
<style>
    /* Fullscreen background overlay */
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
                    url("{{ asset('images/mfa-bg.png') }}") no-repeat center center / cover;
    }

    .auth-container {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    /* Glass Card styling */
    .auth-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }

    /* OTP Form Input */
    .otp-input {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #10b981 !important;
        font-weight: 700;
        letter-spacing: 12px;
        transition: all 0.2s ease-in-out;
    }

    .otp-input:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
        background-color: #0f172a !important;
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

    /* Resend Link Hover */
    #resend-btn {
        transition: all 0.2s ease-in-out;
        color: #10b981;
    }

    #resend-btn:hover:not(:disabled) {
        color: #34d399 !important;
        text-decoration: underline !important;
        transform: scale(1.02);
    }

    /* Back Link Hover */
    .hover-white {
        transition: all 0.2s ease-in-out;
        display: inline-block;
    }

    .hover-white:hover {
        color: #ffffff !important;
        transform: translateX(-3px);
    }

    /* Entrance Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-up { animation: fadeInUp 0.5s ease-out forwards; }
    .no-animation { animation: none !important; opacity: 1 !important; transform: none !important; }
</style>
@endpush

@section('content')
<div class="auth-page-wrapper">
    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-5 col-lg-4 {{ $errors->any() ? 'no-animation' : 'animate-up' }}">
                <div class="card auth-card text-white">
                    <div class="card-body p-4 p-md-5 text-center">
                        
                        <!-- Logo -->
                        <div class="mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" style="height: 50px; width: auto;">
                        </div>

                        <h4 class="fw-bold mb-2">Enter Verification Code</h4>
                        <p class="text-secondary small mb-4">We sent a 6-digit code to your email if it matches an account.</p>

                        <!-- Error Alerts -->
                        @if ($errors->any())
    <div class="alert alert-danger text-start small border-0 mb-4" style="background-color: rgba(239, 68, 68, 0.15); color: #f87171;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                        <!-- OTP Form -->
                        <form method="POST" action="{{ route('password.reset.verify.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <input
                                    type="text"
                                    name="otp_code"
                                    class="form-control form-control-lg text-center otp-input"
                                    maxlength="6"
                                    inputmode="numeric"
                                    pattern="\d{6}"
                                    placeholder="------"
                                    required
                                    autofocus
                                >
                            </div>
                            <button type="submit" class="btn btn-emerald btn-lg w-100 mb-3 shadow-sm">
                                <i class="bi bi-patch-check me-2"></i>Verify
                            </button>
                        </form>

                        <!-- Cooldown Timer -->
                        <div id="resend-cooldown-text" class="text-secondary small mb-1" style="{{ $resendCooldown <= 0 ? 'display:none;' : '' }}">
                            Resend code in <span id="cooldown-timer" class="fw-bold text-light">{{ sprintf('%02d:%02d', intdiv($resendCooldown, 60),$resendCooldown % 60) }}</span>
                        </div>

                        <!-- Resend Form -->
                        <form method="POST" action="{{ route('password.reset.send') }}" class="mb-2">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('password_reset_email') }}">
                            <button type="submit" class="btn btn-link text-decoration-none small" id="resend-btn" {{ $resendCooldown > 0 ? 'disabled' : '' }}>
                                Resend code
                            </button>
                        </form>

                        <hr class="border-secondary opacity-25 my-3">

                        <!-- Start Over Link -->
                        <div class="pt-1">
                            <a href="{{ route('password.reset.request') }}" class="text-secondary text-decoration-none small hover-white">
                                <i class="bi bi-arrow-left me-1"></i>Start over
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let remaining = {{ $resendCooldown }};
    const resendBtn = document.getElementById('resend-btn');
    const cooldownText = document.getElementById('resend-cooldown-text');
    const timerSpan = document.getElementById('cooldown-timer');

    function formatTime(totalSeconds) {
        const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
        const s = (totalSeconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    if (remaining > 0) {
        resendBtn.disabled = true;
        resendBtn.style.pointerEvents = 'none';

        const countdownInterval = setInterval(() => {
            remaining--;

            if (remaining <= 0) {
                clearInterval(countdownInterval);
                resendBtn.disabled = false;
                resendBtn.style.pointerEvents = 'auto';
                cooldownText.style.display = 'none';
                return;
            }

            timerSpan.textContent = formatTime(remaining);
        }, 1000);
    }
</script>
@endpush