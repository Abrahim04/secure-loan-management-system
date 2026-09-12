@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<h4 class="mb-4">System Settings</h4>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <p class="text-muted small">
            These values control the Email OTP MFA flow used at login, forgot-password, and payment verification.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.system.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">OTP Expiry (minutes)</label>
                <input type="number" name="otp_expiry_minutes" class="form-control"
                       value="{{ old('otp_expiry_minutes', $settings['otp_expiry_minutes']) }}" min="1" max="60" required>
                <div class="form-text">How long a generated OTP code stays valid before expiring.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Max OTP Attempts</label>
                <input type="number" name="otp_max_attempts" class="form-control"
                       value="{{ old('otp_max_attempts', $settings['otp_max_attempts']) }}" min="1" max="10" required>
                <div class="form-text">Number of incorrect codes allowed before a new one must be requested.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Resend Cooldown (seconds)</label>
                <input type="number" name="otp_resend_cooldown_seconds" class="form-control"
                       value="{{ old('otp_resend_cooldown_seconds', $settings['otp_resend_cooldown_seconds']) }}" min="10" max="600" required>
                <div class="form-text">Minimum wait time before a user can request another OTP.</div>
            </div>

            <button type="submit" class="btn btn-primary">Save System Settings</button>
        </form>
    </div>
</div>
@endsection
