<?php

use App\Modules\Products\Http\Controllers\Dashboard\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('dashboard')
    ->group(function (): void {
        Route::resource('products', ProductController::class)
            ->except(['show'])
            ->names('dashboard.products');
    });
