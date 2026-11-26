@extends('layouts.app')

@section('title', 'Verification Management')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main, #f8fafc) !important;
        background: transparent !important;
        --bs-table-bg: transparent !important;
        --bs-table-color: var(--text-main, #f8fafc) !important;
    }
    .custom-table thead th {
        background: rgba(255, 255, 255, 0.03) !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        color: var(--text-muted, #94a3b8) !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        padding: 12px 16px !important;
        vertical-align: middle;
    }
    .custom-table tbody tr {
        transition: background-color 0.15s ease;
        background: transparent !important;
        cursor: pointer;
    }
    .custom-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    .custom-table tbody td {
        padding: 13px 16px !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05)) !important;
        color: var(--text-main, #f8fafc) !important;
        font-size: 0.875rem;
        background: transparent !important;
        vertical-align: middle;
    }

    .badge-soft {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0;
        background: transparent !important;
        border: none !important;
        display: inline-block;
    }
    .badge-soft-success { color: #34d399 !important; }
    .badge-soft-danger  { color: #f87171 !important; }
    .badge-soft-warning { color: #fbbf24 !important; }

    .btn-tbl-action {
        font-size: 0.785rem;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 50px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .btn-tbl-activate {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34d399 !important;
        border: 1px solid rgba(16, 185, 129, 0.25) !important;
    }
    .btn-tbl-activate:hover {
        background-color: #10b981 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(16, 185, 129, 0.3);
    }
    .btn-tbl-delete {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #f87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.25) !important;
    }
    .btn-tbl-delete:hover {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.3);
    }

    /* Glassmorphism Modals & Confirmation Buttons */
    .glass-modal .modal-content {
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.95)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 20px;
        box-shadow: var(--dash-card-shadow, 0 20px 40px rgba(0, 0, 0, 0.4));
        color: var(--text-main, #f8fafc);
    }
    .glass-modal .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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
    .modal-icon-badge.success {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }
    .modal-icon-badge.danger {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }

    .btn-approve-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-approve-glass:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transform: translateY(-1px);
    }

    .btn-reject-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .btn-reject-glass:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
        transform: translateY(-1px);
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

    /* Auto-Dismissing Custom Glass Alerts (Consistent with Settings page) */
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
    .glass-alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }
    .glass-alert.fade-out {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">Verification Management</h2>
            <p class="text-muted small mb-0">Review borrower KYC details, uploaded IDs, and approve or reject verification applications. Click any row to view details.</p>
        </div>
    </div>

    {{-- Auto-Dismissing Flash Alerts (Consistent Style) --}}
    @if (session('status'))
        <div id="autoDismissAlert" class="glass-alert glass-alert-success mb-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div id="autoDismissAlert" class="glass-alert glass-alert-danger mb-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Verification Table Card --}}
    <div class="dash-card p-0 overflow-hidden mb-4">
        @if ($users->isEmpty())
            <div class="p-5 text-center">
                <h6 class="fw-semibold text-main mb-1">No Verification Requests Found</h6>
                <p class="text-muted small mb-0">All borrower accounts are up to date and fully reviewed.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Borrower Details</th>
                            <th>ID Type & Number</th>
                            <th>Employment & Income</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Submitted Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($users as $user)
                            <tr onclick="openVerificationDetails({{ $user->id }})" title="Click to review KYC details">
                                <td class="ps-4">
                                    <div class="fw-semibold text-main">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }} / {{$user->phone ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-main">{{ $user->id_type ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $user->id_number ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-main">{{ $user->employment_status ?? 'N/A' }}</div>
                                    <div class="text-muted small">{{ $user->monthly_income ?? 'N/A' }}</div>
                                </td>
                                <td class="text-center">
                                    @if ($user->verification_status === 'verified')
                                        <span class="badge-soft badge-soft-success">Verified</span>
                                    @elseif ($user->verification_status === 'pending')
                                        <span class="badge-soft badge-soft-warning">Pending Review</span>
                                    @else
                                        <span class="badge-soft badge-soft-danger">Unverified</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted small">{{ $user->updated_at->format('M d, Y h:i A') }}</td>
                                <td class="text-center" onclick="event.stopPropagation()">
                                    <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                        @if ($user->verification_status === 'pending')
                                            <!-- Approve Button (Triggers Approve Modal) -->
                                            <button type="button" class="btn-tbl-action btn-tbl-activate" data-bs-toggle="modal" data-bs-target="#approveModal{{ $user->id }}">
                                                Approve
                                            </button>

                                            <!-- Reject Button (Triggers Reject Modal) -->
                                            <button type="button" class="btn-tbl-action btn-tbl-delete" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $user->id }}">
                                                Reject
                                            </button>
                                        @else
                                            <span class="text-muted small">Reviewed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- APPROVE & REJECT VERIFICATION MODALS --}}
