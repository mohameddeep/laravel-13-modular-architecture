<?php

namespace App\Modules\Category\Providers;

use App\Modules\Base\Http\Traits\ResolvesPlatformService;
use App\Modules\Category\Http\Services\Api\Category\CategoryMobileService;
use App\Modules\Category\Http\Services\Api\Category\CategoryService;
use App\Modules\Category\Http\Services\Api\Category\CategoryWebService;
use Illuminate\Support\ServiceProvider;

use App\Modules\Category\Http\Services\Api\SubCategory\SubCategoryMobileService;
use App\Modules\Category\Http\Services\Api\SubCategory\SubCategoryService;
use App\Modules\Category\Http\Services\Api\SubCategory\SubCategoryWebService;

use App\Modules\Category\Http\Services\Api\Test\TestMobileService;
use App\Modules\Category\Http\Services\Api\Test\TestService;
use App\Modules\Category\Http\Services\Api\Test\TestWebService;

class CategoryServiceProvider extends ServiceProvider
{
    use ResolvesPlatformService;

    public function register(): void
    {
        $this->bindPlatformService(TestService::class, TestWebService::class, TestMobileService::class);
        $this->bindPlatformService(SubCategoryService::class, SubCategoryWebService::class, SubCategoryMobileService::class);
        $this->bindPlatformService(
            CategoryService::class,
            CategoryWebService::class,
            CategoryMobileService::class,
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
            $this->loadViewsFrom($views, 'category');
        }
    }
}
