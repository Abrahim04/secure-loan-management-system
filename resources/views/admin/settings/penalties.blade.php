@extends('layouts.app')

@section('title', 'Penalty Management')

@section('content')
<!-- Adaptive Glassmorphism & SaaS Clean Form Styling -->
<style>
    /* Translucent Glass Dash Card Container - Matched with Audit Logs */
    .dash-card {
        background: var(--dash-card-bg, rgba(30, 41, 59, 0.45)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.08)) !important;
        border-radius: 16px;
        box-shadow: var(--dash-card-shadow, 0 8px 32px 0 rgba(0, 0, 0, 0.25));
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.3s ease;
    }

    /* Dark Mode Theme Override */
    html[data-bs-theme="dark"] .dash-card,
    body.dark-mode .dash-card,
    .dark .dash-card {
        background: rgba(30, 41, 59, 0.55) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    /* Glass Form Inputs */
    .form-control-glass {
        background-color: rgba(100, 116, 139, 0.05) !important;
        border: 1px solid var(--dash-card-border, rgba(255, 255, 255, 0.12)) !important;
        color: var(--text-main, #f8fafc) !important;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control-glass:focus {
        background-color: transparent !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
        outline: none;
    }

    /* Primary Pill Button */
    .btn-pill-primary {
        background: #0284c7;
        border: 1px solid #0369a1;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 9px 24px;
        border-radius: 50px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-pill-primary:hover {
        background: #0369a1;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Custom Informational Box */
    .glass-info-box {
        background: rgba(2, 132, 199, 0.08);
        border: 1px solid rgba(2, 132, 199, 0.2);
        border-radius: 12px;
        padding: 14px 18px;
        color: var(--text-main, #f8fafc);
        font-size: 0.85rem;
    }

    .glass-info-box code {
        background: rgba(255, 255, 255, 0.1);
        color: #38bdf8;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
</style>

<div class="container-fluid px-0">
    <div class="mb-4">
        <h2 class="fw-bold text-main mb-1">Penalty Management</h2>
        <p class="text-muted small mb-0">Configure late payment penalties, grace periods, and maximum cap limits.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="dash-card p-4" style="max-width: 600px;">
                <div class="glass-info-box mb-4">
                    These values control the automatic overdue detection command
                    (<code>php artisan loans:apply-penalties</code>). Changes take effect
                    on the next run — they do not retroactively recalculate existing penalties[cite: 26].
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 small mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}[cite: 26]</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.settings.penalties.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted mb-1">Penalty per Day (₱)</label>
                        <input type="number" step="0.01" name="penalty_per_day" class="form-control form-control-glass @error('penalty_per_day') is-invalid @enderror"
                               value="{{ old('penalty_per_day', $settings['penalty_per_day']) }}" required>
                        <div class="form-text text-muted small mt-1">Amount added to the balance for each day an installment is overdue[cite: 26].</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted mb-1">Grace Period (days)</label>
                        <input type="number" name="grace_period_days" class="form-control form-control-glass @error('grace_period_days') is-invalid @enderror"
                               value="{{ old('grace_period_days', $settings['grace_period_days']) }}" required>
                        <div class="form-text text-muted small mt-1">Days after the due date before a penalty starts accruing[cite: 26].</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted mb-1">Maximum Penalty (₱)</label>
                        <input type="number" step="0.01" name="max_penalty" class="form-control form-control-glass @error('max_penalty') is-invalid @enderror"
                               value="{{ old('max_penalty', $settings['max_penalty']) }}" required>
                        <div class="form-text text-muted small mt-1">The penalty for a single installment will never exceed this amount[cite: 26].</div>
                    </div>

                    <div>
                        <button type="submit" class="btn-pill-primary">Save Penalty Policy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection