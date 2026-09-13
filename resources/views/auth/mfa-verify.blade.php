@extends('layouts.app')

@section('title', 'Verify Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4 text-center">
                <h4 class="mb-2">Check Your Email</h4>
                <p class="text-muted">We sent a 6-digit verification code to your email address. It will expire shortly.</p>

                @if ($errors->any())
                    <div class="alert alert-danger text-start">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('mfa.verify.submit') }}">
                    @csrf
                    <input
                        type="text"
                        name="otp_code"
                        class="form-control form-control-lg text-center mb-3"
                        style="letter-spacing: 8px; font-size: 28px;"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="\d{6}"
                        placeholder="------"
                        required
                        autofocus
                    >
                    <button type="submit" class="btn btn-primary w-100 mb-3">Verify</button>
                </form>

                <div id="resend-cooldown-text" class="text-muted small mb-2" style="{{ $resendCooldown <= 0 ? 'display:none;' : '' }}">
                    Resend code in <span id="cooldown-timer">{{ sprintf('%02d:%02d', intdiv($resendCooldown, 60), $resendCooldown % 60) }}</span>
                </div>

                <form method="POST" action="{{ route('mfa.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link" id="resend-btn" {{ $resendCooldown > 0 ? 'disabled' : '' }}>
                        Resend code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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
@endsection
