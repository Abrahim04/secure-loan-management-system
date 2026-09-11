@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<h4 class="mb-4">User Management</h4>

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
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($users->isEmpty())
    <div class="alert alert-info">No borrowers found.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Loans</th>
                    <th>Payments</th>
                    <th>Status</th>
                    <th>Verification</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
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
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf
                                @if ($user->is_active)
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Block {{ $user->name }}? They will be unable to log in.')">Block</button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Reactivate {{ $user->name }}?')">Activate</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endif
@endsection
