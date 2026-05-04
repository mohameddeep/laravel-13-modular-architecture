<?php

use App\Modules\Category\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {
        Route::apiResource('categories', CategoryController::class);
    });
