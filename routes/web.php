<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
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
