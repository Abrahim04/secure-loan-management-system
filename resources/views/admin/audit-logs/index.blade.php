@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean UI Styling -->
<style>
    .dash-card {
        background-color: var(--dash-card-bg) !important;
        backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border) !important;
        border-radius: 14px;
        box-shadow: var(--dash-card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
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
        border: 1px solid var(--dash-card-border) !important;
        color: var(--text-main) !important;
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
        color: var(--text-muted);
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
    .custom-table tbody td {
        padding: 13px 16px;
        font-size: 0.875rem;
    }

    /* Monospace Tech Text */
    .mono-text {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
        font-size: 0.825rem !important;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Action Badge Soft Styles */
    .badge-soft {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        display: inline-block;
    }
    .badge-soft-primary { background: rgba(2, 132, 199, 0.12); color: #0284c7; }
    .badge-soft-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .badge-soft-danger  { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
    .badge-soft-secondary { background: rgba(100, 116, 139, 0.12); color: #64748b; }

    /* Custom Bootstrap Pagination Styling */
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
</style>

<div class="container-fluid px-0">
    {{-- Header Title --}}
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Audit Logs</h2>
        <p class="text-muted small mb-0">Track and monitor all system activities, actions, and administrative changes.</p>
    </div>

    {{-- Filter Search Card --}}
    <div class="dash-card p-3 mb-4">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-icon-wrapper">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="user" class="form-control glass-input has-icon" placeholder="Filter by user name..." value="{{ request('user') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-icon-wrapper">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="action" class="form-control glass-input has-icon" placeholder="Filter by action..." value="{{ request('action') }}">
                </div>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control glass-input" value="{{ request('date_from') }}" title="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control glass-input" value="{{ request('date_to') }}" title="To Date">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-filter-glass w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span>Filter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Audit Log Data Table Card --}}
    <div class="dash-card overflow-hidden mb-4">
        @if ($logs->isEmpty())
            <div class="p-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3 opacity-50"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                <h6 class="fw-semibold text-main mb-1">No Audit Log Entries Found</h6>
                <p class="text-muted small mb-0">Try adjusting your filter search criteria to find matching logs.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table custom-table align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Date / Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Related Record</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            @php
                                // Dynamic Soft Badge Color Picker based on Action Name
                                $actionLower = strtolower($log->action);
                                $badgeClass = 'badge-soft-secondary';

                                if (str_contains($actionLower, 'approved') || str_contains($actionLower, 'restored')) {
                                    $badgeClass = 'badge-soft-success';
                                } elseif (str_contains($actionLower, 'submitted') || str_contains($actionLower, 'registered') || str_contains($actionLower, 'created')) {
                                    $badgeClass = 'badge-soft-primary';
                                } elseif (str_contains($actionLower, 'updated') || str_contains($actionLower, 'changed') || str_contains($actionLower, 'reset')) {
                                    $badgeClass = 'badge-soft-warning';
                                } elseif (str_contains($actionLower, 'deleted') || str_contains($actionLower, 'rejected')) {
                                    $badgeClass = 'badge-soft-danger';
                                }
                            @endphp
                            <tr>
                                <td class="ps-4 text-nowrap fw-medium text-main">
                                    {{ $log->created_at->format('M d, Y') }}
                                    <span class="d-block text-muted small">{{ $log->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-main d-block">{{ $log->user->name ?? 'System' }}</span>
                                </td>
                                <td>
                                    <span class="badge-soft {{ $badgeClass }}">{{ $log->action }}</span>
                                </td>
                                <td>
                                    @if ($log->related_type)
                                        <span class="mono-text text-main">{{ $log->related_type }} #{{ $log->related_id }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td style="max-width: 320px;">
                                    <span class="text-muted small d-inline-block text-truncate w-100" title="{{ $log->description }}">
                                        {{ $log->description ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <code class="px-2 py-1 rounded text-main" style="font-size: 0.8rem; background: rgba(100, 116, 139, 0.1);">
                                        {{ $log->ip_address ?? '—' }}
                                    </code>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Styled Glassmorphism Pagination Card --}}
    @if (!$logs->isEmpty())
        <div class="dash-card p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mb-4">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-main">{{ $logs->firstItem() }}</span> 
                to <span class="fw-semibold text-main">{{ $logs->lastItem() }}</span> 
                of <span class="fw-semibold text-main">{{ $logs->total() }}</span> results
            </div>
            <div>
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection