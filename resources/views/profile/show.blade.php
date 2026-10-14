@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Cropper.js CSS & JS CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Adaptive Glassmorphism & Professional IT Styling -->
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
        display: inline-block;
        text-align: center;
        cursor: pointer;
    }
    .btn-pill-outline:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transform: translateY(-1px);
    }

    /* Auto-Dismissing Custom Glass Alerts */
    .glass-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .glass-alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }
    .glass-alert.fade-out {
        opacity: 0;
        transform: translateY(-10px);
    }

    /* Strict Circle Crop Styling */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }

    /* Professional IT Theme-Aware Modal Styling */
    .theme-crop-modal .modal-content {
        background-color: var(--dash-card-bg) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 16px !important;
        box-shadow: var(--dash-card-shadow) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }
    .theme-crop-modal .modal-header,
    .theme-crop-modal .modal-footer {
        border-color: var(--topbar-border, rgba(100, 116, 139, 0.2)) !important;
    }
    .theme-crop-modal .btn-close {
        filter: var(--btn-close-filter, none);
    }
    [data-bs-theme="dark"] .theme-crop-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .btn-crop-cancel {
        background: rgba(100, 116, 139, 0.12) !important;
        color: var(--text-main) !important;
        border: 1px solid rgba(100, 116, 139, 0.3) !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-crop-cancel:hover {
        background: rgba(100, 116, 139, 0.3) !important;
        color: var(--text-main) !important;
        border-color: rgba(100, 116, 139, 0.5) !important;
        transform: translateY(-1px);
    }

    .btn-crop-save {
        background: rgba(2, 132, 199, 0.12) !important;
        color: #0284c7 !important;
        border: 1px solid rgba(2, 132, 199, 0.3) !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 22px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-crop-save:hover {
        background: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7 !important;
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

    {{-- Modern Auto-Dismissing Glass Alert --}}
    @if (session('status'))
        <div id="autoDismissAlert" class="glass-alert glass-alert-success mb-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('status') }}</span>
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

                    <form id="avatarUploadForm" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="text-start mt-3">
                        @csrf
                        <label class="form-label fw-semibold small text-muted mb-2">Change Profile Picture</label>
                        <div class="mb-3">
                            <input type="file" id="tempImageInput" class="form-control glass-file-input form-control-sm" accept=".jpg,.jpeg,.png">
                            <input type="file" name="avatar" id="realAvatarInput" class="d-none">
                        </div>
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
            </div>
        </div>
    </div>
</div>

<!-- Interactive Cropping Modal Popup -->
<div class="modal fade theme-crop-modal" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Crop Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <div style="max-height: 350px; width: 100%; overflow: hidden;">
                    <img id="imageToCrop" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-crop-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-crop-save" id="btnCropAndUpload">Crop & Upload</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-Dismiss Alert Script
        const alertBox = document.getElementById('autoDismissAlert');
        if (alertBox) {
            setTimeout(function() {
                alertBox.classList.add('fade-out');
                setTimeout(function() {
                    alertBox.remove();
                }, 500);
            }, 3500);
        }

        let cropperInstance = null;
        const tempImageInput = document.getElementById('tempImageInput');
        const cropModalEl = document.getElementById('cropModal');
        const cropModal = new bootstrap.Modal(cropModalEl);
        const imageToCrop = document.getElementById('imageToCrop');
        const btnCropAndUpload = document.getElementById('btnCropAndUpload');

        tempImageInput.addEventListener('change', function (e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        cropModalEl.addEventListener('shown.bs.modal', function () {
            if (cropperInstance) {
                cropperInstance.destroy();
            }
            cropperInstance = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.9,
                responsive: true,
            });
        });

        cropModalEl.addEventListener('hidden.bs.modal', function () {
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
            tempImageInput.value = '';
        });

        btnCropAndUpload.addEventListener('click', function () {
            if (!cropperInstance) return;

            const canvas = cropperInstance.getCroppedCanvas({
                width: 300,
                height: 300,
            });

            canvas.toBlob(function (blob) {
                const croppedFile = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);

                document.getElementById('realAvatarInput').files = dataTransfer.files;
                cropModal.hide();
                document.getElementById('avatarUploadForm').submit();
            }, 'image/jpeg', 0.9);
        });
    });
</script>
@endsection