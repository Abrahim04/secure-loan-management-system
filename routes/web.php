<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
require __DIR__.'/notifications.php';
require __DIR__.'/loans.php';
require __DIR__.'/payments.php';
require __DIR__.'/admin-monitoring.php';