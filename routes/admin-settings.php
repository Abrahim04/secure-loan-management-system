<?php

use App\Http\Controllers\Admin\SystemSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings/penalties', [SystemSettingsController::class, 'penalties'])->name('settings.penalties');
    Route::put('/settings/penalties', [SystemSettingsController::class, 'updatePenalties'])->name('settings.penalties.update');

    Route::get('/settings/system', [SystemSettingsController::class, 'general'])->name('settings.system');
    Route::put('/settings/system', [SystemSettingsController::class, 'updateGeneral'])->name('settings.system.update');
});
