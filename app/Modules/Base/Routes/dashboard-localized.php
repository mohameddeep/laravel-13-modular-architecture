<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Localized dashboard (en / ar in URL), same pattern as Canadian project.
| Example: /en/dashboard/..., /ar/dashboard/...
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function (): void {
    require __DIR__.'/../../Auth/Routes/dashboard/dashboard.php';
    require __DIR__.'/../../Roles/Routes/dashboard/dashboard.php';
});
