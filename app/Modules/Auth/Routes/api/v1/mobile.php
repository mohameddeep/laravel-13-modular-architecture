<?php

use App\Modules\Auth\Http\Controllers\Api\V1\AuthController;
use App\Modules\Auth\Http\Controllers\Api\V1\Password\PasswordController;
use App\Modules\Auth\Http\Controllers\Api\V1\Profile\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::middleware(['api'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {

        // OTP-based auth (send → verify → auto register/login)
        Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function (): void {
            Route::post('send-otp',  'signUp')->name('send-otp')->middleware('throttle:10,1');
            Route::post('verify',    'verifyOtp')->name('verify')->middleware('throttle:20,1');
            Route::post('resend',    'resendOtp')->name('resend')->middleware('throttle:5,1');
            Route::post('sign-in',   'signIn')->name('sign-in')->middleware('throttle:15,1');
        });

        // Password Reset
        Route::prefix('password')->name('password.')->controller(PasswordController::class)->group(function (): void {
            Route::post('forgot',     'forgot')->name('forgot')->middleware('throttle:3,1');
            Route::post('verify-otp', 'verifyOtp')->name('verify-otp')->middleware('throttle:5,1');
            Route::post('reset',      'reset')->name('reset')->middleware('throttle:5,1');
        });
    });

// ─── Protected Routes ─────────────────────────────────────────────────────────

Route::middleware(['api', 'auth:sanctum'])
    ->prefix('api/v1/mobile')
    ->name('api.v1.mobile.')
    ->group(function (): void {

        // Authentication
        Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function (): void {
            Route::post('sign-out', 'signOut')->name('sign-out');
        });

        // Password
        Route::prefix('password')->name('password.')->controller(PasswordController::class)->group(function (): void {
            Route::post('update', 'updatePassword')->name('update');
        });

        // Profile
        Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function (): void {
            Route::get('/',                         'getProfile')->name('get');
            Route::match(['put', 'patch', 'post'], '/', 'updateProfile')->name('update');
        });
    });
