<?php

use App\Modules\Category\Http\Controllers\Api\V1\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {
        Route::apiResource('tests', TestController::class);
    });
