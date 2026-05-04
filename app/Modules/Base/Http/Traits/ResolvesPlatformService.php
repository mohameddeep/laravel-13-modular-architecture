<?php

namespace App\Modules\Base\Http\Traits;

trait ResolvesPlatformService
{
    /**
     * Bind an abstract service class to the correct platform-specific
     * implementation based on the current request URL.
     *
     * Usage in a module ServiceProvider::register():
     *
     *   $this->bindPlatformService(
     *       ProductService::class,
     *       ProductWebService::class,
     *       ProductMobileService::class,
     *   );
     *
     * The controller type-hints the abstract class; the container resolves
     * the right concrete depending on whether the request hits api/v1/* or mobile/v1/*.
     */
    protected function bindPlatformService(
        string $abstract,
        string $webService,
        string $mobileService,
    ): void {
        $this->app->singleton($abstract, function ($app) use ($webService, $mobileService) {
            $request = $app->make('request');

            $concrete = $request->is('api/v*/mobile/*')
                ? $mobileService
                : $webService;

            return $app->make($concrete);
        });
    }
}
