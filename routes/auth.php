<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('mfa/verify', [MfaController::class, 'show'])->name('mfa.verify');
    Route::post('mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify.submit');
    Route::post('mfa/resend', [MfaController::class, 'resend'])->name('mfa.resend');

    Route::get('forgot-password', [PasswordResetController::class, 'showEmailForm'])->name('password.reset.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendOtp'])->name('password.reset.send');

    Route::get('forgot-password/verify', [PasswordResetController::class, 'showVerifyForm'])->name('password.reset.verify');
    Route::post('forgot-password/verify', [PasswordResetController::class, 'verifyOtp'])->name('password.reset.verify.submit');

    Route::get('reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Example admin route group — apply the 'admin' middleware alias (EnsureUserIsAdmin)
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('dashboard', fn () => view('admin.dashboard'))->name('dashboard');
// });
