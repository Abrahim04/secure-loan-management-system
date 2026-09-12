@extends('layouts.app')

@section('title', $showingTrash ? 'Archived Borrowers' : 'Borrower Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">{{ $showingTrash ? 'Archived Borrowers' : 'Borrower Management' }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index', $showingTrash ? [] : ['trashed' => 1]) }}" class="btn btn-outline-secondary">
            {{ $showingTrash ? 'View Active Borrowers' : 'View Trash / Archived Borrowers' }}
        </a>
        @unless ($showingTrash)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                + Add Borrower
            </button>
        @endunless
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@unless ($showingTrash)
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search name or email" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active only</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked only</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
        </div>
    </form>
@endunless

@if ($users->isEmpty())
    <div class="alert alert-info">{{ $showingTrash ? 'No archived borrowers.' : 'No borrowers found.' }}</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    @unless ($showingTrash)
                        <th>Loans</th>
                        <th>Payments</th>
                        <th>Status</th>
                        <th>Verification</th>
                        <th>Joined</th>
                    @else
                        <th>Deleted</th>
                    @endunless
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        @unless ($showingTrash)
                            <td>{{ $user->loans_count }}</td>
                            <td>{{ $user->payments_count }}</td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Blocked' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Edit</button>

                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                    @csrf
                                    @if ($user->is_active)
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Block borrower {{ $user->name }}? They will be unable to log in.')">Block</button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Reactivate borrower {{ $user->name }}?')">Activate</button>
                                    @endif
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Move borrower {{ $user->name }} to trash? They will be unable to log in until restored.')">Delete</button>
                                </form>
                            </td>

                            {{-- Edit modal for this row --}}
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Borrower Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <select name="role" class="form-select">
                                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Borrower</option>
                                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="active" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                                        <option value="blocked" {{ ! $user->is_active ? 'selected' : '' }}>Blocked</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Verification Status</label>
                                                    <select name="verification_status" class="form-select">
                                                        <option value="verified" {{ $user->email_verified_at ? 'selected' : '' }}>Verified</option>
                                                        <option value="unverified" {{ ! $user->email_verified_at ? 'selected' : '' }}>Unverified</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <td>{{ $user->deleted_at->format('M d, Y h:i A') }}</td>
                            <td class="text-nowrap">
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore borrower {{ $user->name }}?')">Restore</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.force-delete', $user->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete borrower {{ $user->name }}? This cannot be undone.')">Permanently Delete</button>
                                </form>
                            </td>
                        @endunless
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endif

{{-- Add Borrower Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Borrower</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user">Borrower</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Verification Status</label>
                        <select name="verification_status" class="form-select">
                            <option value="unverified">Unverified</option>
                            <option value="verified">Verified</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Borrower</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Re-open the Add Borrower modal automatically if validation failed on submit.
    @if ($errors->any() && old('name') !== null)
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('addUserModal')).show();
        });
    @endif
</script>
@endpush
@endsection