@foreach ($users as $user)
    @if ($user->verification_status === 'pending')
        <div class="modal fade glass-modal" id="approveModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-3">
                    <div class="modal-body pt-4">
                        <div class="modal-icon-badge success mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <h4 class="fw-bold text-main mb-2">Approve Verification?</h4>
                        <p class="text-muted small mb-0 px-3">
                            Are you sure you want to approve the account verification for borrower <strong>{{ $user->name }}</strong>? This will allow them to apply for loans.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.verifications.approve', $user) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn-approve-glass">
                                Yes, Approve Verification
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade glass-modal" id="rejectModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 text-center">
                    <div class="modal-body pt-4">
                        <div class="modal-icon-badge danger mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        </div>
                        <h4 class="fw-bold text-main mb-2">Reject Verification?</h4>
                        <p class="text-muted small mb-0 px-3">
                            Are you sure you want to reject the verification request for borrower <strong>{{ $user->name }}</strong>? Their status will return to unverified.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-3 gap-2">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.verifications.reject', $user) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn-reject-glass">
                                Yes, Reject Verification
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Verification Details Modal -->
<div class="modal fade glass-modal" id="verificationDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-main">KYC Verification Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3" id="verificationDetailsBody">
                <div class="text-center py-4 text-muted">Loading details...</div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openVerificationDetails(userId) {
        const modal = new bootstrap.Modal(document.getElementById('verificationDetailsModal'));
        modal.show();

        const container = document.getElementById('verificationDetailsBody');
        container.innerHTML = '<div class="text-center py-4 text-muted">Loading details...</div>';

        fetch(`/admin/users/${userId}/details`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-6"><strong class="text-muted small d-block">Full Name</strong><span class="text-main">${data.name}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Email</strong><span class="text-main">${data.email}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Phone</strong><span class="text-main">${data.phone}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Date of Birth</strong><span class="text-main">${data.dob}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Gender & Nationality</strong><span class="text-main">${data.gender} / ${data.nationality}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Address</strong><span class="text-main">${data.address}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">ID Type & Number</strong><span class="text-main">${data.id_type} - ${data.id_number}</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Employment Status</strong><span class="text-main">${data.employment_status} (${data.occupation || 'N/A'})</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Monthly Income</strong><span class="text-main">${data.monthly_income} (${data.income_source || 'N/A'})</span></div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Verification Status</strong><span class="text-main">${data.verification_status}</span></div>
                        ${data.id_image_url ? `<div class="col-12 mt-3"><strong class="text-muted small d-block mb-2">Uploaded Valid ID</strong><a href="${data.id_image_url}" target="_blank"><img src="${data.id_image_url}" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: contain;"></a></div>` : ''}
                    </div>
                `;
            })
            .catch(err => {
                container.innerHTML = '<div class="text-center py-4 text-danger">Failed to load verification details.</div>';
            });
    }

    // Auto-Dismissing Flash Alert Script (Consistent with Settings page)
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
    });
</script>
@endpush
@endsection