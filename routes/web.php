<?php

use App\Http\Controllers\DashboardController;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        // Kunin ang kasalukuyang system state
        $systemState = SystemSetting::get('maintenance_mode', '0');
        
        // Kung normal/operational ang system o Admin ang naka-log in, pumunta sa dashboard
        if ($systemState === '0' || auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
    }
    
    return view('welcome');
});

// Protected routes with authentication and 3-State Operational Check (Operational / Maintenance / Shutdown)
Route::middleware(['auth', 'operational'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
require __DIR__.'/notifications.php';
require __DIR__.'/loans.php';
require __DIR__.'/payments.php';
require __DIR__.'/schedule.php';
require __DIR__.'/admin-monitoring.php';
require __DIR__.'/admin-users.php';
require __DIR__.'/profile.php';
require __DIR__.'/admin-settings.php';