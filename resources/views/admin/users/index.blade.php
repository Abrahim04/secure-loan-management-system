@extends('layouts.app')

@section('title', $showingTrash ? 'Archived Borrowers' : 'Borrower Management')

@section('content')
<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $showingTrash ? 'Archived Borrowers' : 'Borrower Management' }}</h4>
            <p class="text-muted small mb-0">
                {{ $showingTrash ? 'Manage deleted borrower accounts and restore if needed.' : 'View, edit, and manage registered borrower accounts and permissions.' }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index', $showingTrash ? [] : ['trashed' => 1]) }}" class="btn btn-outline-secondary rounded-3 d-inline-flex align-items-center gap-2 px-3 py-2 fw-medium">
                @if ($showingTrash)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>View Active Borrowers</span>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>View Trash / Archived</span>
                @endif
            </a>
            @unless ($showingTrash)
                <button type="button" class="btn btn-primary rounded-3 d-inline-flex align-items-center gap-2 px-3 py-2 fw-medium" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
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

    {{-- Filters --}}
    @unless ($showingTrash)
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: var(--dash-card-bg);">
            <div class="card-body p-3">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" name="search" class="form-control rounded-3" style="padding-left: 40px;" placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select rounded-3">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked Only</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary rounded-3 w-100 fw-medium">
                            <span>Filter Results</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endunless

    {{-- Table Section --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: var(--dash-card-bg);">
        @if ($users->isEmpty())
            <div class="text-center py-5">
                <div class="p-3 bg-body-tertiary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h6 class="fw-bold mb-1">{{ $showingTrash ? 'No archived borrowers' : 'No borrowers found' }}</h6>
                <p class="text-muted small mb-0">Try adjusting your filters or search query.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="ps-4 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Borrower Details</th>
                            @unless ($showingTrash)
                                <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Loans</th>
                                <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Payments</th>
                                <th class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Status</th>
                                <th class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Verification</th>
                                <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Joined</th>
                            @else
                                <th class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Deleted At</th>
                            @endunless
                            <th class="text-center text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-body">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                @unless ($showingTrash)
                                    <td class="text-center fw-semibold">{{ $user->loans_count }}</td>
                                    <td class="text-center fw-semibold">{{ $user->payments_count }}</td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1">
                                                Blocked
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2.5 py-1">
                                                Unverified
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted small">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        {{-- Fixed-width Flex layout para pantay-pantay ang action buttons --}}
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-2 px-0" style="width: 62px;" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                Edit
                                            </button>

                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                @if ($user->is_active)
                                                    <button type="submit" class="btn btn-sm btn-outline-warning rounded-2 px-0" style="width: 72px;" onclick="return confirm('Block borrower {{ $user->name }}? They will be unable to log in.')">
                                                        Block
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-2 px-0" style="width: 72px;" onclick="return confirm('Reactivate borrower {{ $user->name }}?')">
                                                        Activate
                                                    </button>
                                                @endif
                                            </form>

                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-0" style="width: 65px;" onclick="return confirm('Move borrower {{ $user->name }} to trash? They will be unable to log in until restored.')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4">
                                                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Edit Borrower Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-muted">Full Name</label>
                                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-muted">Email</label>
                                                            <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-muted">Role</label>
                                                            <select name="role" class="form-select rounded-3">
                                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Borrower</option>
                                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-muted">Status</label>
                                                            <select name="status" class="form-select rounded-3">
                                                                <option value="active" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                                                <option value="blocked" {{ ! $user->is_active ? 'selected' : '' }}>Blocked</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-semibold text-muted">Verification Status</label>
                                                            <select name="verification_status" class="form-select rounded-3">
                                                                <option value="verified" {{ $user->email_verified_at ? 'selected' : '' }}>Verified</option>
                                                                <option value="unverified" {{ ! $user->email_verified_at ? 'selected' : '' }}>Unverified</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary rounded-3 px-4">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <td class="text-muted small">{{ $user->deleted_at->format('M d, Y h:i A') }}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1">
                                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success rounded-2 px-2" onclick="return confirm('Restore borrower {{ $user->name }}?')">Restore</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger rounded-2 px-2" onclick="return confirm('Permanently delete borrower {{ $user->name }}? This cannot be undone.')">Permanently Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endunless
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Add Borrower Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Borrower</h5>
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
                        <label class="form-label small fw-semibold text-muted">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required placeholder="john@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                        <div class="form-text small" style="font-size: 0.75rem;">
                            Must be at least 8 characters with upper, lower, number, and special characters (!@#$%).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Role</label>
                        <select name="role" class="form-select rounded-3">
                            <option value="user">Borrower</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Initial Verification Status</label>
                        <select name="verification_status" class="form-select rounded-3">
                            <option value="unverified">Unverified</option>
                            <option value="verified">Verified</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Create Borrower</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    @if ($errors->any() && old('name') !== null)
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('addUserModal')).show();
        });
    @endif
</script>
@endpush
@endsection