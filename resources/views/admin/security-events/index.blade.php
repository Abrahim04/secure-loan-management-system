@extends('layouts.app')

@section('title', 'Security Events')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling (100% Matched with Audit Logs Code) -->
<style>
    /* Dash Card & Container Theme - Matched with Audit Logs Code */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    /* Dark Mode Theme Override - Ensures consistent Dark Slate Blue, never solid black */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Input Icon Wrapper Container */
    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-icon-wrapper .search-icon {
        position: absolute;
        left: 12px;
        color: var(--text-muted);
        pointer-events: none;
        transition: color 0.2s ease;
    }

    /* Custom Form Control Styling with Icon Padding */
    .glass-input {
        background: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    .glass-input.has-icon {
        padding-left: 36px !important;
    }
    .glass-input:focus {
        background: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }
    .glass-input:focus + .search-icon,
    .input-icon-wrapper:focus-within .search-icon {
        color: #0284c7;
    }
    .glass-input::placeholder {
        color: var(--text-muted, #94a3b8);
        opacity: 0.7;
    }

    /* Filter Action Button */
    .btn-filter-glass {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #0284c7;
        border: 1px solid #0369a1;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 20px;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .btn-filter-glass:hover {
        background: #0369a1;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Modern Glass Table Styling - Exactly Matched with Audit Logs */
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
    }
    .custom-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.03) !important;
    }
    .custom-table tbody td {
        padding: 14px 16px !important;
        border-bottom: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.05)) !important;
        color: var(--text-main, #f8fafc) !important;
        font-size: 0.875rem;
        background: transparent !important;
        vertical-align: middle;
    }

    /* Monospace Tech Text */
    .mono-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.825rem !important;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Clean Text Status Badges (No Background, Colored Text Only) */
    .action-text {
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        background: transparent !important;
        padding: 0;
        border: none !important;
    }
    .action-success { color: #10b981 !important; }
    .action-primary { color: #0284c7 !important; }
    .action-warning { color: #f59e0b !important; }
    .action-danger  { color: #ef4444 !important; }
    .action-secondary { color: #94a3b8 !important; }

    /* Custom Bootstrap Pagination Styling */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .pagination .page-item .page-link {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 8px !important;
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.15s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: #0284c7 !important;
        border-color: #0284c7 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
    }
    .pagination .page-item .page-link:hover {
        background-color: rgba(2, 132, 199, 0.15) !important;
        border-color: #0284c7 !important;
        color: #0284c7 !important;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        background-color: transparent !important;
        color: var(--text-muted, #94a3b8) !important;
    }
</style>

<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Security Events</h2>
        <p class="text-muted small mb-0">Monitor system access, authentication logs, and potential security anomalies.</p>
    </div>

    {{-- Filter Search Card --}}
    <div class="dash-card p-3 mb-4">
        <form method="GET" action="{{ route('admin.security-events.index') }}" class="row g-2 align-items-center">
            
            {{-- Search Input --}}
            <div class="col-md-3">
                <div class="input-icon-wrapper">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="search" class="form-control glass-input has-icon" placeholder="Search user or IP..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Event Type Dropdown Filter --}}
            <div class="col-md-3">
                <select name="event_type" class="form-select glass-input">
                    <option value="">All Event Types</option>
                    @foreach ($eventTypes as $type)
                        <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- From Date --}}
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control glass-input" value="{{ request('date_from') }}" title="From Date">
            </div>

            {{-- To Date --}}
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control glass-input" value="{{ request('date_to') }}" title="To Date">
            </div>

            {{-- Filter Submit Button --}}
            <div class="col-md-2">
                <button type="submit" class="btn-filter-glass w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span>Filter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Security Events Data Table Card --}}
    <div class="dash-card p-0 overflow-hidden mb-4">
        @if ($events->isEmpty())
            <div class="p-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3 opacity-50"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                <h6 class="fw-semibold text-main mb-1">No Security Events Found</h6>
                <p class="text-muted small mb-0">Try adjusting your date or event type filter criteria.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Date / Time</th>
                            <th>Event Type</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            @php
                                $isAlert = str_starts_with($event->event_type, 'failed') || $event->event_type === 'unauthorized_access';
                                $actionClass = $isAlert ? 'action-danger' : 'action-primary';
                            @endphp
                            <tr>
                                <td class="ps-4 text-nowrap fw-medium text-main">
                                    {{ $event->created_at->format('M d, Y') }}
                                    <span class="d-block text-muted small">{{ $event->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span class="action-text {{ $actionClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-main d-block">{{ $event->user->name ?? 'Unknown' }}</span>
                                </td>
                                <td class="text-main" style="max-width: 280px;">
                                    <span class="text-muted small d-inline-block text-truncate w-100" title="{{ $event->description }}">
                                        {{ $event->description ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-main mono-text" style="font-size: 0.85rem;">
                                        {{ $event->ip_address ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <span class="d-inline-block text-break" style="max-width: 300px;">
                                        {{ $event->user_agent ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagination Section --}}
    @if (!$events->isEmpty())
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $events->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $events->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $events->total() }}</span> results
            </div>
            <div>
                {{ $events->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection