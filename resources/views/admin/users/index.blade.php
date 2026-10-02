@extends('layouts.app')

@section('title', $showingTrash ? 'Archived Borrowers' : 'Borrower Management')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>
    /* Dash Card Container */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Filters Container - Higher z-index to allow dropdown overlay */
    .filter-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        position: relative;
        z-index: 1050 !important;
    }

    /* Custom Form Control Styling */
    .glass-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .glass-input:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }
    .glass-input::placeholder {
        color: var(--text-muted, #94a3b8);
        opacity: 0.7;
    }

    /* Custom Select Button and Dropdown Menu Fixes */
    .custom-select-btn {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.875rem;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-select-btn:hover, .custom-select-btn:focus {
        border-color: #0284c7 !important;
        outline: none;
    }

    .custom-select-menu {
        width: 100% !important;
        background-color: var(--dash-card-bg, #1e293b) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.15)) !important;
        border-radius: 10px;
        padding: 6px;
        margin-top: 6px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35) !important;
        z-index: 9999 !important;
        position: absolute !important;
    }

    .custom-select-item {
        color: var(--text-main, #f8fafc) !important;
        padding: 9px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .custom-select-item:hover, .custom-select-item.active {
        background-color: #0284c7 !important;
        color: #ffffff !important;
    }

    /* Action Buttons Header */
    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #0284c7;
        border: 1px solid #0369a1;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-primary:hover {
        background: #0369a1;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    .btn-action-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12));
        color: var(--text-main, #f8fafc) !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 16px;
        border-radius: 10px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-secondary:hover {
        background: rgba(255, 255, 255, 0.12);
        color: var(--text-main, #ffffff) !important;
        transform: translateY(-1px);
    }

    /* Action Table Buttons */
    .btn-tbl-action {
        font-size: 0.785rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 8px;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .btn-tbl-edit {
        background-color: rgba(148, 163, 184, 0.12) !important;
        color: var(--text-main, #e2e8f0) !important;
        border: 1px solid rgba(148, 163, 184, 0.2) !important;
    }
    .btn-tbl-edit:hover {
        background-color: rgba(148, 163, 184, 0.25) !important;
        border-color: rgba(148, 163, 184, 0.4) !important;
        transform: translateY(-1px);
    }

    .btn-tbl-block {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #fbbf24 !important;
        border: 1px solid rgba(245, 158, 11, 0.25) !important;
    }
    .btn-tbl-block:hover {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(245, 158, 11, 0.3);
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

    /* Table Styling Header & Rows */
    .custom-table {
        color: var(--text-main, #f8fafc);
        vertical-align: middle;
        margin-bottom: 0;
        background: transparent !important;
    }
    .custom-table thead th {
        background: rgba(255, 255, 255, 0.03) !important;
        color: var(--text-muted, #94a3b8);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08));
        padding: 12px 16px;
    }
    .custom-table tbody tr {
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05));
        transition: background-color 0.15s ease;
    }
    .custom-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    .custom-table tbody td {
        padding: 13px 16px;
        font-size: 0.875rem;
        background: transparent !important;
    }

    /* Text Badges */
    .badge-soft {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0;
        background: transparent !important;
        border: none !important;
        display: inline-block;
    }
    .badge-soft-success { color: #34d399; }
    .badge-soft-danger  { color: #f87171; }
    .badge-soft-warning { color: #fbbf24; }

    /* User Avatar Circle */
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(2, 132, 199, 0.15);
        color: #38bdf8;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Modal Responsive Styles */
    .theme-modal .modal-content {
        background-color: var(--dash-card-bg, rgba(30, 41, 59, 0.95)) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(20px);
    }

    .theme-modal .modal-header,
    .theme-modal .modal-body,
    .theme-modal .modal-footer {
        background-color: transparent !important;
    }

    .theme-modal label {
        color: var(--text-muted, #94a3b8) !important;
        font-weight: 600 !important;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .theme-modal .form-control {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.875rem;
    }

    .theme-modal .form-control:focus {
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    }

    /* SweetAlert Glassmorphism Integration */
    .swal2-glass-modal {
        border-radius: 18px !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4) !important;
        padding: 1.75rem 1.5rem !important;
    }

    .swal2-glass-title {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        padding-top: 0.5rem !important;
    }

    .swal2-glass-text {
        font-size: 0.9rem !important;
        opacity: 0.8;
    }

    .swal2-glass-actions {
        gap: 10px !important;
        margin-top: 1.25rem !important;
    }

    .swal2-btn-confirm {
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 9px 20px !important;
        border-radius: 10px !important;
        border: none !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .swal2-btn-confirm:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .swal2-btn-cancel {
        background: rgba(148, 163, 184, 0.15) !important;
        color: var(--text-main, #f8fafc) !important;
        border: 1px solid rgba(148, 163, 184, 0.25) !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        padding: 9px 20px !important;
        border-radius: 10px !important;
        transition: all 0.2s ease !important;
    }
    .swal2-btn-cancel:hover {
        background: rgba(148, 163, 184, 0.25) !important;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">{{ $showingTrash ? 'Archived Borrowers' : 'Borrower Management' }}</h2>
            <p class="text-muted small mb-0">
                {{ $showingTrash ? 'Manage deleted borrower accounts and restore them if needed.' : 'View, edit, and manage registered borrower accounts and permissions.' }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index', $showingTrash ? [] : ['trashed' => 1]) }}" class="btn-action-secondary">
                @if ($showingTrash)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>View Active Borrowers</span>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>View Trash / Archived</span>
                @endif
            </a>
            @unless ($showingTrash)
                <button type="button" class="btn-action-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Add Borrower</span>
                </button>
            @endunless
        </div>
    </div>

    {{-- Session Alerts --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filters Card --}}
    @unless ($showingTrash)
        <div class="filter-card p-3 mb-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search" class="form-control glass-input" style="padding-left: 40px;" placeholder="Search by name or email..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Custom Filter Dropdown --}}
                <div class="col-md-4">
                    <input type="hidden" name="status" id="filter_status_input" value="{{ request('status') }}">
                    <div class="dropdown position-relative">
                        <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                            <span id="filter_status_text">
                                @if(request('status') === 'active')
                                    Active Only
                                @elseif(request('status') === 'blocked')
                                    Blocked Only
                                @else
                                    All Statuses
                                @endif
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <ul class="dropdown-menu custom-select-menu shadow-lg">
                            <li><a class="dropdown-item custom-select-item" href="#" onclick="selectOption('filter_status_input', 'filter_status_text', '', 'All Statuses'); return false;">All Statuses</a></li>
                            <li><a class="dropdown-item custom-select-item" href="#" onclick="selectOption('filter_status_input', 'filter_status_text', 'active', 'Active Only'); return false;">Active Only</a></li>
                            <li><a class="dropdown-item custom-select-item" href="#" onclick="selectOption('filter_status_input', 'filter_status_text', 'blocked', 'Blocked Only'); return false;">Blocked Only</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn-action-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>Filter Results</span>
                    </button>
                </div>
            </form>
        </div>
    @endunless

    {{-- Borrower Table Card (Tinanggal ang overflow-hidden dito) --}}
    <div class="dash-card mb-4">
        @if ($users->isEmpty())
            <div class="p-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3 opacity-50"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <h6 class="fw-semibold text-main mb-1">{{ $showingTrash ? 'No Archived Borrowers' : 'No Borrowers Found' }}</h6>
                <p class="text-muted small mb-0">Try adjusting your filter search criteria or add a new borrower.</p>
            </div>
        @else
            <div class="table-responsive" style="overflow-x: auto; overflow-y: visible;">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Borrower Details</th>
                            @unless ($showingTrash)
                                <th class="text-center">Loans</th>
                                <th class="text-center">Payments</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Verification</th>
                                <th class="text-center">Joined</th>
                            @else
                                <th>Deleted At</th>
                            @endunless
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-main">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                @unless ($showingTrash)
                                    <td class="text-center fw-semibold text-main">{{ $user->loans_count }}</td>
                                    <td class="text-center fw-semibold text-main">{{ $user->payments_count }}</td>
                                    <td class="text-center">
                                        @if ($user->is_active)
                                            <span class="badge-soft badge-soft-success">Active</span>
                                        @else
                                            <span class="badge-soft badge-soft-danger">Blocked</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->email_verified_at)
                                            <span class="badge-soft badge-soft-success">Verified</span>
                                        @else
                                            <span class="badge-soft badge-soft-warning">Unverified</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                            <button type="button" class="btn-tbl-action btn-tbl-edit" style="min-width: 58px;" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                Edit
                                            </button>

                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                @if ($user->is_active)
                                                    <button type="submit" class="btn-tbl-action btn-tbl-block" style="min-width: 62px;" onclick="confirmAction(event, 'Block Borrower?', 'Borrower {{ $user->name }} will be unable to log in.', 'Yes, Block', '#f59e0b')">
                                                        Block
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn-tbl-action btn-tbl-activate" style="min-width: 68px;" onclick="confirmAction(event, 'Reactivate Borrower?', 'Allow {{ $user->name }} to log in and access services again.', 'Yes, Activate', '#10b981')">
                                                        Activate
                                                    </button>
                                                @endif
                                            </form>

                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-tbl-action btn-tbl-delete" style="min-width: 62px;" onclick="confirmAction(event, 'Move to Trash?', 'Borrower {{ $user->name }} will be archived and unable to log in.', 'Yes, Delete', '#ef4444')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @else
                                    <td class="text-muted small">{{ $user->deleted_at->format('M d, Y h:i A') }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-tbl-action btn-tbl-activate" onclick="confirmAction(event, 'Restore Borrower?', 'Restore borrower {{ $user->name }} back to active list.', 'Yes, Restore', '#10b981')">
                                                    Restore
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-tbl-action btn-tbl-delete" onclick="confirmAction(event, 'Permanently Delete?', 'Are you sure you want to delete {{ $user->name }}? This action cannot be undone.', 'Yes, Delete Permanently', '#ef4444')">
                                                    Permanently Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endunless
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagination Links Container --}}
    @if (!$users->isEmpty())
        <div class="d-flex justify-content-end">
            {{ $users->links() }}
        </div>
    @endif
</div>

{{-- Edit Modals --}}
@unless ($showingTrash)
    @foreach ($users as $user)
        <div class="modal fade theme-modal" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold text-main">Edit Borrower Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-3">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>

                            <!-- Adaptive Custom Role Dropdown -->
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <input type="hidden" name="role" id="role_input_{{ $user->id }}" value="{{ $user->role }}">
                                <div class="dropdown position-relative">
                                    <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="role_text_{{ $user->id }}">{{ $user->role === 'admin' ? 'Admin' : 'Borrower' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <ul class="dropdown-menu custom-select-menu">
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('role_input_{{ $user->id }}', 'role_text_{{ $user->id }}', 'user', 'Borrower')">Borrower</a></li>
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('role_input_{{ $user->id }}', 'role_text_{{ $user->id }}', 'admin', 'Admin')">Admin</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Adaptive Custom Account Status Dropdown -->
                            <div class="mb-3">
                                <label class="form-label">Account Status</label>
                                <input type="hidden" name="status" id="status_input_{{ $user->id }}" value="{{ $user->is_active ? 'active' : 'blocked' }}">
                                <div class="dropdown position-relative">
                                    <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="status_text_{{ $user->id }}">{{ $user->is_active ? 'Active' : 'Blocked' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <ul class="dropdown-menu custom-select-menu">
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('status_input_{{ $user->id }}', 'status_text_{{ $user->id }}', 'active', 'Active')">Active</a></li>
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('status_input_{{ $user->id }}', 'status_text_{{ $user->id }}', 'blocked', 'Blocked')">Blocked</a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Adaptive Custom Verification Status Dropdown -->
                            <div class="mb-3">
                                <label class="form-label">Verification Status</label>
                                <input type="hidden" name="verification_status" id="verif_input_{{ $user->id }}" value="{{ $user->email_verified_at ? 'verified' : 'unverified' }}">
                                <div class="dropdown position-relative">
                                    <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="verif_text_{{ $user->id }}">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <ul class="dropdown-menu custom-select-menu">
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('verif_input_{{ $user->id }}', 'verif_text_{{ $user->id }}', 'verified', 'Verified')">Verified</a></li>
                                        <li><a class="dropdown-item custom-select-item" onclick="selectOption('verif_input_{{ $user->id }}', 'verif_text_{{ $user->id }}', 'unverified', 'Unverified')">Unverified</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn-action-secondary py-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-action-primary py-2 px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endunless

{{-- Add Borrower Modal --}}
<div class="modal fade theme-modal" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-main">Add New Borrower</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 small mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Juan Dela Cruz">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="juan@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <!-- Adaptive Custom Add Role Dropdown -->
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="hidden" name="role" id="add_role_input" value="user">
                        <div class="dropdown position-relative">
                            <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="add_role_text">Borrower</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <ul class="dropdown-menu custom-select-menu">
                                <li><a class="dropdown-item custom-select-item" onclick="selectOption('add_role_input', 'add_role_text', 'user', 'Borrower')">Borrower</a></li>
                                <li><a class="dropdown-item custom-select-item" onclick="selectOption('add_role_input', 'add_role_text', 'admin', 'Admin')">Admin</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Adaptive Custom Add Verification Dropdown -->
                    <div class="mb-3">
                        <label class="form-label">Initial Verification Status</label>
                        <input type="hidden" name="verification_status" id="add_verif_input" value="unverified">
                        <div class="dropdown position-relative">
                            <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="add_verif_text">Unverified</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <ul class="dropdown-menu custom-select-menu">
                                <li><a class="dropdown-item custom-select-item" onclick="selectOption('add_verif_input', 'add_verif_text', 'unverified', 'Unverified')">Unverified</a></li>
                                <li><a class="dropdown-item custom-select-item" onclick="selectOption('add_verif_input', 'add_verif_text', 'verified', 'Verified')">Verified</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn-action-secondary py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-action-primary py-2 px-4">Create Borrower</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function selectOption(inputId, textId, value, label) {
        document.getElementById(inputId).value = value;
        document.getElementById(textId).innerText = label;
    }

    @if ($errors->any() && old('name') !== null)
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('addUserModal')).show();
        });
    @endif

    // Reusable Custom SweetAlert Confirmation Function (Adaptive Theme-Matched)
    function confirmAction(event, title, text, confirmButtonText, confirmButtonColor) {
        event.preventDefault();
        const form = event.target.closest('form');

        // Deteksyunan kung Dark o Light Mode batay sa root element o CSS variable
        const isDark = document.documentElement.classList.contains('dark') || 
                       document.body.classList.contains('dark') || 
                       document.body.classList.contains('dark-theme') || 
                       getComputedStyle(document.documentElement).getPropertyValue('--dash-card-bg').includes('30, 41, 59');

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            iconColor: confirmButtonColor,
            showCancelButton: true,
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            background: isDark ? 'rgba(30, 41, 59, 0.95)' : 'rgba(255, 255, 255, 0.95)',
            color: isDark ? '#f8fafc' : '#0f172a',
            customClass: {
                popup: 'swal2-glass-modal',
                title: 'swal2-glass-title',
                htmlContainer: 'swal2-glass-text',
                confirmButton: 'swal2-btn-confirm',
                cancelButton: 'swal2-btn-cancel',
                actions: 'swal2-glass-actions'
            },
            willOpen: (el) => {
                el.style.border = isDark ? '1px solid rgba(255, 255, 255, 0.12)' : '1px solid rgba(0, 0, 0, 0.1)';
                el.style.backdropFilter = 'blur(16px)';
                el.style.webkitBackdropFilter = 'blur(16px)';
                
                const btnConfirm = el.querySelector('.swal2-btn-confirm');
                if (btnConfirm) {
                    btnConfirm.style.backgroundColor = confirmButtonColor;
                    btnConfirm.style.borderColor = confirmButtonColor;
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection