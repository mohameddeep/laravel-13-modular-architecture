<?php

namespace App\Modules\Roles\Providers;

use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $base = __DIR__.'/..';

        // Dashboard routes are loaded from BaseServiceProvider (localized wrapper).

        $this->loadMigrationsFrom($base.'/database/migrations');

        $views = $base.'/Resources/views';

        if (is_dir($views)) {
            $this->loadViewsFrom($views, 'roles');
        }
    }
}
