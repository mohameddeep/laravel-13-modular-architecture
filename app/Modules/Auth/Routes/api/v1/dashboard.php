<?php

use App\Modules\Auth\Http\Controllers\Api\V1\Dashboard\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/dashboard')
    ->name('api.v1.dashboard.')
    ->group(function (): void {
        Route::apiResource('auths', AuthController::class);
    });
