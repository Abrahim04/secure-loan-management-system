<?php

use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Bagong mga route para sa hiwalay na Borrower Verification Form
    Route::get('/verification/apply', [ProfileController::class, 'showVerificationForm'])->name('verification.form');
    Route::post('/profile/verify', [ProfileController::class, 'submitVerification'])->name('profile.verify');

    Route::get('/avatar/{user}', [AvatarController::class, 'show'])->name('avatar.show');
});