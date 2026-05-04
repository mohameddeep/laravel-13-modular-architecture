<?php

use App\Modules\Category\Http\Controllers\Api\V1\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {
        Route::apiResource('sub-categories', SubCategoryController::class);
    });
