<?php

use App\Modules\Products\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/v1/web')
    ->name('api.v1.web.')
    ->group(function (): void {
        Route::apiResource('products', ProductController::class);
    });
