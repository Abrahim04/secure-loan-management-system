@extends('layouts.app')

@section('title', 'Borrower Verification Form')

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

    /* Back Button Style */
    .glass-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.1);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12));
        color: var(--text-main, #f8fafc);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .glass-btn-back:hover {
        background: rgba(100, 116, 139, 0.2);
        color: var(--text-main, #ffffff);
        transform: translateX(-3px);
    }

    /* Ocean Blue Action Button (#0284c7) */
    .btn-submit-ocean {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(2, 132, 199, 0.12) !important;
        border: 1px solid rgba(2, 132, 199, 0.3) !important;
        color: #0284c7 !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-submit-ocean:hover {
        background: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }

    /* Theme-Aware Form Inputs (Hindi magiging black sa Dark Mode) */
    .glass-input {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px !important;
        padding: 9px 14px !important;
        font-size: 0.875rem !important;
        transition: all 0.2s ease !important;
    }
    .glass-input:focus {
        background-color: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        outline: none;
    }

    /* Custom File Input Styling */
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

    /* Custom Glassmorphism Dropdown Container */
    .custom-dropdown-container {
        position: relative;
        width: 100%;
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

    /* Custom Glassmorphism Dropdown Menu (Matched with payments.blade) */
    .custom-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.95)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        z-index: 1050;
        display: none;
        max-height: 220px;
        overflow-y: auto;
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
        text-decoration: none;
    }
    .btn-modal-cancel:hover {
        background: rgba(100, 116, 139, 0.3) !important;
        color: var(--text-main, #f8fafc) !important;
        border-color: rgba(100, 116, 139, 0.5) !important;
        transform: translateY(-1px);
    }
    .btn-pill-outline {
        background: rgba(2, 132, 199, 0.12);
        border: 1px solid rgba(2, 132, 199, 0.3);
        color: #0284c7;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 8px 20px;
        border-radius: 50px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-pill-outline:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Back Action Button --}}
    <div class="mb-3">
        <a href="{{ route('profile.show') }}" class="glass-btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Back to Profile</span>
        </a>
    </div>

    {{-- Main Verification Card --}}
    <div class="dash-card p-4 p-md-5">
        <div class="mb-4">
            <h3 class="fw-bold text-main mb-1">Borrower Verification (KYC)</h3>
            <p class="text-muted small mb-0">Please complete your personal, employment, and ID details to apply for loans.</p>
        </div>

        <form action="{{ route('profile.verify') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
            @csrf

            <div class="row g-3">
                {{-- Date of Birth --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Date of Birth</label>
                    <input type="date" name="dob" class="form-control glass-input" required value="{{ old('dob') }}">
                </div>

                {{-- Custom Gender Dropdown --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Gender</label>
                    <input type="hidden" name="gender" id="gender_input" value="{{ old('gender') }}" required>
                    <div class="custom-dropdown-container" id="genderDropdown">
                        <div class="custom-dropdown-toggle">
                            <span class="selected-text">{{ old('gender') ? old('gender') : 'Select Gender' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="custom-dropdown-menu">
                            <div class="custom-dropdown-item {{ old('gender') == 'Male' ? 'selected' : '' }}" data-value="Male">Male</div>
                            <div class="custom-dropdown-item {{ old('gender') == 'Female' ? 'selected' : '' }}" data-value="Female">Female</div>
                            <div class="custom-dropdown-item {{ old('gender') == 'Other' ? 'selected' : '' }}" data-value="Other">Other</div>
                        </div>
                    </div>
                </div>

                {{-- Nationality --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Nationality</label>
                    <input type="text" name="nationality" class="form-control glass-input" value="{{ old('nationality', 'Filipino') }}" required>
                </div>

                {{-- Phone Number --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Phone Number</label>
                    <input type="text" name="phone" class="form-control glass-input" placeholder="09123456789" value="{{ old('phone') }}" required>
                </div>

                {{-- Address --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Address</label>
                    <input type="text" name="address" class="form-control glass-input" placeholder="House No., Street, Barangay, City" value="{{ old('address') }}" required>
                </div>

                {{-- Custom ID Type Dropdown --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">ID Type</label>
                    <input type="hidden" name="id_type" id="id_type_input" value="{{ old('id_type') }}" required>
                    <div class="custom-dropdown-container" id="idTypeDropdown">
                        <div class="custom-dropdown-toggle">
                            <span class="selected-text">{{ old('id_type') ? old('id_type') : 'Select Valid ID' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="custom-dropdown-menu">
                            <div class="custom-dropdown-item {{ old('id_type') == 'PhilSys National ID' ? 'selected' : '' }}" data-value="PhilSys National ID">PhilSys National ID</div>
                            <div class="custom-dropdown-item {{ old('id_type') == "Driver's License" ? 'selected' : '' }}" data-value="Driver's License">Driver's License</div>
                            <div class="custom-dropdown-item {{ old('id_type') == 'UMID Card' ? 'selected' : '' }}" data-value="UMID Card">UMID Card</div>
                            <div class="custom-dropdown-item {{ old('id_type') == 'SSS ID' ? 'selected' : '' }}" data-value="SSS ID">SSS ID</div>
                            <div class="custom-dropdown-item {{ old('id_type') == 'Postal ID' ? 'selected' : '' }}" data-value="Postal ID">Postal ID</div>
                            <div class="custom-dropdown-item {{ old('id_type') == 'Student ID' ? 'selected' : '' }}" data-value="Student ID">Student ID</div>
                        </div>
                    </div>
                </div>

                {{-- ID Number --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">ID Number</label>
                    <input type="text" name="id_number" class="form-control glass-input" placeholder="Enter ID Number" required value="{{ old('id_number') }}">
                </div>

                {{-- Upload ID Image --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Upload ID Image</label>
                    <input type="file" name="id_image" class="form-control glass-file-input" accept=".jpg,.jpeg,.png" required>
                </div>

                {{-- Custom Employment Status Dropdown --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Employment Status</label>
                    <input type="hidden" name="employment_status" id="employment_status_input" value="{{ old('employment_status') }}" required>
                    <div class="custom-dropdown-container" id="empStatusDropdown">
                        <div class="custom-dropdown-toggle">
                            <span class="selected-text">{{ old('employment_status') ? old('employment_status') : 'Select Status' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="custom-dropdown-menu">
                            <div class="custom-dropdown-item {{ old('employment_status') == 'Employed' ? 'selected' : '' }}" data-value="Employed">Employed</div>
                            <div class="custom-dropdown-item {{ old('employment_status') == 'Unemployed' ? 'selected' : '' }}" data-value="Unemployed">Unemployed</div>
                            <div class="custom-dropdown-item {{ old('employment_status') == 'Student' ? 'selected' : '' }}" data-value="Student">Student</div>
                        </div>
                    </div>
                </div>

                {{-- Occupation / Business --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Occupation / Business</label>
                    <input type="text" name="occupation" class="form-control glass-input" placeholder="e.g. IT Professional / N/A" value="{{ old('occupation') }}">
                </div>

                {{-- Job Position --}}
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Job Position</label>
                    <input type="text" name="job_position" class="form-control glass-input" placeholder="e.g. Developer / N/A" value="{{ old('job_position') }}">
                </div>

                {{-- Custom Monthly Income Dropdown --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Monthly Income Range</label>
                    <input type="hidden" name="monthly_income" id="monthly_income_input" value="{{ old('monthly_income') }}" required>
                    <div class="custom-dropdown-container" id="incomeDropdown">
                        <div class="custom-dropdown-toggle">
                            <span class="selected-text">{{ old('monthly_income') ? old('monthly_income') : 'Select Range' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                        <div class="custom-dropdown-menu">
                            <div class="custom-dropdown-item {{ old('monthly_income') == 'Below ₱10,000' ? 'selected' : '' }}" data-value="Below ₱10,000">Below ₱10,000</div>
                            <div class="custom-dropdown-item {{ old('monthly_income') == '₱10,000 - ₱25,000' ? 'selected' : '' }}" data-value="₱10,000 - ₱25,000">₱10,000 - ₱25,000</div>
                            <div class="custom-dropdown-item {{ old('monthly_income') == '₱25,000 - ₱50,000' ? 'selected' : '' }}" data-value="₱25,000 - ₱50,000">₱25,000 - ₱50,000</div>
                            <div class="custom-dropdown-item {{ old('monthly_income') == 'Above ₱50,000' ? 'selected' : '' }}" data-value="Above ₱50,000">Above ₱50,000</div>
                        </div>
                    </div>
                </div>

                {{-- Source of Income --}}
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Source of Income</label>
                    <input type="text" name="income_source" class="form-control glass-input" placeholder="Salary / Allowance / Business" value="{{ old('income_source') }}">
                </div>

                {{-- Action Submit Button (Triggers Confirmation Modal) --}}
                <div class="col-12 mt-4 text-end">
                    <button type="button" class="btn-submit-ocean" data-bs-toggle="modal" data-bs-target="#confirmVerificationModal">
                        Submit Verification Details
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Confirmation Modal para sa Borrower Verification --}}
<div class="modal fade glass-modal" id="confirmVerificationModal" tabindex="-1" aria-labelledby="confirmVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-body pt-4">
                <div class="modal-icon-badge warning mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h4 class="fw-bold text-main mb-2">Submit Verification Details?</h4>
                <p class="text-muted small mb-0 px-3">
                    Are you sure you want to submit your KYC verification details for admin review? Please ensure all information is correct.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-pill-outline" id="confirmVerificationSubmitBtn">
                    Yes, Submit Verification
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Generic Custom Dropdown Handler
        document.querySelectorAll('.custom-dropdown-container').forEach(container => {
            const toggle = container.querySelector('.custom-dropdown-toggle');
            const selectedText = container.querySelector('.selected-text');
            const items = container.querySelectorAll('.custom-dropdown-item');
            const hiddenInput = container.previousElementSibling;

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                // Close other open dropdowns
                document.querySelectorAll('.custom-dropdown-container').forEach(c => {
                    if (c !== container) c.classList.remove('show');
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
                    container.classList.remove('show');
                });
            });
        });

        // Close dropdowns on outside click
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-dropdown-container').forEach(c => c.classList.remove('show'));
        });

        // Submit form upon confirming modal button click
        const confirmBtn = document.getElementById('confirmVerificationSubmitBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                document.getElementById('verificationForm').submit();
            });
        }
    });
</script>
@endpush
@endsection