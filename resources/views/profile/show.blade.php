@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<h4 class="mb-4">My Profile</h4>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3">
    {{-- Profile Photo --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="120" height="120">
                <h6>{{ $user->name }}</h6>
                <span class="badge bg-{{ $user->isAdmin() ? 'dark' : 'primary' }}">{{ ucfirst($user->role) }}</span>

                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <input type="file" name="avatar" class="form-control form-control-sm mb-2" accept=".jpg,.jpeg,.png" required>
                    @error('avatar')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Upload New Photo</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Personal Details --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Personal Details</h6>

                @if ($errors->has('name') || $errors->has('phone') || $errors->has('address'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach (['name', 'phone', 'address'] as $field)
                                @foreach ($errors->get($field) as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="form-text">Email cannot be changed here.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        {{-- Account Security --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Account Security</h6>

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach (['current_password', 'password'] as $field)
                                @foreach ($errors->get($field) as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <div class="position-relative">
                            <input type="password" name="current_password" id="profile-current-password" class="form-control pe-5" required>
                            <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent text-secondary" data-target="profile-current-password" aria-label="Show password"></button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="profile-new-password" class="form-control pe-5" required>
                            <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent text-secondary" data-target="profile-new-password" aria-label="Show password"></button>
                        </div>
                        <ul class="list-unstyled small mt-2 mb-0" id="profile-password-checklist">
                            <li data-rule="length" class="text-muted"><span class="rule-icon">○</span> At least 8 characters</li>
                            <li data-rule="upper" class="text-muted"><span class="rule-icon">○</span> One uppercase letter (A-Z)</li>
                            <li data-rule="lower" class="text-muted"><span class="rule-icon">○</span> One lowercase letter (a-z)</li>
                            <li data-rule="number" class="text-muted"><span class="rule-icon">○</span> One number (0-9)</li>
                            <li data-rule="symbol" class="text-muted"><span class="rule-icon">○</span> One special character (!@#$%^&amp;*)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="profile-confirm-password" class="form-control pe-5" required>
                            <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent text-secondary" data-target="profile-confirm-password" aria-label="Show password"></button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-danger">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const EYE_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    const EYE_OFF_SVG = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

    document.querySelectorAll('.password-toggle-btn').forEach(function (btn) {
        btn.innerHTML = EYE_SVG;
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            this.innerHTML = showing ? EYE_SVG : EYE_OFF_SVG;
        });
    });

    function attachPasswordChecklist(inputId, checklistId) {
        const input = document.getElementById(inputId);
        const checklist = document.getElementById(checklistId);
        if (!input || !checklist) return;

        const rules = {
            length: v => v.length >= 8,
            upper: v => /[A-Z]/.test(v),
            lower: v => /[a-z]/.test(v),
            number: v => /[0-9]/.test(v),
            symbol: v => /[^A-Za-z0-9]/.test(v),
        };

        input.addEventListener('input', function () {
            const value = input.value;
            let allValid = true;

            Object.keys(rules).forEach(function (key) {
                const li = checklist.querySelector('[data-rule="' + key + '"]');
                if (!li) return;
                const valid = rules[key](value);
                if (!valid) allValid = false;

                li.classList.toggle('text-success', valid);
                li.classList.toggle('text-danger', !valid && value.length > 0);
                li.classList.toggle('text-muted', value.length === 0);
                li.querySelector('.rule-icon').textContent = valid ? '✓' : '○';
            });

            const hasValue = value.length > 0;
            input.classList.toggle('is-valid', allValid && hasValue);
            input.classList.toggle('is-invalid', !allValid && hasValue);
        });
    }

    attachPasswordChecklist('profile-new-password', 'profile-password-checklist');
</script>
@endpush
@endsection