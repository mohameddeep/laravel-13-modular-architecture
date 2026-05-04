<?php

use App\Modules\Category\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/web')
    ->name('api.v1.web.')
    ->group(function (): void {
        Route::apiResource('categories', CategoryController::class);
    });
