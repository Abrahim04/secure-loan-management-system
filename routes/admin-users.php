<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VerificationManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Route para makuha ang personal at KYC details ng borrower para sa Modal (JSON)
    Route::get('/users/{user}/details', [UserManagementController::class, 'detailsJson'])->name('users.details');

    // Dedicated Verification Management Routes
    Route::get('/verifications', [VerificationManagementController::class, 'index'])->name('verifications.index');
    Route::post('/verifications/{user}/approve', [VerificationManagementController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{user}/reject', [VerificationManagementController::class, 'reject'])->name('verifications.reject');

    // Legacy verify routes (para panatilihin ang compatibility)
    Route::post('/users/{user}/verify/approve', [UserManagementController::class, 'approveVerification'])->name('users.verify.approve');
    Route::post('/users/{user}/verify/reject', [UserManagementController::class, 'rejectVerification'])->name('users.verify.reject');

    // {id} (not {user}) for trashed records — Laravel's implicit route-model
    // binding excludes soft-deleted rows by default.
    Route::put('/users/{id}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserManagementController::class, 'forceDelete'])->name('users.force-delete');
});