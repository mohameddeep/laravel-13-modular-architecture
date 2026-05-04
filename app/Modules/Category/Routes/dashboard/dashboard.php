<?php

use App\Modules\Category\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('dashboard')
    ->group(function (): void {
        Route::resource('categories', CategoryController::class)
            ->except(['show'])
            ->names('dashboard.category');
    });
