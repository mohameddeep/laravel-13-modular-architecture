<?php

use App\Modules\Category\Http\Controllers\Dashboard\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('dashboard')
    ->group(function (): void {
        Route::resource('sub-categories', SubCategoryController::class)
            ->except(['show'])
            ->names('dashboard.category');
    });
