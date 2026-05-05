<?php

use App\Modules\Auth\Http\Controllers\Dashboard\AdminController;
use App\Modules\Auth\Http\Controllers\Dashboard\Auth\DashboardAuthController;
use App\Modules\Auth\Http\Controllers\Dashboard\HomeController;
use App\Modules\Auth\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->group(function (): void {

        Route::middleware('guest:admin')
            ->prefix('auth')
            ->name('auth.')
            ->group(function (): void {
                Route::get('login', [DashboardAuthController::class, 'showLoginForm'])->name('login');
                Route::post('login', [DashboardAuthController::class, 'login'])
                    ->middleware('throttle:10,1')
                    ->name('login.attempt');
            });

        Route::middleware('auth:admin')->group(function (): void {
            Route::post('auth/logout', [DashboardAuthController::class, 'logout'])->name('auth.logout');

            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

            Route::resource('admins', AdminController::class)->except(['show']);
        });
    });
