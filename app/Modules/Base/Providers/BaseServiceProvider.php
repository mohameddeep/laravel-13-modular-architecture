<?php

namespace App\Modules\Base\Providers;

use App\Modules\Base\Console\Commands\MakeModuleCommand;
use App\Modules\Base\Console\Commands\MakeModuleModelCommand;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModuleCommand::class,
                MakeModuleModelCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/dashboard-localized.php');

        $migrations = __DIR__.'/../database/migrations';

        if (is_dir($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }

        $views = __DIR__.'/../Resources/views';

        if (is_dir($views)) {
            $this->loadViewsFrom($views, 'base');
        }
    }
}
