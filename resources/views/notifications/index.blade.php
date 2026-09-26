@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Notifications</h4>
            <p class="text-muted small mb-0">Stay updated with loan requests, approvals, and system alerts.</p>
        </div>
        @if ($notifications->isNotEmpty() && $notifications->contains('is_read', false))
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-3 d-inline-flex align-items-center gap-2 px-3 py-2 fw-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        {{-- Empty State --}}
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="p-3 bg-body-tertiary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                </div>
                <h6 class="fw-bold mb-1">No notifications yet</h6>
                <p class="text-muted small mb-0">When you receive new updates, they will show up here.</p>
            </div>
        </div>
    @else
        {{-- Notification List --}}
        <div class="d-flex flex-column gap-3">
            @foreach ($notifications as $notification)
                <div class="card border-0 shadow-sm rounded-4 transition-all {{ $notification->is_read ? 'opacity-75' : 'border-start border-4 border-primary' }}" style="background: var(--dash-card-bg);">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="d-flex align-items-start gap-3">
                                {{-- Status Icon Badge --}}
                                <div class="p-2.5 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 {{ $notification->is_read ? 'bg-body-tertiary text-muted' : 'bg-primary-subtle text-primary' }}" style="width: 42px; height: 42px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                </div>

                                {{-- Content --}}
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h6 class="fw-bold mb-0 text-body">{{ $notification->title }}</h6>
                                        @unless ($notification->is_read)
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.7rem;">New</span>
                                        @endunless
                                    </div>
                                    <p class="text-body-secondary mb-2 small">{{ $notification->message }}</p>
                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.75rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            @unless ($notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light text-primary rounded-3 fw-medium d-inline-flex align-items-center gap-1 px-3 py-1.5" title="Mark as read">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span class="d-none d-sm-inline">Mark as read</span>
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection