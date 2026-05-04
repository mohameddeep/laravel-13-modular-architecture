<?php

use App\Modules\Category\Http\Controllers\Dashboard\TestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->prefix('dashboard')
    ->group(function (): void {
        Route::resource('tests', TestController::class)
            ->except(['show'])
            ->names('dashboard.category');
    });
