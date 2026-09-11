<?php

use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// User-facing payment routes
Route::middleware('auth')->group(function () {
    Route::get('/payment-schedules/{paymentSchedule}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payment-schedules/{paymentSchedule}/pay', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/proof', [PaymentController::class, 'proof'])->name('payments.proof');
});

// Admin payment verification routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentVerificationController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');
});
