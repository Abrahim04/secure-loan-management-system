@extends('layouts.app')

@section('title', 'Verify Code')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4 text-center">
                <h4 class="mb-2">Enter Verification Code</h4>
                <p class="text-muted">We sent a 6-digit code to your email if it matches an account.</p>

                @if ($errors->any())
                    <div class="alert alert-danger text-start">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.reset.verify.submit') }}">
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
                    <button type="submit" class="btn btn-primary w-100">Verify</button>
                </form>

                <p class="mt-3 mb-0">
                    <a href="{{ route('password.reset.request') }}">Start over</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
