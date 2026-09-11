@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<h4 class="mb-4">Audit Logs</h4>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3">
        <input type="text" name="user" class="form-control" placeholder="Filter by user name" value="{{ request('user') }}">
    </div>
    <div class="col-md-3">
        <input type="text" name="action" class="form-control" placeholder="Filter by action" value="{{ request('action') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

@if ($logs->isEmpty())
    <div class="alert alert-info">No audit log entries found.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle table-sm">
            <thead class="table-light">
                <tr>
                    <th>Date/Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Related</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->related_type ? "{$log->related_type} #{$log->related_id}" : '—' }}</td>
                        <td>{{ $log->description ?? '—' }}</td>
                        <td>{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $logs->links() }}
@endif
@endsection
