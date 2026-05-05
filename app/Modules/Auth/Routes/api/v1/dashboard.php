<?php

use App\Modules\Auth\Http\Controllers\Api\V1\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

// ─── Public Dashboard Auth ────────────────────────────────────────────────────

Route::middleware(['api'])
    ->prefix('api/v1/dashboard')
    ->name('api.v1.dashboard.')
    ->group(function (): void {
        Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function (): void {
            Route::post('sign-in', 'signIn')->name('sign-in')->middleware('throttle:10,1');
        });
    });

// ─── Protected Dashboard Routes (admin guard) ─────────────────────────────────

Route::middleware(['api', 'auth:admin-api'])
    ->prefix('api/v1/dashboard')
    ->name('api.v1.dashboard.')
    ->group(function (): void {
        Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function (): void {
            Route::post('sign-out', 'signOut')->name('sign-out');
        });
    });
