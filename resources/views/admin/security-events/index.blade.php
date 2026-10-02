@extends('layouts.app')

@section('title', 'Security Events')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>

    /* Custom Pagination Styling */
.pagination {
    margin-bottom: 0;
    gap: 4px;
}

.pagination .page-item .page-link {
    background-color: rgba(100, 116, 139, 0.05) !important;
    border: 1px solid var(--dash-card-border) !important;
    color: var(--text-main) !important;
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
    background-color: rgba(2, 132, 199, 0.1) !important;
    border-color: #0284c7 !important;
    color: #0284c7 !important;
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    background-color: transparent !important;
    color: var(--text-muted) !important;
}

    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Custom Form Control Styling */
    .glass-input {
        background: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border) !important;
        color: var(--text-main) !important;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .glass-input:focus {
        background-color: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        outline: none;
    }

    /* Custom Select Button and Dropdown Menu */
    .custom-select-btn {
        width: 100%;
        background-color: rgba(100, 116, 139, 0.05) !important;
        color: var(--text-main) !important;
        border: 1px solid var(--dash-card-border) !important;
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
        background-color: var(--dash-card-bg) !important;
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 10px;
        padding: 6px;
        margin-top: 4px;
        box-shadow: var(--dash-card-shadow);
        backdrop-filter: blur(16px);
        z-index: 9999 !important;
        max-height: 250px;
        overflow-y: auto;
    }

    .custom-select-item {
        color: var(--text-main) !important;
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

    /* Action Primary Button */
    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #0284c7;
        border: 1px solid #0369a1;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .btn-action-primary:hover {
        background: #0369a1;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        transform: translateY(-1px);
    }

    /* Table Styling */
    .custom-table {
        color: var(--text-main);
        vertical-align: middle;
        margin-bottom: 0;
    }
    .custom-table thead th {
        background: rgba(100, 116, 139, 0.06);
        color: var(--text-muted);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        border-bottom: 1px solid var(--dash-card-border);
        padding: 12px 16px;
    }
    .custom-table tbody tr {
        border-bottom: 1px solid var(--dash-card-border);
        transition: background-color 0.15s ease;
    }
    .custom-table tbody tr:hover {
        background-color: rgba(100, 116, 139, 0.04);
    }
    .custom-table tbody tr.row-security-alert {
        background-color: rgba(239, 68, 68, 0.08) !important;
    }
    .custom-table tbody tr.row-security-alert:hover {
        background-color: rgba(239, 68, 68, 0.14) !important;
    }
    .custom-table tbody td {
        padding: 13px 16px;
        font-size: 0.875rem;
    }

    /* Soft Badge Styles */
    .badge-soft {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        display: inline-block;
    }
    .badge-soft-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; }
    .badge-soft-info   { background: rgba(2, 132, 199, 0.15); color: #0284c7; }
</style>

<div class="container-fluid px-0">
    {{-- Header Section --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Security Events</h2>
        <p class="text-muted small mb-0">Monitor system access, authentication logs, and potential security anomalies.</p>
    </div>

   {{-- Filter Card --}}
<div class="dash-card p-3 mb-4 position-relative" style="z-index: 20;">
    <form method="GET" action="{{ route('admin.security-events.index') }}" class="row g-2 align-items-center">
        
        {{-- Search Input (Para makapag-type ka ng keyword, name, or IP) --}}
        <div class="col-md-3">
            <div class="position-relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" class="form-control glass-input" style="padding-left: 40px;" placeholder="Search user or IP..." value="{{ request('search') }}">
            </div>
        </div>

        {{-- Custom Select Event Type Dropdown --}}
        <div class="col-md-3">
            <input type="hidden" name="event_type" id="filter_event_type_input" value="{{ request('event_type') }}">
            <div class="dropdown position-relative">
                <button class="custom-select-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                    <span id="filter_event_type_text">
                        @if(request('event_type'))
                            {{ ucfirst(str_replace('_', ' ', request('event_type'))) }}
                        @else
                            All Event Types
                        @endif
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <ul class="dropdown-menu custom-select-menu shadow-lg">
                    <li>
                        <a class="dropdown-item custom-select-item" href="#" onclick="selectOption('filter_event_type_input', 'filter_event_type_text', '', 'All Event Types'); return false;">
                            All Event Types
                        </a>
                    </li>
                    @foreach ($eventTypes as $type)
                        <li>
                            <a class="dropdown-item custom-select-item" href="#" onclick="selectOption('filter_event_type_input', 'filter_event_type_text', '{{ $type }}', '{{ ucfirst(str_replace('_', ' ', $type)) }}'); return false;">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
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
            <button type="submit" class="btn-action-primary w-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span>Filter</span>
            </button>
        </div>
    </form>
</div>

    {{-- Security Events Table Card --}}
    <div class="dash-card overflow-hidden mb-4">
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
                            <th class="ps-4">Date/Time</th>
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
                            @endphp
                            <tr class="{{ $isAlert ? 'row-security-alert' : '' }}">
                                <td class="ps-4 text-nowrap text-muted small">
                                    {{ $event->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td>
                                    <span class="badge-soft {{ $isAlert ? 'badge-soft-danger' : 'badge-soft-info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                    </span>
                                </td>
                                <td class="fw-semibold text-main">
                                    {{ $event->user->name ?? 'Unknown' }}
                                </td>
                                <td class="text-main">
                                    {{ $event->description ?? '—' }}
                                </td>
                                <td>
                                    <code class="px-2 py-1 rounded bg-opacity-10 text-main" style="font-size: 0.8rem; background: rgba(100, 116, 139, 0.1);">
                                        {{ $event->ip_address ?? '—' }}
                                    </code>
                                </td>
                                <td class="text-muted small text-truncate" style="max-width: 220px;" title="{{ $event->user_agent }}">
                                    {{ $event->user_agent ?? '—' }}
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
    <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
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

@push('scripts')
<script>
    function selectOption(inputId, textId, value, label) {
        document.getElementById(inputId).value = value;
        document.getElementById(textId).innerText = label;
    }
</script>
@endpush
@endsection