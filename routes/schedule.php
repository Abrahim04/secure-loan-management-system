<?php

use App\Http\Controllers\PaymentScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/payment-schedule', [PaymentScheduleController::class, 'index'])->name('payment-schedule.index');
});