<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Base\Http\Traits\ResolvesPlatformService;
use App\Modules\Auth\Http\Services\Api\Auth\AuthMobileService;
use App\Modules\Auth\Http\Services\Api\Auth\AuthService;
use App\Modules\Auth\Http\Services\Api\Auth\AuthWebService;
use Illuminate\Support\ServiceProvider;

use App\Modules\Auth\Http\Services\Api\Admin\AdminMobileService;
use App\Modules\Auth\Http\Services\Api\Admin\AdminService;
use App\Modules\Auth\Http\Services\Api\Admin\AdminWebService;

use App\Modules\Auth\Http\Services\Api\User\UserMobileService;
use App\Modules\Auth\Http\Services\Api\User\UserService;
use App\Modules\Auth\Http\Services\Api\User\UserWebService;

class AuthServiceProvider extends ServiceProvider
{
    use ResolvesPlatformService;

    public function register(): void
    {
        $this->bindPlatformService(UserService::class, UserWebService::class, UserMobileService::class);
        $this->bindPlatformService(AdminService::class, AdminWebService::class, AdminMobileService::class);
        $this->bindPlatformService(
            AuthService::class,
            AuthWebService::class,
            AuthMobileService::class,
        );
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

        $dashboardPath = $base.'/Routes/dashboard';

        if (is_dir($dashboardPath)) {
            foreach (glob($dashboardPath.'/*.php') ?: [] as $file) {
                $this->loadRoutesFrom($file);
            }
        }

        $this->loadMigrationsFrom($base.'/database/migrations');

        $views = $base.'/Resources/views';

        if (is_dir($views)) {
            $this->loadViewsFrom($views, 'auth');
        }
    }
}
