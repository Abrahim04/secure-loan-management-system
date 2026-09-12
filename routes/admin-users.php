<?php

use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // {id} (not {user}) for trashed records — Laravel's implicit route-model
    // binding excludes soft-deleted rows by default.
    Route::put('/users/{id}/restore', [UserManagementController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [UserManagementController::class, 'forceDelete'])->name('users.force-delete');
});
