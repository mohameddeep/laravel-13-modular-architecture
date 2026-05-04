<?php

use App\Modules\Auth\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {
        Route::apiResource('auths', AuthController::class);
    });
