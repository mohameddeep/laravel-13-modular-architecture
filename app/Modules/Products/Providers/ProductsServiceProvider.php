<?php

namespace App\Modules\Products\Providers;

use Illuminate\Support\ServiceProvider;

class ProductsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
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
            $this->loadViewsFrom($views, 'products');
        }
    }
}
