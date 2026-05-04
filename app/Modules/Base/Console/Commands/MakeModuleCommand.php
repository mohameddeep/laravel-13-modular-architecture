<?php

namespace App\Modules\Base\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module
        {name : Module folder name (StudlyCase), e.g. Products}
        {--model= : Primary Eloquent model (singular StudlyCase), defaults from module name}
        {--api : Scaffold only the API surface}
        {--dashboard : Scaffold only the dashboard surface}
        {--force : Overwrite files if they exist}
        {--with-factory : Add a model factory}
        {--with-tests : Add a minimal feature test}';

    protected $description = 'Scaffold app/Modules/{Name} with routes, repository, services, and controllers';

    public function handle(): int
    {
        $moduleName = Str::studly($this->argument('name'));
        $modelOption = $this->option('model');
        $modelName = $modelOption ? Str::studly($modelOption) : Str::singular($moduleName);
        $onlyApi = (bool) $this->option('api');
        $onlyDashboard = (bool) $this->option('dashboard');

        if ($onlyApi && $onlyDashboard) {
            $this->error('Use only one of --api or --dashboard, or omit both for full scaffolding.');

            return self::FAILURE;
        }

        $withApi = ! $onlyDashboard;
        $withDashboard = ! $onlyApi;

        $table = Str::snake(Str::pluralStudly($modelName));
        $modelVariable = Str::camel($modelName);
        $viewNamespace = Str::lower($moduleName);
        $routeSegment = str_replace('_', '-', $table);
        $moduleNs = 'App\\Modules\\'.$moduleName;
        $basePath = app_path('Modules/'.$moduleName);

        if (File::isDirectory($basePath) && ! $this->option('force')) {
            $this->error("Module directory already exists: {$basePath}. Pass --force to overwrite individual files.");

            return self::FAILURE;
        }

        File::ensureDirectoryExists($basePath);

        $replacements = [
            '{{MODULE_NAME}}' => $moduleName,
            '{{MODULE_NAMESPACE}}' => $moduleNs,
            '{{MODEL_NAME}}' => $modelName,
            '{{MODEL_VARIABLE}}' => $modelVariable,
            '{{TABLE_NAME}}' => $table,
            '{{VIEW_NAMESPACE}}' => $viewNamespace,
            '{{ROUTE_SEGMENT}}' => $routeSegment,
        ];

        $migrationFile = date('Y_m_d_His').'_'.Str::lower(Str::random(4))."_create_{$table}_table.php";

        $modelStub    = $this->option('with-factory') ? 'model_with_factory.stub' : 'model.stub';
        $providerStub = $withApi ? 'provider.stub' : 'provider_dashboard_only.stub';

        /** @var list<array{stub: string, target: string}> $writes */
        $writes = [
            ['stub' => $modelStub,    'target' => $basePath."/Models/{$modelName}.php"],
            ['stub' => 'migration.stub', 'target' => $basePath.'/database/migrations/'.$migrationFile],
            ['stub' => 'repository_interface.stub', 'target' => $basePath."/Repositories/{$modelName}RepositoryInterface.php"],
            ['stub' => 'repository.stub', 'target' => $basePath."/Repositories/Eloquent/{$modelName}Repository.php"],
            ['stub' => $providerStub, 'target' => $basePath."/Providers/{$moduleName}ServiceProvider.php"],
            ['stub' => 'seeder.stub', 'target' => $basePath."/database/seeders/{$modelName}Seeder.php"],
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
                ['stub' => 'routes_api_web.stub',       'target' => $basePath.'/Routes/api/v1/web.php'],
                ['stub' => 'routes_api_mobile.stub',    'target' => $basePath.'/Routes/api/v1/mobile.php'],
            ]);
        }

        if ($withDashboard) {
            $writes = array_merge($writes, [
                ['stub' => 'service_dashboard.stub', 'target' => $basePath."/Http/Services/Dashboard/{$modelName}/{$modelName}Service.php"],
                ['stub' => 'controller_dashboard.stub', 'target' => $basePath."/Http/Controllers/Dashboard/{$modelName}Controller.php"],
                ['stub' => 'request_store_dashboard.stub', 'target' => $basePath."/Http/Requests/Dashboard/{$modelName}/Store{$modelName}Request.php"],
                ['stub' => 'request_update_dashboard.stub', 'target' => $basePath."/Http/Requests/Dashboard/{$modelName}/Update{$modelName}Request.php"],
                ['stub' => 'routes_dashboard.stub', 'target' => $basePath.'/Routes/dashboard/dashboard.php'],
                ['stub' => 'layout.stub',     'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/layout.blade.php"],
                ['stub' => 'view_index.stub', 'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/index.blade.php"],
                ['stub' => 'view_create.stub','target' => $basePath."/Resources/views/dashboard/{$routeSegment}/create.blade.php"],
                ['stub' => 'view_edit.stub',  'target' => $basePath."/Resources/views/dashboard/{$routeSegment}/edit.blade.php"],
            ]);
        }

        if ($this->option('with-factory')) {
            $writes[] = ['stub' => 'factory.stub', 'target' => $basePath."/database/factories/{$modelName}Factory.php"];
        }

        if ($this->option('with-tests') && $withApi) {
            $writes[] = [
                'stub' => 'test.stub',
                'target' => base_path("tests/Feature/Modules/{$moduleName}/{$modelName}ApiTest.php"),
            ];
        }

        $stubPath = __DIR__.'/../Stubs';

        foreach ($writes as $write) {
            $this->publishStub($stubPath.'/'.$write['stub'], $write['target'], $replacements);
        }

        $this->registerProviderHint($moduleName);

        $this->components->info("Module [{$moduleName}] scaffolded at app/Modules/{$moduleName}");

        $this->newLine();
        $this->line('To seed only this module:');
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

    protected function registerProviderHint(string $moduleName): void
    {
        $providersFile = base_path('bootstrap/providers.php');

        if (! File::exists($providersFile)) {
            return;
        }

        $fqcn = "App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider::class";
        $contents = File::get($providersFile);

        if (str_contains($contents, $fqcn)) {
            return;
        }

        // Auto-insert before the closing bracket
        $updated = str_replace(
            '];',
            "    {$fqcn},\n];",
            $contents
        );

        File::put($providersFile, $updated);

        $this->components->info("Registered {$fqcn} in bootstrap/providers.php");
    }
}
