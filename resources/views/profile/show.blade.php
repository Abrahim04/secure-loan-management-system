@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<h4 class="mb-4">My Profile</h4>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3">
    {{-- Profile Photo --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle mb-3" width="120" height="120">
                <h6>{{ $user->name }}</h6>
                <span class="badge bg-{{ $user->isAdmin() ? 'dark' : 'primary' }}">{{ ucfirst($user->role) }}</span>

                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <input type="file" name="avatar" class="form-control form-control-sm mb-2" accept=".jpg,.jpeg,.png" required>
                    @error('avatar')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Upload New Photo</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Personal Details --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-3">Personal Details</h6>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="form-text">Email cannot be changed here.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>

        {{-- Account Security --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Account Security</h6>

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-outline-danger">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
