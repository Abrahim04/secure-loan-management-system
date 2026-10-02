<?php

use App\Http\Controllers\Admin\LoanReviewController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

// Admin Loan Review & History Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/loans', [LoanReviewController::class, 'index'])->name('loans.index');
    Route::get('/loans/history', [LoanReviewController::class, 'history'])->name('loans.history');
    Route::get('/loans/{loan}', [LoanReviewController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/approve', [LoanReviewController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [LoanReviewController::class, 'reject'])->name('loans.reject');
});

// User-facing Loan Routes
Route::middleware('auth')->group(function () {
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/apply', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
});