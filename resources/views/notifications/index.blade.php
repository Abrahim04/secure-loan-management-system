@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>
    /* Card Container Theme */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Primary Glass Button (Mark as read) */
    .btn-glass-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(2, 132, 199, 0.15) !important;
        border: 1px solid rgba(2, 132, 199, 0.3) !important;
        color: #38bdf8 !important;
        font-weight: 600;
        font-size: 0.825rem;
        padding: 6px 14px;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-glass-primary:hover {
        background: #0284c7 !important;
        color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Secondary Glass Button (Mark all as read) */
    .btn-glass-secondary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(100, 116, 139, 0.08) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-glass-secondary:hover {
        background: rgba(100, 116, 139, 0.2) !important;
        color: var(--text-main, #ffffff) !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
        transform: translateY(-1px);
    }

    /* Icon Badge Wrapper */
    .notification-icon-bg {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notification-icon-unread {
        background: rgba(2, 132, 199, 0.15) !important;
        color: #38bdf8 !important;
    }
    .notification-icon-read {
        background: rgba(100, 116, 139, 0.1) !important;
        color: var(--text-muted, #94a3b8) !important;
    }

    /* Badge New */
    .badge-new {
        background: rgba(2, 132, 199, 0.2) !important;
        color: #38bdf8 !important;
        border: 1px solid rgba(2, 132, 199, 0.3) !important;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 50px;
        padding: 2px 8px;
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-main mb-1">Notifications</h2>
            <p class="text-muted small mb-0">Stay updated with loan requests, approvals, and system alerts.</p>
        </div>
        @if ($notifications->isNotEmpty() && $notifications->contains('is_read', false))
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn-glass-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>Mark all as read</span>
                </button>
            </form>
        @endif
    </div>

    @if ($notifications->isEmpty())
        {{-- Empty State Card --}}
        <div class="dash-card p-5 text-center mb-4">
            <div class="notification-icon-bg notification-icon-read mx-auto mb-3" style="width: 56px; height: 56px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            </div>
            <h6 class="fw-bold text-main mb-1">No notifications yet</h6>
            <p class="text-muted small mb-0">When you receive new updates, they will show up here.</p>
        </div>
    @else
        {{-- Notification List --}}
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach ($notifications as $notification)
                <div class="dash-card p-3 p-md-4 {{ $notification->is_read ? 'opacity-75' : 'border-start border-4 border-info' }}">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div class="d-flex align-items-start gap-3">
                            {{-- Status Icon Badge --}}
                            <div class="notification-icon-bg {{ $notification->is_read ? 'notification-icon-read' : 'notification-icon-unread' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            </div>

                            {{-- Content --}}
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <h6 class="fw-bold text-main mb-0">{{ $notification->title }}</h6>
                                    @unless ($notification->is_read)
                                        <span class="badge-new">New</span>
                                    @endunless
                                </div>
                                <p class="text-muted mb-2 small">{{ $notification->message }}</p>
                                <div class="d-flex align-items-center gap-1 text-muted small" style="font-size: 0.75rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Mark as Read Button Action --}}
                        @unless ($notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}" class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="btn-glass-primary" title="Mark as read">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <span class="d-none d-sm-inline">Mark as read</span>
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="dash-card p-3 d-flex justify-content-center mb-4">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection