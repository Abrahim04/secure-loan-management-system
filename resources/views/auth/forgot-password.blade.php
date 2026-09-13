@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-2">Forgot Password</h4>
                <p class="text-muted">Enter your account email and we'll send you a verification code.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.reset.send') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', session('password_reset_email')) }}" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="send-code-btn" {{ $resendCooldown > 0 ? 'disabled' : '' }}>
                        Send Verification Code
                    </button>

                    <div id="resend-cooldown-text" class="text-muted small mt-2 text-center" style="{{ $resendCooldown <= 0 ? 'display:none;' : '' }}">
                        You can request another code in <span id="cooldown-timer">{{ sprintf('%02d:%02d', intdiv($resendCooldown, 60), $resendCooldown % 60) }}</span>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('login') }}">Back to login</a>
                </p>
            </div>
        </div>
    </div>
</div>

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
@endsection