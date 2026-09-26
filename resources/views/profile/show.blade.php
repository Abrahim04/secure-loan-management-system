@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow);
    }
    
    .form-control-glass {
        background-color: transparent !important;
        border: 1px solid var(--dash-card-border) !important;
        color: var(--text-main) !important;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }
    .form-control-glass:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }
    .form-control-glass:disabled, .form-control-glass[readonly] {
        background-color: rgba(0, 0, 0, 0.03) !important;
        opacity: 0.7;
    }

    .btn-pill-primary {
        background: #10b981;
        border: 1px solid #059669;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-pill-primary:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-pill-outline {
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 18px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-pill-outline:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transform: translateY(-1px);
    }

    .btn-pill-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-pill-danger:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Banner & Page Title --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">My Profile</h2>
            <p class="text-muted small mb-0">Manage your personal information, profile photo, and account security.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Profile Photo & Overview Card --}}
        <div class="col-lg-4">
            <div class="dash-card p-0 overflow-hidden h-100">
                <div class="profile-card-header py-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(31, 157, 90, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); border-bottom: 1px solid var(--dash-card-border);">
                    <div class="position-relative d-inline-block">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover; border: 4px solid var(--dash-card-bg);">
                    </div>
                </div>
                <div class="p-4 text-center">
                    <h5 class="fw-bold text-main mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    
                    <span class="badge rounded-pill px-3 py-2 fw-semibold {{ $user->isAdmin() ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary' }} mb-4">
                        <span class="d-inline-block rounded-circle bg-current me-1" style="width: 6px; height: 6px; background-color: currentColor;"></span>
                        {{ ucfirst($user->role) }}
                    </span>

                    <hr class="my-3" style="border-color: var(--dash-card-border);">

                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="text-start mt-3">
                        @csrf
                        <label class="form-label fw-semibold small text-muted mb-2">Change Profile Picture</label>
                        <div class="mb-3">
                            <input type="file" name="avatar" class="form-control form-control-glass form-control-sm @error('avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png" required>
                            @error('avatar')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn-pill-outline w-100 d-flex align-items-center justify-content-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <span>Upload New Photo</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Personal Details Card --}}
            <div class="dash-card p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-3 text-emerald" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-main mb-0">Personal Details</h5>
                        <span class="text-muted small">Update your basic information and contact details.</span>
                    </div>
                </div>

                @if ($errors->has('name') || $errors->has('phone') || $errors->has('address'))
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
                        <ul class="mb-0 small ps-3">
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

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control form-control-glass" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Email Address</label>
                            <input type="email" class="form-control form-control-glass" value="{{ $user->email }}" disabled readonly>
                            <div class="form-text text-muted small mt-1">Email cannot be changed here.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-glass" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 09123456789">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small text-muted">Address</label>
                            <textarea name="address" class="form-control form-control-glass" rows="3" placeholder="Enter complete address">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn-pill-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Security Card --}}
            <div class="dash-card p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-2.5 rounded-3" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-main mb-0">Account Security</h5>
                        <span class="text-muted small">Ensure your account is using a strong and secure password.</span>
                    </div>
                </div>

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
                        <ul class="mb-0 small ps-3">
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
                        <label class="form-label fw-semibold small text-muted">Current Password</label>
                        <div class="position-relative">
                            <input type="password" name="current_password" id="profile-current-password" class="form-control form-control-glass pe-5" required>
                            <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent text-muted d-flex align-items-center" data-target="profile-current-password" aria-label="Show password"></button>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">New Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" id="profile-new-password" class="form-control form-control-glass pe-5" required>
                                <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent text-muted d-flex align-items-center" data-target="profile-new-password" aria-label="Show password"></button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-muted">Confirm New Password</label>
                            <div class="position-relative">
                                <input type="password" name="password_confirmation" id="profile-confirm-password" class="form-control form-control-glass pe-5" required>
                                <button type="button" class="password-toggle-btn position-absolute top-50 end-0 translate-middle-y me-3 p-0 border-0 bg-transparent text-muted d-flex align-items-center" data-target="profile-confirm-password" aria-label="Show password"></button>
                            </div>
                        </div>
                    </div>

                    {{-- Password Requirements Checklist --}}
                    <div class="p-3 rounded-3 mb-4" style="background: rgba(0, 0, 0, 0.02); border: 1px solid var(--dash-card-border);">
                        <span class="fw-semibold small d-block mb-2 text-muted">Password Requirements:</span>
                        <ul class="list-unstyled small mb-0 row row-cols-1 row-cols-md-2 g-2" id="profile-password-checklist">
                            <li data-rule="length" class="text-muted col d-flex align-items-center gap-2"><span class="rule-icon fw-bold">○</span> At least 8 characters</li>
                            <li data-rule="upper" class="text-muted col d-flex align-items-center gap-2"><span class="rule-icon fw-bold">○</span> One uppercase letter (A-Z)</li>
                            <li data-rule="lower" class="text-muted col d-flex align-items-center gap-2"><span class="rule-icon fw-bold">○</span> One lowercase letter (a-z)</li>
                            <li data-rule="number" class="text-muted col d-flex align-items-center gap-2"><span class="rule-icon fw-bold">○</span> One number (0-9)</li>
                            <li data-rule="symbol" class="text-muted col d-flex align-items-center gap-2"><span class="rule-icon fw-bold">○</span> Special character (!@#$%^&*)</li>
                        </ul>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn-pill-danger">
                            Change Password
                        </button>
                    </div>
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