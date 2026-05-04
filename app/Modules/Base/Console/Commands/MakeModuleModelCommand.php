<?php

namespace App\Modules\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends Command
{
    protected $signature = 'make:module-model
        {module : Existing module name (StudlyCase), e.g. Category}
        {model  : New model name (singular StudlyCase), e.g. SubCategory}
        {--api       : Scaffold only the API surface}
        {--dashboard : Scaffold only the dashboard surface}
        {--force     : Overwrite files if they already exist}
        {--with-factory : Add a model factory}
        {--with-tests   : Add a minimal feature test}';

    protected $description = 'Add a new model with all its files inside an existing module';

    public function handle(): int
    {
        $moduleName = Str::studly($this->argument('module'));
        $modelName  = Str::studly($this->argument('model'));

        $basePath = app_path("Modules/{$moduleName}");

        if (! File::isDirectory($basePath)) {
            $this->error("Module [{$moduleName}] does not exist at {$basePath}.");
            $this->line("Run: php artisan make:module {$moduleName}");

            return self::FAILURE;
        }

        $onlyApi       = (bool) $this->option('api');
        $onlyDashboard = (bool) $this->option('dashboard');

        if ($onlyApi && $onlyDashboard) {
            $this->error('Use only one of --api or --dashboard, or omit both for full scaffolding.');

            return self::FAILURE;
        }

        $withApi       = ! $onlyDashboard;
        $withDashboard = ! $onlyApi;

        $table         = Str::snake(Str::pluralStudly($modelName));
        $modelVariable = Str::camel($modelName);
        $viewNamespace = Str::lower($moduleName);
        $routeSegment  = str_replace('_', '-', $table);
        $moduleNs      = "App\\Modules\\{$moduleName}";

        $replacements = [
            '{{MODULE_NAME}}'      => $moduleName,
            '{{MODULE_NAMESPACE}}' => $moduleNs,
            '{{MODEL_NAME}}'       => $modelName,
            '{{MODEL_VARIABLE}}'   => $modelVariable,
            '{{TABLE_NAME}}'       => $table,
            '{{VIEW_NAMESPACE}}'   => $viewNamespace,
            '{{ROUTE_SEGMENT}}'    => $routeSegment,
        ];

        $migrationFile = date('Y_m_d_His').'_'.Str::lower(Str::random(4))."_create_{$table}_table.php";
        $modelStub     = $this->option('with-factory') ? 'model_with_factory.stub' : 'model.stub';

        /** @var list<array{stub: string, target: string}> $writes */
        $writes = [
            ['stub' => $modelStub,        'target' => $basePath."/Models/{$modelName}.php"],
            ['stub' => 'migration.stub',   'target' => $basePath.'/database/migrations/'.$migrationFile],
            ['stub' => 'repository_interface.stub', 'target' => $basePath."/Repositories/{$modelName}RepositoryInterface.php"],
            ['stub' => 'repository.stub',  'target' => $basePath."/Repositories/Eloquent/{$modelName}Repository.php"],
            ['stub' => 'seeder.stub',      'target' => $basePath."/database/seeders/{$modelName}Seeder.php"],
        ];

        if ($withApi) {
            $writes = array_merge($writes, [
                ['stub' => 'service_api_abstract.stub', 'target' => $basePath."/Http/Services/Api/{$modelName}/{$modelName}Service.php"],
                ['stub' => 'service_api_web.stub',      'target' => $basePath."/Http/Services/Api/{$modelName}/{$modelName}WebService.php"],
                ['stub' => 'service_api_mobile.stub',   'target' => $basePath."/Http/Services/Api/{$modelName}/{$modelName}MobileService.php"],
                ['stub' => 'controller_api.stub',        'target' => $basePath."/Http/Controllers/Api/V1/{$modelName}Controller.php"],
                ['stub' => 'request_store_api.stub',    'target' => $basePath."/Http/Requests/Api/{$modelName}/Store{$modelName}Request.php"],
                ['stub' => 'request_update_api.stub',   'target' => $basePath."/Http/Requests/Api/{$modelName}/Update{$modelName}Request.php"],
                ['stub' => 'resource.stub',              'target' => $basePath."/Http/Resources/{$modelName}/{$modelName}Resource.php"],
                ['stub' => 'routes_api_web.stub',       'target' => $basePath."/Routes/api/v1/{$routeSegment}_web.php"],
                ['stub' => 'routes_api_mobile.stub',    'target' => $basePath."/Routes/api/v1/{$routeSegment}_mobile.php"],
            ]);
        }

        if ($withDashboard) {
            $writes = array_merge($writes, [
                ['stub' => 'service_dashboard.stub',        'target' => $basePath."/Http/Services/Dashboard/{$modelName}/{$modelName}Service.php"],
                ['stub' => 'controller_dashboard.stub',     'target' => $basePath."/Http/Controllers/Dashboard/{$modelName}Controller.php"],
                ['stub' => 'request_store_dashboard.stub',  'target' => $basePath."/Http/Requests/Dashboard/{$modelName}/Store{$modelName}Request.php"],
                ['stub' => 'request_update_dashboard.stub', 'target' => $basePath."/Http/Requests/Dashboard/{$modelName}/Update{$modelName}Request.php"],
                ['stub' => 'routes_dashboard.stub',          'target' => $basePath."/Routes/dashboard/{$routeSegment}.php"],
                ['stub' => 'layout.stub',                    'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/layout.blade.php"],
                ['stub' => 'view_index.stub',                'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/index.blade.php"],
                ['stub' => 'view_create.stub',               'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/create.blade.php"],
                ['stub' => 'view_edit.stub',                 'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/edit.blade.php"],
            ]);
        }

        if ($this->option('with-factory')) {
            $writes[] = ['stub' => 'factory.stub', 'target' => $basePath."/database/factories/{$modelName}Factory.php"];
        }

        if ($this->option('with-tests') && $withApi) {
            $writes[] = [
                'stub'   => 'test.stub',
                'target' => base_path("tests/Feature/Modules/{$moduleName}/{$modelName}ApiTest.php"),
            ];
        }

        $stubPath = __DIR__.'/../Stubs';

        foreach ($writes as $write) {
            $this->publishStub($stubPath.'/'.$write['stub'], $write['target'], $replacements);
        }

        if ($withApi) {
            $this->appendProviderBinding($basePath, $moduleName, $modelName, $moduleNs);
        }

        $this->components->info("Model [{$modelName}] added to module [{$moduleName}].");
        $this->newLine();
        $this->line('To seed only this model:');
        $this->line("    php artisan db:seed --class=\"{$moduleNs}\\Database\\Seeders\\{$modelName}Seeder\"");

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function publishStub(string $stubFile, string $targetPath, array $replacements): void
    {
        if (! File::exists($stubFile)) {
            $this->warn("Missing stub: {$stubFile}");

            return;
        }

        if (File::exists($targetPath) && ! $this->option('force')) {
            $this->warn("Skipped existing file: {$targetPath}");

            return;
        }

        File::ensureDirectoryExists(dirname($targetPath));

        $contents = File::get($stubFile);

        foreach ($replacements as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        File::put($targetPath, $contents);
    }

    /**
     * Append bindPlatformService() call for the new model into the module's ServiceProvider.
     */
    protected function appendProviderBinding(
        string $basePath,
        string $moduleName,
        string $modelName,
        string $moduleNs,
    ): void {
        $providerPath = $basePath."/Providers/{$moduleName}ServiceProvider.php";

        if (! File::exists($providerPath)) {
            $this->warn("ServiceProvider not found — skipping binding: {$providerPath}");

            return;
        }

        $contents = File::get($providerPath);
        $binding  = "\$this->bindPlatformService({$modelName}Service::class, {$modelName}WebService::class, {$modelName}MobileService::class);";

        if (str_contains($contents, $binding)) {
            return;
        }

        // Add use statements if the trait is already there, else add use trait line too
        $useAbstract = "use {$moduleNs}\\Http\\Services\\Api\\{$modelName}\\{$modelName}MobileService;";
        $useWeb      = "use {$moduleNs}\\Http\\Services\\Api\\{$modelName}\\{$modelName}Service;";
        $useMobile   = "use {$moduleNs}\\Http\\Services\\Api\\{$modelName}\\{$modelName}WebService;";

        // Insert use statements before the class declaration
        $contents = preg_replace(
            '/(^class\s)/m',
            "{$useAbstract}\n{$useWeb}\n{$useMobile}\n\n$1",
            $contents,
            1
        );

        // Insert binding inside register() method body
        $contents = preg_replace(
            '/(public function register\(\): void\s*\{)/m',
            "$1\n        {$binding}",
            $contents,
            1
        );

        File::put($providerPath, $contents);

        $this->components->info("Added platform binding for [{$modelName}] in {$moduleName}ServiceProvider.");
    }
}
