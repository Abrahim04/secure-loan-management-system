@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<!-- Adaptive Glassmorphism & Modern Vertical Settings Layout -->
<style>
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: all 0.2s ease;
        overflow: visible !important;
    }

    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Vertical Settings Sub-Navigation Menu */
    .settings-nav-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .settings-nav-item {
        display: flex;
        flex-direction: column;
        padding: 12px 16px;
        border-radius: 12px;
        background: transparent;
        border: 1px solid transparent;
        color: var(--text-muted, #94a3b8);
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .settings-nav-item:hover {
        background: rgba(100, 116, 139, 0.1);
        color: var(--text-main, #f8fafc);
    }

    .settings-nav-item.active {
        background: rgba(2, 132, 199, 0.12);
        border-color: rgba(2, 132, 199, 0.3);
        color: #0284c7;
    }

    .settings-nav-item .nav-title {
        font-weight: 600;
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .settings-nav-item.active .nav-title {
        color: #38bdf8;
    }

    .settings-nav-item .nav-desc {
        font-size: 0.75rem;
        opacity: 0.75;
        margin-top: 2px;
    }

    .form-control-glass {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control-glass:focus {
        background-color: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        outline: none;
    }

    .form-control-glass.is-invalid {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.05) !important;
        background-image: none !important;
        padding-right: 2.8rem !important;
    }

    /* Eye Toggle Button for Glass Inputs */
    .eye-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted, #94a3b8);
        cursor: pointer;
        z-index: 5;
    }

    .eye-toggle:hover {
        color: var(--text-main, #f8fafc);
    }

    /* Glass Primary Action Button */
    .btn-glass-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-glass-primary:hover {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }

    .btn-pill-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }

    .btn-pill-danger:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
    }

    .glass-info-box {
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid rgba(2, 132, 199, 0.2);
        border-radius: 12px;
        padding: 14px 18px;
        color: var(--text-main, #f8fafc);
        font-size: 0.85rem;
    }

    .glass-info-box code {
        background: rgba(255, 255, 255, 0.1);
        color: #38bdf8;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    /* Seamless Glassmorphism Table Styling */
    .glass-table {
        background: transparent !important;
        color: var(--text-main, #f8fafc) !important;
    }

    .glass-table th,
    .glass-table td {
        background: transparent !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        color: var(--text-main, #f8fafc) !important;
    }

    .glass-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .glass-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }

    /* Custom Glassmorphism Dropdown Container with Smart Dropup/Dropdown */
    .custom-dropdown-container {
        position: relative;
        width: 100%;
        overflow: visible !important;
    }
    .custom-dropdown-toggle {
        width: 100%;
        background-color: rgba(100, 116, 139, 0.05) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }
    .custom-dropdown-toggle:hover,
    .custom-dropdown-container.show .custom-dropdown-toggle {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .custom-dropdown-toggle svg {
        transition: transform 0.2s ease;
        color: var(--text-muted, #94a3b8);
    }
    .custom-dropdown-container.show .custom-dropdown-toggle svg {
        transform: rotate(180deg);
    }

   /* Unang Card na naglalaman ng Personal Information */
    #accountCat .dash-card:first-child {
        position: relative !important;
        z-index: 50 !important;
        overflow: visible !important;
    }

    /* Custom Glassmorphism Dropdown Menu na mas mataas kaysa sa susunod na card */
    .custom-dropdown-menu {
        position: absolute;
        left: 0;
        right: 0;
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.98)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        z-index: 999999 !important;
        display: none;
        max-height: 200px;
        overflow-y: auto;
    
    }
    .custom-dropdown-container.dropup .custom-dropdown-menu {
        bottom: calc(100% + 6px);
        top: auto !important;
    }
    .custom-dropdown-container:not(.dropup) .custom-dropdown-menu {
        top: calc(100% + 6px);
        bottom: auto !important;
    }
    .custom-dropdown-container.show .custom-dropdown-menu {
        display: block;
        animation: dropDownFade 0.15s ease-out;
    }
    @keyframes dropDownFade {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .custom-dropdown-item {
        padding: 8px 12px;
        color: var(--text-main, #f8fafc) !important;
        font-weight: 500;
        font-size: 0.85rem;
        border-radius: 8px;
        cursor: pointer;
        background-color: transparent !important;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .custom-dropdown-item:hover {
        background-color: rgba(2, 132, 199, 0.15) !important;
        color: #0284c7 !important;
    }
    .custom-dropdown-item.selected {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    /* Modern Glassmorphism Confirmation Modals */
    .glass-modal .modal-content {
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.95)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 20px;
        box-shadow: var(--dash-card-shadow, 0 20px 40px rgba(0, 0, 0, 0.4));
        color: var(--text-main, #f8fafc);
    }
    .glass-modal .modal-header, 
    .glass-modal .modal-footer {
        border-color: var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
    }
    .modal-icon-badge {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .modal-icon-badge.warning {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
    }
    .btn-modal-cancel {
        background: rgba(100, 116, 139, 0.12) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid rgba(100, 116, 139, 0.3) !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        border-radius: 50px;
        transition: all 0.2s ease;
    }
    .btn-modal-cancel:hover {
        background: rgba(100, 116, 139, 0.3) !important;
        color: var(--text-main, #f8fafc) !important;
        border-color: rgba(100, 116, 139, 0.5) !important;
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

    .glass-dropdown-toggle {
    width: 100%;
    background-color: rgba(100, 116, 139, 0.06) !important;
    color: var(--text-main, #f8fafc) !important;
    border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.14)) !important;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: left;
}

.glass-dropdown-toggle:hover,
.glass-dropdown-toggle:focus,
.glass-dropdown-toggle[aria-expanded="true"] {
    background-color: rgba(2, 132, 199, 0.08) !important;
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    color: var(--text-main, #f8fafc) !important;
}

.glass-dropdown-menu {
    width: 100%;
    background-color: var(--dash-card-bg, #1e293b) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.14)) !important;
    border-radius: 12px;
    padding: 6px;
    margin-top: 6px !important;
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.28);
    z-index: 1050;
}

.glass-dropdown-item {
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-main, #f8fafc) !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    transition: all 0.15s ease;
    margin-bottom: 2px;
}

.glass-dropdown-item:last-child {
    margin-bottom: 0;
}

.glass-dropdown-item:hover,
.glass-dropdown-item:focus {
    background-color: rgba(2, 132, 199, 0.14) !important;
    color: #0284c7 !important;
}

.glass-dropdown-item.active,
.glass-dropdown-item.active:hover,
.glass-dropdown-item.active:focus {
    background-color: #0284c7 !important;
    color: #ffffff !important;
    font-weight: 600;
}
</style>

<div class="container-fluid px-0">
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Settings</h2>
        <p class="text-muted small mb-0">Select a category from the menu to manage preferences and configurations.</p>
    </div>

    {{-- Auto-Dismissing Flash Alert --}}
    @if (session('status'))
        <div id="autoDismissAlert" class="glass-alert glass-alert-success mb-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="row g-4">
        {{-- KALIWA: Settings Navigation Side Menu --}}
        <div class="col-lg-3">
            <div class="dash-card p-3">
                <span class="text-muted small text-uppercase fw-bold d-block px-2 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Categories</span>
                
                <div class="settings-nav-group">
                    <button type="button" class="settings-nav-item active" onclick="switchAdminTab('accountCat', this)">
                        <span class="nav-title">Account & Security</span>
                        <span class="nav-desc">Personal details & password</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchAdminTab('authCat', this)">
                        <span class="nav-title">Authentication (MFA/OTP)</span>
                        <span class="nav-desc">OTP duration & attempt limits</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchAdminTab('maintenanceCat', this)">
                        <span class="nav-title">System Operational State</span>
                        <span class="nav-desc">Operational, maintenance & shutdown</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchAdminTab('loanDefaultsCat', this)">
                        <span class="nav-title">Loan Defaults</span>
                        <span class="nav-desc">Interest rates & max loan terms</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchAdminTab('alertsCat', this)">
                        <span class="nav-title">Notification & Alerts</span>
                        <span class="nav-desc">Email alert triggers for admin</span>
                    </button>

                    <button type="button" class="settings-nav-item" onclick="switchAdminTab('penaltyCat', this)">
                        <span class="nav-title">Penalty Policy</span>
                        <span class="nav-desc">Overdue rates & grace periods</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- KANAN: Selected Setting Category Content --}}
        <div class="col-lg-9">
            {{-- CATEGORY 1: Account & Security --}}
            <div id="accountCat" class="settings-content-panel">
                <div class="dash-card p-4 mb-4">
                    <h5 class="fw-bold text-main mb-1">Personal Information</h5>
                    <p class="text-muted small mb-4">Update your complete personal information to clear profile verification requirements.</p>

<form method="POST" action="{{ route('profile.update') }}" id="personalDetailsForm" enctype="multipart/form-data">                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Full Name *</label>
                                <input type="text" name="name" class="form-control form-control-glass" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Email Address</label>
                                <input type="email" class="form-control form-control-glass" value="{{ auth()->user()->email }}" disabled readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Phone Number *</label>
                                <input type="text" name="phone" class="form-control form-control-glass" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. 09123456789" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Date of Birth *</label>
                                <input type="date" name="dob" class="form-control form-control-glass" value="{{ old('dob', auth()->user()->dob ? auth()->user()->dob->format('Y-m-d') : '') }}" required>
                            </div>

                            {{-- Custom Gender Dropdown --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Gender</label>
                                <input type="hidden" name="gender" id="gender_input" value="{{ old('gender', auth()->user()->gender) }}">
                                <div class="custom-dropdown-container" id="genderDropdown">
                                    <div class="custom-dropdown-toggle">
                                        <span class="selected-text">{{ old('gender', auth()->user()->gender) ? old('gender', auth()->user()->gender) : 'Select Gender' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </div>
                                    <div class="custom-dropdown-menu">
                                        <div class="custom-dropdown-item {{ old('gender', auth()->user()->gender) == 'Male' ? 'selected' : '' }}" data-value="Male">Male</div>
                                        <div class="custom-dropdown-item {{ old('gender', auth()->user()->gender) == 'Female' ? 'selected' : '' }}" data-value="Female">Female</div>
                                        <div class="custom-dropdown-item {{ old('gender', auth()->user()->gender) == 'Other' ? 'selected' : '' }}" data-value="Other">Other</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Nationality</label>
                                <input type="text" name="nationality" class="form-control form-control-glass" value="{{ old('nationality', auth()->user()->nationality ?? 'Filipino') }}" placeholder="e.g. Filipino">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Address *</label>
                                <textarea name="address" class="form-control form-control-glass" rows="2" placeholder="Enter complete address" required>{{ old('address', auth()->user()->address) }}</textarea>
                            </div>

                            {{-- Custom ID Type Dropdown --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">ID Type</label>
                                <input type="hidden" name="id_type" id="id_type_input" value="{{ old('id_type', auth()->user()->id_type) }}">
                                <div class="custom-dropdown-container" id="idTypeDropdown">
                                    <div class="custom-dropdown-toggle">
                                        <span class="selected-text">{{ old('id_type', auth()->user()->id_type) ? old('id_type', auth()->user()->id_type) : 'Select Valid ID' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </div>
                                    <div class="custom-dropdown-menu">
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == 'PhilSys National ID' ? 'selected' : '' }}" data-value="PhilSys National ID">PhilSys National ID</div>
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == "Driver's License" ? 'selected' : '' }}" data-value="Driver's License">Driver's License</div>
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == 'UMID Card' ? 'selected' : '' }}" data-value="UMID Card">UMID Card</div>
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == 'SSS ID' ? 'selected' : '' }}" data-value="SSS ID">SSS ID</div>
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == 'Postal ID' ? 'selected' : '' }}" data-value="Postal ID">Postal ID</div>
                                        <div class="custom-dropdown-item {{ old('id_type', auth()->user()->id_type) == 'Student ID' ? 'selected' : '' }}" data-value="Student ID">Student ID</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">ID Number</label>
                                <input type="text" name="id_number" class="form-control form-control-glass" value="{{ old('id_number', auth()->user()->id_number) }}" placeholder="e.g. 2316548978/974">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Upload ID Picture / Document</label>
                                <input type="file" name="id_picture" class="form-control form-control-glass @error('id_picture') is-invalid @enderror" accept="image/*,.pdf">
                                @error('id_picture')
                                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted" style="font-size: 0.75rem;">Supported: JPG, PNG, PDF (Max: 2MB)</small>
                            </div>

                            {{-- Custom Employment Status Dropdown --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Employment Status</label>
                                <input type="hidden" name="employment_status" id="employment_status_input" value="{{ old('employment_status', auth()->user()->employment_status ?? 'Employed') }}">
                                <div class="custom-dropdown-container" id="empStatusDropdown">
                                    <div class="custom-dropdown-toggle">
                                        <span class="selected-text">{{ old('employment_status', auth()->user()->employment_status) ? old('employment_status', auth()->user()->employment_status) : 'Select Status' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </div>
                                    <div class="custom-dropdown-menu">
                                        <div class="custom-dropdown-item {{ old('employment_status', auth()->user()->employment_status) == 'Employed' ? 'selected' : '' }}" data-value="Employed">Employed</div>
                                        <div class="custom-dropdown-item {{ old('employment_status', auth()->user()->employment_status) == 'Unemployed' ? 'selected' : '' }}" data-value="Unemployed">Unemployed</div>
                                        <div class="custom-dropdown-item {{ old('employment_status', auth()->user()->employment_status) == 'Student' ? 'selected' : '' }}" data-value="Student">Student</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Occupation</label>
                                <input type="text" name="occupation" class="form-control form-control-glass" value="{{ old('occupation', auth()->user()->occupation) }}" placeholder="e.g. IT Operations">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Position</label>
                                <input type="text" name="job_position" class="form-control form-control-glass" value="{{ old('job_position', auth()->user()->job_position) }}" placeholder="e.g. System Admin">
                            </div>

                            {{-- Custom Monthly Income Dropdown --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Monthly Income Range</label>
                                <input type="hidden" name="monthly_income" id="monthly_income_input" value="{{ old('monthly_income', auth()->user()->monthly_income) }}">
                                <div class="custom-dropdown-container" id="incomeDropdown">
                                    <div class="custom-dropdown-toggle">
                                        <span class="selected-text">{{ old('monthly_income', auth()->user()->monthly_income) ? old('monthly_income', auth()->user()->monthly_income) : 'Select Range' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </div>
                                    <div class="custom-dropdown-menu">
                                        <div class="custom-dropdown-item {{ old('monthly_income', auth()->user()->monthly_income) == 'Below ₱10,000' ? 'selected' : '' }}" data-value="Below ₱10,000">Below ₱10,000</div>
                                        <div class="custom-dropdown-item {{ old('monthly_income', auth()->user()->monthly_income) == '₱10,000 - ₱25,000' ? 'selected' : '' }}" data-value="₱10,000 - ₱25,000">₱10,000 - ₱25,000</div>
                                        <div class="custom-dropdown-item {{ old('monthly_income', auth()->user()->monthly_income) == '₱25,000 - ₱50,000' ? 'selected' : '' }}" data-value="₱25,000 - ₱50,000">₱25,000 - ₱50,000</div>
                                        <div class="custom-dropdown-item {{ old('monthly_income', auth()->user()->monthly_income) == 'Above ₱50,000' ? 'selected' : '' }}" data-value="Above ₱50,000">Above ₱50,000</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Income Source</label>
                                <input type="text" name="income_source" class="form-control form-control-glass" value="{{ old('income_source', auth()->user()->income_source) }}" placeholder="e.g. Salary">
                            </div>
                        </div>
                        <div class="mt-5 pt-3 text-end">
                            <button type="button" class="btn-glass-primary" data-bs-toggle="modal" data-bs-target="#confirmPersonalModal">
                                Save Personal Details
                            </button>
                        </div>
                    </form>
                </div>

                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Account Security</h5>
                    <p class="text-muted small mb-4">Update your password to keep your admin account safe with strict security rules.</p>

                    <form method="POST" action="{{ route('admin.settings.password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            {{-- Current Password --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Current Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="current_password" id="admin_current_password" class="form-control form-control-glass @error('current_password') is-invalid @enderror" placeholder="Enter current password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('admin_current_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">New Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="adminNewPassword" class="form-control form-control-glass @error('password') is-invalid @enderror" placeholder="Create a strong password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('adminNewPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Confirm New Password *</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="adminConfirmPassword" class="form-control form-control-glass" placeholder="Confirm your password" required>
                                    <button type="button" class="eye-toggle" onclick="togglePassword('adminConfirmPassword', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Live Password Requirements Box --}}
                        <div class="p-3 my-3 rounded-3 transition-all" id="adminReqBox" style="background-color: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="d-flex align-items-center gap-2 mb-2 text-secondary fw-semibold" style="font-size: 0.82rem;">
                                <i class="bi bi-shield text-secondary" id="adminShieldHeaderIcon" style="font-size: 1rem;"></i>
                                <span id="adminShieldHeaderText">Password Requirements:</span>
                            </div>

                            <div class="d-flex flex-column gap-1" style="font-size: 0.82rem;">
                                <div id="adminRuleLength" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="adminIconLength" style="font-size: 0.75rem;"></i> At least 8 characters
                                </div>
                                <div id="adminRuleUpper" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="adminIconUpper" style="font-size: 0.75rem;"></i> One uppercase letter (A-Z)
                                </div>
                                <div id="adminRuleLower" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="adminIconLower" style="font-size: 0.75rem;"></i> One lowercase letter (a-z)
                                </div>
                                <div id="adminRuleNumber" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="adminIconNumber" style="font-size: 0.75rem;"></i> One number (0-9)
                                </div>
                                <div id="adminRuleSpecial" class="text-secondary transition-all d-flex align-items-center gap-2">
                                    <i class="bi bi-circle" id="adminIconSpecial" style="font-size: 0.75rem;"></i> One special character (!@#$%^&*)
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn-pill-danger">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 2: Authentication (MFA/OTP) --}}
            <div id="authCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Authentication & OTP Parameters</h5>
                    <p class="text-muted small mb-4">Configure email OTP duration and attempt restrictions.</p>

                    <div class="glass-info-box mb-4">
                        These parameters control the MFA flow for user logins, verification codes, and password resets.
                    </div>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="maintenance_mode" value="{{ $settings['maintenance_mode'] }}">
                        @if($settings['admin_notify_loan_application'] == '1') <input type="hidden" name="admin_notify_loan_application" value="1"> @endif
                        @if($settings['admin_notify_large_repayment'] == '1') <input type="hidden" name="admin_notify_large_repayment" value="1"> @endif

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">OTP Expiry (minutes)</label>
                                <input type="number" name="otp_expiry_minutes" class="form-control form-control-glass" value="{{ old('otp_expiry_minutes', $settings['otp_expiry_minutes']) }}" min="1" max="60" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Max OTP Attempts</label>
                                <input type="number" name="otp_max_attempts" class="form-control form-control-glass" value="{{ old('otp_max_attempts', $settings['otp_max_attempts']) }}" min="1" max="10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Free Resend Attempts</label>
                                <input type="number" name="otp_free_resend_attempts" class="form-control form-control-glass" value="{{ old('otp_free_resend_attempts', $settings['otp_free_resend_attempts']) }}" min="0" max="20" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Resend Cooldown (seconds)</label>
                                <input type="number" name="otp_resend_cooldown_seconds" class="form-control form-control-glass" value="{{ old('otp_resend_cooldown_seconds', $settings['otp_resend_cooldown_seconds']) }}" min="10" max="600" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-glass-primary">Save OTP Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 3: System Operational State (Maintenance & Shutdown) --}}
            <div id="maintenanceCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">System Operational State</h5>
                    <p class="text-muted small mb-4">Control system access, schedule maintenance, or execute an emergency system shutdown for borrowers.</p>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}" id="systemStateForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="otp_expiry_minutes" value="{{ $settings['otp_expiry_minutes'] }}">
                        <input type="hidden" name="otp_max_attempts" value="{{ $settings['otp_max_attempts'] }}">
                        <input type="hidden" name="otp_resend_cooldown_seconds" value="{{ $settings['otp_resend_cooldown_seconds'] }}">
                        <input type="hidden" name="otp_free_resend_attempts" value="{{ $settings['otp_free_resend_attempts'] }}">
                        @if($settings['admin_notify_loan_application'] == '1') <input type="hidden" name="admin_notify_loan_application" value="1"> @endif
                        @if($settings['admin_notify_large_repayment'] == '1') <input type="hidden" name="admin_notify_large_repayment" value="1"> @endif

                        {{-- Hidden Input for form submission --}}
                        <input type="hidden" name="maintenance_mode" id="selected_maintenance_mode" value="{{ $settings['maintenance_mode'] }}">

                        @php
                            $stateLabels = [
                                '0' => '🟢 0 - Operational (Normal Operation)',
                                '1' => '🟡 1 - Under Maintenance (App Closed for Borrowers)',
                                '2' => '🔴 2 - Emergency Shutdown / System Offline (Service Halted)'
                            ];
                            $currentStateVal = (string) $settings['maintenance_mode'];
                            $currentStateLabel = $stateLabels[$currentStateVal] ?? $stateLabels['0'];
                        @endphp

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-muted mb-2">Select Operational State</label>
                            
                            {{-- Custom Glass Dropdown --}}
                            <div class="dropdown">
                                <button class="glass-dropdown-toggle dropdown-toggle" type="button" id="operationalStateDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="selectedStateLabel">{{ $currentStateLabel }}</span>
                                </button>
                                <ul class="dropdown-menu glass-dropdown-menu" aria-labelledby="operationalStateDropdown">
                                    @foreach ($stateLabels as $val => $lbl)
                                        <li>
                                            <a class="dropdown-item glass-dropdown-item state-option {{ $currentStateVal === (string)$val ? 'active' : '' }}" data-value="{{ $val }}" data-label="{{ $lbl }}">
                                                <span>{{ $lbl }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="check-icon {{ $currentStateVal === (string)$val ? '' : 'd-none' }}"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="form-text text-muted small mt-3">
                                <strong>Operational:</strong> Fully accessible to everyone.<br>
                                <strong>Maintenance:</strong> Borrowers are temporarily restricted with a maintenance notice.<br>
                                <strong>Shutdown:</strong> Complete offline state for borrowers (Flowchart End state) until the admin restarts/switches it back.
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn-glass-primary" data-bs-toggle="modal" data-bs-target="#confirmStateModal">
                                Save Operational State
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 4: Loan Defaults (Loan Types Specific) --}}
            <div id="loanDefaultsCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Loan Product Specific Configurations</h5>
                    <p class="text-muted small mb-4">Adjust baseline interest rates, max allowable terms, and amount ranges for each specific loan type.</p>

                    <form method="POST" action="{{ route('admin.settings.loantypes.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive mb-4">
                            <table class="table glass-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="small text-muted text-uppercase" style="width: 25%;">Loan Type</th>
                                        <th class="small text-muted text-uppercase" style="width: 15%;">Interest Rate (%)</th>
                                        <th class="small text-muted text-uppercase" style="width: 15%;">Max Term (mos)</th>
                                        <th class="small text-muted text-uppercase" style="width: 45%;">Amount Range (₱)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loanTypes as $type)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-main d-block">{{ $type->name }}</span>
                                                <small class="text-muted">{{ Str::limit($type->description, 35) }}</small>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="loan_types[{{ $type->id }}][interest_rate]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.interest_rate', $type->interest_rate) }}" min="0" required style="max-width: 90px;">
                                            </td>
                                            <td>
                                                <input type="number" name="loan_types[{{ $type->id }}][max_term_months]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.max_term_months', $type->max_term_months) }}" min="1" required style="max-width: 90px;">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="number" step="0.01" name="loan_types[{{ $type->id }}][min_amount]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.min_amount', $type->min_amount) }}" min="0" required placeholder="Min ₱" style="max-width: 110px;">
                                                    <span class="text-muted">-</span>
                                                    <input type="number" step="0.01" name="loan_types[{{ $type->id }}][max_amount]" class="form-control form-control-glass form-control-sm" value="{{ old('loan_types.'.$type->id.'.max_amount', $type->max_amount) }}" min="0" required placeholder="Max ₱" style="max-width: 120px;">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-glass-primary">Save Loan Type Configurations</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 5: Notification & Alerts --}}
            <div id="alertsCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Admin Alert Preferences</h5>
                    <p class="text-muted small mb-4">Choose which events trigger real-time system and email alerts.</p>

                    <form method="POST" action="{{ route('admin.settings.system.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="otp_expiry_minutes" value="{{ $settings['otp_expiry_minutes'] }}">
                        <input type="hidden" name="otp_max_attempts" value="{{ $settings['otp_max_attempts'] }}">
                        <input type="hidden" name="otp_resend_cooldown_seconds" value="{{ $settings['otp_resend_cooldown_seconds'] }}">
                        <input type="hidden" name="otp_free_resend_attempts" value="{{ $settings['otp_free_resend_attempts'] }}">
                        <input type="hidden" name="maintenance_mode" value="{{ $settings['maintenance_mode'] }}">

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="admin_notify_loan_application" name="admin_notify_loan_application" value="1" {{ $settings['admin_notify_loan_application'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-main ms-2" for="admin_notify_loan_application">New Loan Application Alerts</label>
                            </div>
                            <span class="text-muted small d-block ms-4">Receive notification whenever a borrower submits a new loan request.</span>
                        </div>

                        <div class="p-3 rounded-3 mb-4" style="background: rgba(100, 116, 139, 0.05); border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" class="form-check-input" id="admin_notify_large_repayment" name="admin_notify_large_repayment" value="1" {{ $settings['admin_notify_large_repayment'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-main ms-2" for="admin_notify_large_repayment">Large Repayment Notifications</label>
                            </div>
                            <span class="text-muted small d-block ms-4">Get alerted when a payment above ₱10,000 is logged into the system.</span>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-glass-primary">Save Alert Preferences</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CATEGORY 6: Penalty Policy --}}
            <div id="penaltyCat" class="settings-content-panel" style="display: none;">
                <div class="dash-card p-4">
                    <h5 class="fw-bold text-main mb-1">Penalty Policy</h5>
                    <p class="text-muted small mb-4">Configure late charges and maximum penalty caps.</p>

                    <div class="glass-info-box mb-4">
                        These values control the automatic overdue detection command (<code>php artisan loans:apply-penalties</code>).
                    </div>

                    <form method="POST" action="{{ route('admin.settings.penalties.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Penalty per Day (₱)</label>
                                <input type="number" step="0.01" name="penalty_per_day" class="form-control form-control-glass" value="{{ old('penalty_per_day', $penaltySettings['penalty_per_day']) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Grace Period (days)</label>
                                <input type="number" name="grace_period_days" class="form-control form-control-glass" value="{{ old('grace_period_days', $penaltySettings['grace_period_days']) }}" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted">Maximum Penalty Cap (₱)</label>
                                <input type="number" step="0.01" name="max_penalty" class="form-control form-control-glass" value="{{ old('max_penalty', $penaltySettings['max_penalty']) }}" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn-glass-primary">Save Penalty Policy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CONFIRMATION MODAL PARA SA SYSTEM OPERATIONAL STATE --}}
<div class="modal fade glass-modal" id="confirmStateModal" tabindex="-1" aria-labelledby="confirmStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-body pt-4">
                <div class="modal-icon-badge warning mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Change Operational State?</h4>
                <p class="text-muted small mb-0 px-3">
                    Are you sure you want to update the system operational state? This will immediately affect access for all active borrowers.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-glass-primary" id="confirmStateSubmitBtn">
                    Yes, Update State
                </button>
            </div>
        </div>
    </div>
</div>

{{-- CONFIRMATION MODAL PARA SA PERSONAL DETAILS --}}
<div class="modal fade glass-modal" id="confirmPersonalModal" tabindex="-1" aria-labelledby="confirmPersonalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-body pt-4">
                <div class="modal-icon-badge warning mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Save Personal Details?</h4>
                <p class="text-muted small mb-0 px-3">
                    Are you sure you want to update your personal information and profile verification details?
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-glass-primary" id="confirmPersonalSubmitBtn">
                    Yes, Save Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showSettingsCategory(catId, btn) {
        document.querySelectorAll('.settings-content-panel').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.settings-nav-item').forEach(el => el.classList.remove('active'));
        document.getElementById(catId).style.display = 'block';
        if (btn) btn.classList.add('active');
    }

    function switchAdminTab(catId, btn) {
        showSettingsCategory(catId, btn);
        localStorage.setItem('admin_settings_active_tab', catId);
    }

    // Eye Toggle Script
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Live Password Requirement Checker Script para sa Admin
    const adminNewPassInput = document.getElementById('adminNewPassword');
    if (adminNewPassInput) {
        adminNewPassInput.addEventListener('input', function () {
            const val = this.value;
            function updateAdminRule(ruleId, iconId, isValid) {
                const ruleEl = document.getElementById(ruleId);
                const iconEl = document.getElementById(iconId);
                if (isValid) {
                    ruleEl.className = 'text-success fw-medium transition-all d-flex align-items-center gap-2';
                    iconEl.className = 'bi bi-check-circle-fill text-success';
                } else {
                    ruleEl.className = 'text-secondary transition-all d-flex align-items-center gap-2';
                    iconEl.className = 'bi bi-circle text-secondary';
                }
            }
            const hasLength  = val.length >= 8;
            const hasUpper   = /[A-Z]/.test(val);
            const hasLower   = /[a-z]/.test(val);
            const hasNumber  = /[0-9]/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);

            updateAdminRule('adminRuleLength',  'adminIconLength',  hasLength);
            updateAdminRule('adminRuleUpper',   'adminIconUpper',   hasUpper);
            updateAdminRule('adminRuleLower',   'adminIconLower',   hasLower);
            updateAdminRule('adminRuleNumber',  'adminIconNumber',  hasNumber);
            updateAdminRule('adminRuleSpecial', 'adminIconSpecial', hasSpecial);

            const allValid = hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
            const reqBox = document.getElementById('adminReqBox');
            const shieldIcon = document.getElementById('adminShieldHeaderIcon');

            if (allValid) {
                reqBox.style.backgroundColor = 'rgba(16, 185, 129, 0.08)';
                reqBox.style.borderColor = 'rgba(16, 185, 129, 0.2)';
                shieldIcon.className = 'bi bi-shield-check text-success';
            } else {
                reqBox.style.backgroundColor = 'rgba(100, 116, 139, 0.05)';
                reqBox.style.borderColor = 'var(--dash-card-border, rgba(255, 255, 255, 0.08))';
                shieldIcon.className = 'bi bi-shield text-secondary';
            }
        });
    }

    // Custom Glassmorphism Dropdown Script with Smart Dropup/Dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const alertBox = document.getElementById('autoDismissAlert');
        if (alertBox) {
            setTimeout(function() {
                alertBox.classList.add('fade-out');
                setTimeout(function() {
                    alertBox.remove();
                }, 500);
            }, 3500);
        }

        // Generic Custom Dropdown Handler with Smart Position Detection
        document.querySelectorAll('.custom-dropdown-container').forEach(container => {
            const toggle = container.querySelector('.custom-dropdown-toggle');
            const selectedText = container.querySelector('.selected-text');
            const items = container.querySelectorAll('.custom-dropdown-item');
            const hiddenInput = container.previousElementSibling;

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Suriin kung malapit sa ibaba ng screen para kusa itong mag-dropup
                const rect = container.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                if (spaceBelow < 220) {
                    container.classList.add('dropup');
                } else {
                    container.classList.remove('dropup');
                }

                // Isara ang iba pang nakabukas na dropdowns
                document.querySelectorAll('.custom-dropdown-container').forEach(c => {
                    if (c !== container) c.classList.remove('show', 'dropup');
                });
                container.classList.toggle('show');
            });

            items.forEach(item => {
                item.addEventListener('click', () => {
                    items.forEach(i => i.classList.remove('selected'));
                    item.classList.add('selected');
                    
                    const val = item.getAttribute('data-value');
                    selectedText.textContent = val;
                    if (hiddenInput && hiddenInput.tagName === 'INPUT') {
                        hiddenInput.value = val;
                    }
                    container.classList.remove('show', 'dropup');
                });
            });
        });

        // Isara ang mga dropdowns kapag nag-click sa labas
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-dropdown-container').forEach(c => c.classList.remove('show', 'dropup'));
        });

        // Custom Dropdown Item Click Handler para sa Maintenance State
        document.querySelectorAll('.state-option').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const val = this.getAttribute('data-value');
                const label = this.getAttribute('data-label');

                document.getElementById('selected_maintenance_mode').value = val;
                document.getElementById('selectedStateLabel').innerText = label;

                document.querySelectorAll('.state-option').forEach(opt => {
                    opt.classList.remove('active');
                    opt.querySelector('.check-icon').classList.add('d-none');
                });

                this.classList.add('active');
                this.querySelector('.check-icon').classList.remove('d-none');
            });
        });

        // Submit form upon confirming modal button click (System State)
        const confirmBtn = document.getElementById('confirmStateSubmitBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                document.getElementById('systemStateForm').submit();
            });
        }

        // Submit form upon confirming modal button click (Personal Details)
        const confirmPersonalBtn = document.getElementById('confirmPersonalSubmitBtn');
        if (confirmPersonalBtn) {
            confirmPersonalBtn.addEventListener('click', function() {
                document.getElementById('personalDetailsForm').submit();
            });
        }

        @if ($errors->any())
            @if ($errors->has('current_password') || $errors->has('password') || $errors->has('name') || $errors->has('phone') || $errors->has('address') || $errors->has('dob'))
                switchAdminTab('accountCat', document.querySelector('[onclick*="accountCat"]'));
            @elseif ($errors->has('otp_expiry_minutes') || $errors->has('otp_max_attempts') || $errors->has('otp_resend_cooldown_seconds') || $errors->has('otp_free_resend_attempts'))
                switchAdminTab('authCat', document.querySelector('[onclick*="authCat"]'));
            @elseif ($errors->has('maintenance_mode'))
                switchAdminTab('maintenanceCat', document.querySelector('[onclick*="maintenanceCat"]'));
            @elseif ($errors->has('loan_types.*'))
                switchAdminTab('loanDefaultsCat', document.querySelector('[onclick*="loanDefaultsCat"]'));
            @elseif ($errors->has('penalty_per_day') || $errors->has('grace_period_days') || $errors->has('max_penalty'))
                switchAdminTab('penaltyCat', document.querySelector('[onclick*="penaltyCat"]'));
            @endif
        @else
            const savedAdminTab = localStorage.getItem('admin_settings_active_tab');
            if (savedAdminTab && document.getElementById(savedAdminTab)) {
                const btn = document.querySelector(`[onclick*="${savedAdminTab}"]`);
                if (btn) {
                    showSettingsCategory(savedAdminTab, btn);
                }
            }
        @endif
    });
</script>
@endsection