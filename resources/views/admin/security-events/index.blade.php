@extends('layouts.app')

@section('title', 'Security Events')

@section('content')
<h4 class="mb-4">Security Events</h4>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="event_type" class="form-select">
            <option value="">All event types</option>
            @foreach ($eventTypes as $type)
                <option value="{{ $type }}" {{ request('event_type') === $type ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-md-3">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

@if ($events->isEmpty())
    <div class="alert alert-info">No security events found.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle table-sm">
            <thead class="table-light">
                <tr>
                    <th>Date/Time</th>
                    <th>Event Type</th>
                    <th>User</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr class="{{ str_starts_with($event->event_type, 'failed') || $event->event_type === 'unauthorized_access' ? 'table-danger' : '' }}">
                        <td>{{ $event->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $event->event_type)) }}</td>
                        <td>{{ $event->user->name ?? 'Unknown' }}</td>
                        <td>{{ $event->description ?? '—' }}</td>
                        <td>{{ $event->ip_address ?? '—' }}</td>
                        <td class="text-truncate" style="max-width: 200px;">{{ $event->user_agent ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $events->links() }}
@endif
@endsection
