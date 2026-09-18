@extends('layouts.app')

@section('title', 'Forgot Password')

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

    /* Glass Card styling */
    .auth-card {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
    }

    /* Form Inputs */
    .form-control-dark {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #f8fafc !important;
        transition: all 0.2s ease-in-out;
    }

    .form-control-dark:focus {
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

    .btn-emerald:hover:not(:disabled) {
        opacity: 0.95;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
    }

    .btn-emerald:active:not(:disabled) {
        transform: translateY(1px);
        box-shadow: none !important;
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
                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" style="height: 50px; width: auto;">
                        </div>

                        <h4 class="fw-bold mb-2 text-center">Forgot Password</h4>
                        <p class="text-secondary small mb-4 text-center">Enter your account email and we'll send you a verification code.</p>

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

                        <!-- Forgot Password Form -->
                        <form method="POST" action="{{ route('password.reset.send') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-semibold">Email Address</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control form-control-lg form-control-dark"
                                    value="{{ old('email', session('password_reset_email')) }}" 
                                    placeholder="name@example.com"
                                    required 
                                    autofocus
                                >
                            </div>

                            <button type="submit" class="btn btn-emerald btn-lg w-100 mb-2 shadow-sm" id="send-code-btn" {{ $resendCooldown > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-send me-2"></i>Send Verification Code
                            </button>

                            <div id="resend-cooldown-text" class="text-secondary small mt-2 text-center" style="{{ $resendCooldown <= 0 ? 'display:none;' : '' }}">
                                You can request another code in <span id="cooldown-timer" class="fw-bold text-light">{{ sprintf('%02d:%02d', intdiv($resendCooldown, 60),$resendCooldown % 60) }}</span>
                            </div>
                        </form>

                        <hr class="border-secondary opacity-25 my-4">

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-secondary text-decoration-none small hover-white">
                                <i class="bi bi-arrow-left me-1"></i>Back to login
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
    const sendBtn = document.getElementById('send-code-btn');
    const cooldownText = document.getElementById('resend-cooldown-text');
    const timerSpan = document.getElementById('cooldown-timer');

    function formatTime(totalSeconds) {
        const m = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
        const s = (totalSeconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    if (remaining > 0) {
        sendBtn.disabled = true;

        const countdownInterval = setInterval(() => {
            remaining--;

            if (remaining <= 0) {
                clearInterval(countdownInterval);
                sendBtn.disabled = false;
                cooldownText.style.display = 'none';
                return;
            }

            timerSpan.textContent = formatTime(remaining);
        }, 1000);
    }
</script>
@endpush