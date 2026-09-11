<?php

use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
});
