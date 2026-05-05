<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Http\Services\Api\Auth\AuthMobileService;
use App\Modules\Auth\Http\Services\Api\Auth\AuthService;
use App\Modules\Auth\Http\Services\Api\Auth\AuthWebService;
use App\Modules\Auth\Repositories\OtpRepositoryInterface;
use App\Modules\Auth\Repositories\Eloquent\OtpRepository;
use App\Modules\Base\Http\Traits\ResolvesPlatformService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use ResolvesPlatformService;

    public function register(): void
    {
        // Bind OTP repository (auto-bind via RepositoryServiceProvider won't pick it up
        // because OtpRepositoryInterface doesn't match the naming convention exactly)
        $this->app->bind(OtpRepositoryInterface::class, OtpRepository::class);

        // Bind AuthService to the correct platform implementation (web vs mobile)
        $this->bindPlatformService(AuthService::class, AuthWebService::class, AuthMobileService::class);
    }

    public function boot(): void
    {
        $base = __DIR__.'/..';

        $apiV1Path = $base.'/Routes/api/v1';

        if (is_dir($apiV1Path)) {
            foreach (glob($apiV1Path.'/*.php') ?: [] as $file) {
                $this->loadRoutesFrom($file);
            }
        }

        // Dashboard routes are loaded from BaseServiceProvider (localized wrapper).

        $this->loadMigrationsFrom($base.'/database/migrations');

        $views = $base.'/Resources/views';

        if (is_dir($views)) {
            $this->loadViewsFrom($views, 'auth');
        }
    }
}
