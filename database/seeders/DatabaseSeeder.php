<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call($this->discoverModuleSeeders());
    }

    /**
     * Scan every app/Modules/{X}/database/seeders/ directory and collect
     * all *Seeder classes automatically (one per model, e.g. ProductSeeder).
     *
     * @return list<class-string<Seeder>>
     */
    protected function discoverModuleSeeders(): array
    {
        $modulesPath = app_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return [];
        }

        $seeders = [];

        foreach (File::directories($modulesPath) as $moduleDir) {
            $moduleName  = basename($moduleDir);
            $seedersPath = $moduleDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'seeders';

            if (! File::isDirectory($seedersPath)) {
                continue;
            }

            foreach (File::files($seedersPath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $className = $file->getFilenameWithoutExtension();
                $class = "App\\Modules\\{$moduleName}\\Database\\Seeders\\{$className}";

                if (class_exists($class)) {
                    $seeders[] = $class;
                }
            }
        }

        return $seeders;
    }
}
