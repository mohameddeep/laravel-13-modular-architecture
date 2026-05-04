<?php

namespace App\Modules\Base\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $manualBindings = [];

    public function register(): void
    {
        $this->registerManualBindings();
        $this->autoRegisterModuleRepositories();
    }

    protected function registerManualBindings(): void
    {
        foreach ($this->manualBindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    protected function autoRegisterModuleRepositories(): void
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $repositoriesPath = $moduleDir.DIRECTORY_SEPARATOR.'Repositories';

            if (! File::isDirectory($repositoriesPath)) {
                continue;
            }

            foreach (File::files($repositoriesPath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $basename = $file->getFilenameWithoutExtension();

                if (! str_ends_with($basename, 'RepositoryInterface')) {
                    continue;
                }

                if ($basename === 'RepositoryInterface') {
                    continue;
                }

                $relativeModule = basename($moduleDir);
                $interfaceClass = 'App\\Modules\\'.$relativeModule.'\\Repositories\\'.$basename;

                if (! interface_exists($interfaceClass)) {
                    continue;
                }

                $repositoryClass = str_replace('Interface', '', $basename);
                $concreteClass = 'App\\Modules\\'.$relativeModule.'\\Repositories\\Eloquent\\'.$repositoryClass;

                if (! class_exists($concreteClass)) {
                    continue;
                }

                $reflection = new ReflectionClass($concreteClass);

                if (! $reflection->isInstantiable()) {
                    continue;
                }

                $this->app->bind($interfaceClass, $concreteClass);
            }
        }
    }
}
