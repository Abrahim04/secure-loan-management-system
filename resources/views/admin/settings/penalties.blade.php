@extends('layouts.app')

@section('title', 'Penalty Management')

@section('content')
<h4 class="mb-4">Penalty Management</h4>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <p class="text-muted small">
            These values control the automatic overdue detection command
            (<code>php artisan loans:apply-penalties</code>). Changes take effect
            on the next run — they do not retroactively recalculate existing penalties.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.penalties.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Penalty per Day (₱)</label>
                <input type="number" step="0.01" name="penalty_per_day" class="form-control"
                       value="{{ old('penalty_per_day', $settings['penalty_per_day']) }}" required>
                <div class="form-text">Amount added to the balance for each day an installment is overdue.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Grace Period (days)</label>
                <input type="number" name="grace_period_days" class="form-control"
                       value="{{ old('grace_period_days', $settings['grace_period_days']) }}" required>
                <div class="form-text">Days after the due date before a penalty starts accruing.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Maximum Penalty (₱)</label>
                <input type="number" step="0.01" name="max_penalty" class="form-control"
                       value="{{ old('max_penalty', $settings['max_penalty']) }}" required>
                <div class="form-text">The penalty for a single installment will never exceed this amount.</div>
            </div>

            <button type="submit" class="btn btn-primary">Save Penalty Policy</button>
        </form>
    </div>
</div>
@endsection
