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

                <form method="POST" action="{{ route('mfa.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">Resend code</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
