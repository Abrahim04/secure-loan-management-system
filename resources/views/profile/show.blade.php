@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Styling -->
<style>
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    .glass-file-input {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
    }
    .glass-file-input::file-selector-button {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: var(--text-main, #f8fafc) !important;
        border: none !important;
        border-right: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        padding: 0.5rem 1rem;
        margin-right: 1rem;
        transition: background-color 0.2s ease;
    }

    .btn-pill-outline {
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 18px;
        border-radius: 50px;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-pill-outline:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">My Profile</h2>
            <p class="text-muted small mb-0">Overview of your registered personal information and profile picture.</p>
        </div>
        <a href="{{ auth()->user()->isAdmin() ? route('admin.settings.system') : route('profile.show') }}" class="btn-pill-outline">
            <span>Edit Credentials in Settings</span>
        </a>
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
        {{-- Profile Avatar Box --}}
        <div class="col-lg-4">
            <div class="dash-card p-0 overflow-hidden h-100">
                <div class="profile-card-header py-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(31, 157, 90, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                    <div class="position-relative d-inline-block">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover; border: 4px solid var(--dash-card-bg, rgba(30, 41, 59, 0.55));">
                    </div>
                </div>
                <div class="p-4 text-center">
                    <h5 class="fw-bold text-main mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>

                    <span class="badge rounded-pill px-3 py-2 fw-semibold {{ $user->isAdmin() ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary' }} mb-4">
                        <span class="d-inline-block rounded-circle bg-current me-1" style="width: 6px; height: 6px; background-color: currentColor;"></span>
                        {{ ucfirst($user->role) }}
                    </span>

                    <hr class="my-3" style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08));">

                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="text-start mt-3">
                        @csrf
                        <label class="form-label fw-semibold small text-muted mb-2">Change Profile Picture</label>
                        <div class="mb-3">
                            <input type="file" name="avatar" class="form-control glass-file-input form-control-sm @error('avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png" required>
                        </div>
                        <button type="submit" class="btn-pill-outline w-100 d-flex align-items-center justify-content-center">
                            <span>Upload New Photo</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Read-Only Personal Details --}}
        <div class="col-lg-8">
            <div class="dash-card p-4">
                <div class="mb-4">
                    <h5 class="fw-bold text-main mb-0">Personal Information (Read-Only)</h5>
                    <span class="text-muted small">Your profile details registered in the system.</span>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Full Name</span>
                        <span class="fw-semibold text-main fs-6">{{ $user->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Email Address</span>
                        <span class="fw-semibold text-main fs-6">{{ $user->email }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Phone Number</span>
                        <span class="fw-semibold text-main fs-6">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Role</span>
                        <span class="fw-semibold text-main fs-6">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="col-md-12">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1" style="font-size: 0.75rem;">Address</span>
                        <span class="fw-semibold text-main fs-6">{{ $user->address ?? 'Not provided' }}</span>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08));">

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="text-muted small">Need to update your details or change your password?</span>
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.settings.system') : route('profile.show') }}" class="btn-pill-outline">
                        <span>Go to Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection