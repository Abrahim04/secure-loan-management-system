<?php

use App\Http\Controllers\Admin\LoanReviewController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// User-facing loan routes
Route::middleware('auth')->group(function () {
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/apply', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
});

// Admin loan review routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/loans', [LoanReviewController::class, 'index'])->name('loans.index');
    Route::get('/loans/{loan}', [LoanReviewController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/approve', [LoanReviewController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [LoanReviewController::class, 'reject'])->name('loans.reject');
});
