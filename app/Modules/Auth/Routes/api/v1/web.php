<?php

use App\Modules\Auth\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/web')
    ->name('api.v1.web.')
    ->group(function (): void {
        Route::apiResource('auths', AuthController::class);
    });
