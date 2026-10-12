@extends('layouts.app')

@section('title', 'Verify Code')

@push('styles')
<style>
    .mfa-page-wrapper {
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

    .mfa-container {
        position: relative;
        z-index: 2;
        width: 100%;
    }

    .auth-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }

    /* OTP Masked Container Box */
    .otp-wrapper {
        position: relative;
        width: 100%;
    }

    .otp-input-real {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 5;
        cursor: pointer;
    }

    .otp-display-box {
        background-color: #1e293b;
        border: 1px solid #334155;
        color: #10b981;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 12px; /* Ibinalik sa orihinal na 12px */
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        text-align: center;
        transition: all 0.2s ease-in-out;
        user-select: none;
    }

    .otp-input-real:focus + .otp-display-box {
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        background-color: #0f172a;
    }

    .otp-wrapper.is-invalid .otp-display-box {
        border-color: #ef4444;
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

    /* Resend Code Button (WALANG UNDERLINE) */
    #resend-btn {
        transition: all 0.2s ease-in-out;
        color: #10b981;
        text-decoration: none !important;
    }

    #resend-btn:hover:not(:disabled) {
        color: #34d399 !important;
        text-decoration: none !important;
        transform: scale(1.02);
    }

    #resend-btn:active:not(:disabled) {
        transform: scale(0.98);
    }

    /* Back to Login Link Hover */
    .hover-white {
        transition: all 0.2s ease-in-out;
        display: inline-block;
    }

    .hover-white:hover {
        color: #ffffff !important;
        transform: translateX(-3px);
    }

    .hover-white:active {
        transform: translateX(-1px);
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
<div class="mfa-page-wrapper">
    <div class="container mfa-container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-5 col-lg-4 {{ $errors->any() ? 'no-animation' : 'animate-up' }}">
                <div class="card auth-card text-white">
                    <div class="card-body p-4 p-md-5 text-center">
                        
                        <!-- Logo Header (Option A: Logo Only) -->
                        <div class="mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" style="height: 52px; width: auto;">
                        </div>

                        <h4 class="fw-bold mb-2">Check Your Email</h4>
                        <p class="text-secondary small mb-4">
                            We sent a 6-digit verification code to your email address. It will expire shortly.
                        </p>

                        <!-- Error Alert Directive -->
                        @error('otp_code')
                            <div class="alert alert-danger text-start small border-0 mb-4" style="background-color: rgba(239, 68, 68, 0.15); color: #f87171;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <!-- OTP Form -->
                        <form method="POST" action="{{ route('mfa.verify.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <div class="otp-wrapper @error('otp_code') is-invalid @enderror">
                                    <input 
                                        type="text" 
                                        name="otp_code" 
                                        id="otp_code_real"
                                        class="otp-input-real" 
                                        maxlength="6" 
                                        inputmode="numeric" 
                                        pattern="\d{6}" 
                                        required 
                                        autocomplete="one-time-code"
                                        autofocus
                                    >
                                    <div class="otp-display-box" id="otp_display_box">
                                        - - - - - -
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-emerald btn-lg w-100 mb-3 shadow-sm">
                                Verify
                            </button>
                        </form>

                        <!-- Cooldown Timer -->
                        <div id="resend-cooldown-text" class="text-secondary small mb-2" style="{{ $resendCooldown <= 0 ? 'display:none;' : '' }}">
                            Resend code in <span id="cooldown-timer" class="fw-bold text-light">{{ sprintf('%02d:%02d', intdiv($resendCooldown, 60),$resendCooldown % 60) }}</span>
                        </div>

                        <!-- Resend Code Button -->
                        <form method="POST" action="{{ route('mfa.resend') }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none small" id="resend-btn" {{ $resendCooldown > 0 ? 'disabled' : '' }}>
                                Resend code
                            </button>
                        </form>

                        <hr class="border-secondary opacity-25 my-3">

                        <!-- Back to Login / Return Action -->
                        <div class="pt-1">
                            <a href="{{ route('login') }}" class="text-secondary text-decoration-none small hover-white">
                                Back to Login
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
    // Per-character Digit Masking Logic (na may katamtamang space)
    const realInput = document.getElementById('otp_code_real');
    const displayBox = document.getElementById('otp_display_box');

    function updateOtpDisplay() {
        if (!realInput || !displayBox) return;
        let val = realInput.value.replace(/\D/g, '').substring(0, 6);
        realInput.value = val;

        let placeholders = ['-', '-', '-', '-', '-', '-'];
        for (let i = 0; i < val.length; i++) {
            placeholders[i] = val[i];
        }
        displayBox.textContent = placeholders.join(' ');
    }

    if (realInput) {
        realInput.addEventListener('input', updateOtpDisplay);
        realInput.addEventListener('keyup', updateOtpDisplay);
        realInput.addEventListener('paste', () => setTimeout(updateOtpDisplay, 10));
    }

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