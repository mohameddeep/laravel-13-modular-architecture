<?php

use App\Modules\Auth\Http\Controllers\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('dashboard')
    ->group(function (): void {
        Route::resource('auths', AuthController::class)
            ->except(['show'])
            ->names('dashboard.auth');
    });
