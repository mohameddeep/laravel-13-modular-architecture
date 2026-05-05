<?php

use App\Modules\Roles\Http\Controllers\Dashboard\PermissionController;
use App\Modules\Roles\Http\Controllers\Dashboard\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function (): void {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', PermissionController::class)->except(['show']);
    });
