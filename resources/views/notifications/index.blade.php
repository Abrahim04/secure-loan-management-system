@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Notifications</h4>
    <form method="POST" action="{{ route('notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark all as read</button>
    </form>
</div>

@if ($notifications->isEmpty())
    <div class="alert alert-info">You have no notifications yet.</div>
@else
    <div class="list-group">
        @foreach ($notifications as $notification)
            <div class="list-group-item {{ $notification->is_read ? '' : 'list-group-item-light border-start border-3 border-primary' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $notification->title }}</strong>
                        @unless ($notification->is_read)
                            <span class="badge bg-primary ms-2">New</span>
                        @endunless
                        <p class="mb-1">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @unless ($notification->is_read)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link">Mark as read</button>
                        </form>
                    @endunless
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
