<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Applies to every Password::defaults() call across the app —
        // registration, password reset, change password, and admin
        // "Add Borrower" — since they all reference this default rather
        // than each writing out their own rule.
        Password::defaults(function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols();
        });
    }
}