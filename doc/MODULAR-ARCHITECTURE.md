# Modular Architecture (Laravel 12)

> A complete reference for the `app/Modules/*` architecture used in this project: how modules are structured, how they are loaded, how a request flows through them, and **how to replicate this exact structure cleanly in another Laravel 12 project** (manually or via an AI agent).

---

## Table of Contents

1. [Why modules?](#1-why-modules)
2. [Folder layout — top level](#2-folder-layout--top-level)
3. [Folder layout — inside a single module](#3-folder-layout--inside-a-single-module)
4. [The `Base` module — what it provides](#4-the-base-module--what-it-provides)
5. [Module registration: two strategies](#5-module-registration-two-strategies)
6. [Request lifecycle (Controller → Service → Repository → Model)](#6-request-lifecycle-controller--service--repository--model)
7. [Routing strategy (api/v1, dashboard, mobile, web)](#7-routing-strategy-apiv1-dashboard-mobile-web)
8. [Naming conventions](#8-naming-conventions)
9. [Clean replication recipe — bootstrap in a new project](#9-clean-replication-recipe--bootstrap-in-a-new-project)
10. [Adding a new module (the `make:module` command)](#10-adding-a-new-module-the-makemodule-command)
11. [Known issues & recommended improvements](#11-known-issues--recommended-improvements)
12. [AI replication prompt (paste-ready)](#12-ai-replication-prompt-paste-ready)

---

## 1. Why modules?

Each business area (Auth, Courses, Quizzes, Discounts, Notifications, …) lives in its own self-contained folder under `app/Modules/`. A module owns its **models, migrations, controllers, services, repositories, requests, resources, routes, views and provider**. Cross-module coupling happens only through:

- Importing a sibling module's **Model** (e.g. `App\Modules\Courses\Models\Lesson`).
- Importing a sibling module's **RepositoryInterface** when you need its data.
- The shared utilities in the **`Base`** module (Repository pattern, traits, helpers).

Result:

- A module can be **deleted** by removing its folder + its line in `bootstrap/providers.php`.
- A module can be **lifted into another project** by copying its folder and registering its provider.
- Each module has its own routes file(s), its own migrations folder and its own views namespace.

---

## 2. Folder layout — top level

```
app/Modules/
├── Base/                # Shared foundation: Repository pattern, traits, helpers, make:module command
├── Auth/
├── Accreditations/
├── Category/
├── Certificates/
├── Courses/
├── Discounts/
├── Earnings/
├── Notifications/
├── QA/
├── Quizzes/
├── Ratings/
└── Structure/
```

Registration is centralized in `bootstrap/providers.php`:

```12:21:bootstrap/providers.php
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,
    App\Modules\Category\Providers\CategoryServiceProvider::class,
    App\Modules\Courses\Providers\CourseServiceProvider::class,
    App\Modules\Discounts\Providers\DiscountServiceProvider::class,
    App\Modules\Earnings\Providers\EarningsServiceProvider::class,
    App\Modules\QA\Providers\QAServiceProvider::class,
    App\Modules\Ratings\Providers\RatingsServiceProvider::class,
    App\Modules\Structure\Providers\StructureServiceProvider::class,
    App\Modules\Certificates\Providers\CertificatesServiceProvider::class,
    App\Modules\Quizzes\Providers\QuizzesServiceProvider::class,
```

> **Order matters** when one module's provider is consumed by another (e.g. `LaravelLocalizationServiceProvider` is registered first because module route files use `LaravelLocalization::setLocale()` for the prefix).

---

## 3. Folder layout — inside a single module

This is the canonical layout used by `Quizzes`, `Courses`, etc.

```
app/Modules/{ModuleName}/
├── Console/
│   └── Commands/                 # Module-specific artisan commands (optional)
├── database/
│   └── migrations/               # Module migrations (loaded by the provider)
├── Enums/                        # PHP 8.1+ enums (e.g. QuizType, GradingType)
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/               # Mobile/public API controllers (versioned)
│   │   └── Dashboard/            # Admin/dashboard controllers
│   ├── Middleware/               # Module-specific middleware (optional)
│   ├── Requests/
│   │   ├── Api/                  # FormRequests for API endpoints
│   │   └── Dashboard/            # FormRequests for dashboard endpoints
│   ├── Resources/                # JsonResource transformers for API responses
│   └── Services/
│       ├── Api/                  # Business logic for API surface
│       └── Dashboard/            # Business logic for Dashboard surface
├── Models/                       # Eloquent models for this module
├── Observers/                    # Model observers (optional)
├── Providers/
│   └── {Module}ServiceProvider.php  # Loads routes/migrations/views, binds repositories
├── Repositories/
│   ├── {Model}RepositoryInterface.php  # Contract
│   └── Eloquent/
│       └── {Model}Repository.php       # Concrete (extends Base\Repositories\Eloquent\Repository)
├── Resources/
│   └── views/                    # Blade views (loaded under namespace, e.g. `quizzes::...`)
└── Routes/
    ├── api/
    │   └── v1/
    │       ├── mobile.php        # `mobile/v1/...` routes
    │       └── web.php           # `api/v1/...` routes (or web SPA routes)
    └── dashboard/
        └── dashboard.php         # `/{locale}/...` admin routes
```

Why split `Services/Api` from `Services/Dashboard`?

- API services return JSON via the `Responser` trait (`responseSuccess`, `responseFail`).
- Dashboard services return Blade views or `RedirectResponse` with flash messages.
- The two surfaces have **different validation, authorization and response contracts** — sharing a single service forces awkward branching, so we keep them separate but reuse the same Repository underneath.

---

## 4. The `Base` module — what it provides

`app/Modules/Base/` is the foundation every other module builds on. It provides:

### 4.1 Repository pattern (segregated interfaces)

```text
Base/Repositories/
├── ReadableRepositoryInterface.php       # getAll, getById, get, first, query, ...
├── WritableRepositoryInterface.php       # create, insert, createMany, update, delete, forceDelete
├── PaginatableRepositoryInterface.php    # paginate, paginateWithQuery
├── SoftDeletableRepositoryInterface.php  # getTrashed, restoreById
├── RepositoryInterface.php               # Composite of the four above
└── Eloquent/Repository.php               # Abstract base implementation
```

Module repositories simply do:

```php
namespace App\Modules\Quizzes\Repositories;

use App\Modules\Base\Repositories\RepositoryInterface;

interface QuizRepositoryInterface extends RepositoryInterface { /* domain methods */ }
```

```php
namespace App\Modules\Quizzes\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Quizzes\Models\Quiz;
use App\Modules\Quizzes\Repositories\QuizRepositoryInterface;

class QuizRepository extends Repository implements QuizRepositoryInterface
{
    public function __construct(Quiz $model) { parent::__construct($model); }
}
```

### 4.2 Traits

| Trait                            | Purpose                                                            |
|----------------------------------|--------------------------------------------------------------------|
| `Base\Http\Traits\Responser`     | `responseSuccess()` / `responseFail()` JSON helpers (handles paginators & resource collections cleanly) |
| `Base\Http\Traits\FileTrait`     | Upload, delete, replace files in storage                           |
| `Base\Http\Traits\FileManager`   | Higher-level file workflow                                         |
| `Base\Http\Traits\Filterable`    | `searchableFields` query helpers on models                         |
| `Base\Http\Traits\HasUuid`       | Auto-fill `uuid` on model `creating`                               |
| `Base\Http\Traits\LanguageToggle`| Multi-language attribute resolution                                |

### 4.3 Helper functions (`Base/Http/Helpers/helpers.php`)

Autoloaded via `composer.json` `"autoload.files"`. Important ones:

- `responseSuccess($status, $message, $data)` / `responseFail(...)`
- `paginatedJsonResponse(...)`
- `catchError(\Throwable $e)` — rolls back DB, logs, returns standard error JSON
- `fileFullPath($path)`, `formatDate($date)`

> **Note:** `store_model`, `update_model`, `delete_model` are **deprecated** in this codebase. Prefer transactions inside services. See [Improvements](#11-known-issues--recommended-improvements).

### 4.4 Constants and exceptions

- `Base\Http\Helpers\Http` — every HTTP status code as a class constant.
- `Base\Exceptions\Handler` — central exception → JSON converter.

### 4.5 Middleware

- `Authenticate`, `LocalizeApi`, `SetLocale`, `SetLocaleFromHeader` — locale-aware authentication primitives.

### 4.6 The `make:module` artisan command

`app/Modules/Base/Console/Commands/MakeModuleCommand.php` scaffolds an entire module from stubs in **one call**:

```bash
php artisan make:module Products
```

It creates: directories, model, migration, repository + interface, API service, Dashboard service, API & Dashboard FormRequests (Store + Update), API & Dashboard controllers, JsonResource + DetailsResource, four route files (`api/v1/mobile.php`, `dashboard.php`, `web.php`, `console.php`), service provider, factory, seeder, and Blade views. Optional flags: `--with-tests`, `--with-factory`, `--api`, `--dashboard`, `--force`, `--model=Foo`.

### 4.7 `RepositoryServiceProvider` — auto-discovery

`Base\Providers\RepositoryServiceProvider` scans every module's `Repositories/` (and `Repositories/Eloquent/`) folder and **auto-binds** every `XRepositoryInterface` → `XRepository` it finds. You only need to write a per-module ServiceProvider when you have **non-trivial bindings or boot logic**.

```26:32:app/Modules/Base/Providers/RepositoryServiceProvider.php
    public function register(): void
    {
        // Register manual bindings if provided
        $this->registerManualBindings();

        // Auto-discover and register module repositories
        $this->autoRegisterModuleRepositories();
    }
```

### 4.8 `BaseServiceProvider` — registers commands, routes, views, components

Loads its own `Routes/web.php`, registers `make:module` and `make:module-model`, registers Blade component namespaces (`<x-dashboard.button />`, etc.), and loads `Base` migrations.

---

## 5. Module registration: two strategies

The codebase supports **two** registration models:

### 5.1 Strategy A — Per-module ServiceProvider (used today)

Each module ships its own provider that explicitly:

- Binds repository interface(s).
- `loadRoutesFrom()` for every route file.
- `loadMigrationsFrom()` for the migrations folder.
- `loadViewsFrom()` under the module namespace.
- (Optionally) `publishes()` views/migrations.

Example (`Quizzes`):

```16:62:app/Modules/Quizzes/Providers/QuizzesServiceProvider.php
class QuizzesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            QuizRepositoryInterface::class,
            QuizRepository::class
        );
    }

    public function boot(): void
    {
        // Load routes: api/v1 and dashboard
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

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'quizzes');

        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/quizzes'),
        ], 'quizzes-views');
    }
}
```

The provider is then added to `bootstrap/providers.php`.

### 5.2 Strategy B — Auto-discovery (partially active)

`RepositoryServiceProvider` already auto-binds repositories without any per-module wiring. The `Base` README documents extending this pattern to also auto-load **routes / migrations / views / translations** so new modules need **zero** registration.

> The codebase currently uses Strategy A for explicit control. When porting, you can pick A, B, or a hybrid (auto-discovery for boilerplate; explicit provider only when custom bindings are needed).

---

## 6. Request lifecycle (Controller → Service → Repository → Model)

```
HTTP request
   │
   ▼
Route (in Modules/{X}/Routes/...)
   │
   ▼
FormRequest                     ← validation + authorization
   │
   ▼
Controller (thin)               ← only forwards $request->validated() to the service
   │
   ▼
Service (Api or Dashboard)      ← business logic, DB transactions, file uploads
   │
   ▼
Repository (interface)          ← all DB access
   │
   ▼
Eloquent Model                  ← schema, relations, scopes, accessors
```

### Concrete example — `Quizzes` create flow

1. **Route** (`app/Modules/Quizzes/Routes/dashboard/dashboard.php`):
   ```php
   Route::post('/', [QuizController::class, 'store'])->name('store');
   ```
2. **FormRequest** (`Http/Requests/Dashboard/StoreQuizRequest.php`) validates input.
3. **Controller** simply delegates:
   ```php
   public function store(StoreQuizRequest $request)
   {
       return $this->quizService->store($request);
   }
   ```
4. **Service** (`Http/Services/Dashboard/Quiz/QuizService.php`) wraps the work in `DB::beginTransaction()`, calls the repository, handles file uploads, returns a redirect with flash.
5. **Repository** (`Repositories/Eloquent/QuizRepository.php`) does the actual `$model->create($data)`.
6. **Model** (`Models/Quiz.php`) defines fillable, casts, relations, observers.

### What the controller is **not** allowed to do

- ❌ Touch the DB directly (no `Quiz::create(...)` in a controller).
- ❌ Validate (use a FormRequest).
- ❌ Send notifications, write files, or call external services.

That keeps controllers ≤ 30 lines and lets the service layer be unit-tested without HTTP.

---

## 7. Routing strategy (api/v1, dashboard, mobile, web)

Each module declares **at most three** route files, each with a clear surface:

| File                                  | Purpose                                          | Typical prefix / middleware                                      |
|---------------------------------------|--------------------------------------------------|------------------------------------------------------------------|
| `Routes/api/v1/web.php`               | Versioned REST API for SPA / web client          | `prefix('api/v1')`, `middleware(['api'])`                        |
| `Routes/api/v1/mobile.php`            | Versioned REST API for mobile clients            | `prefix('mobile/v1')`, `middleware(['api', 'auth:api'])`         |
| `Routes/dashboard/dashboard.php`      | Admin/back-office Blade pages                    | `prefix(LaravelLocalization::setLocale())`, `middleware(['web', 'auth:manager', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'])` |

### Route-name convention

Always **named**:

- `mobile.v1.placement.start`
- `dashboard.quizzes.index`
- `dashboard.quizzes.lessons` (AJAX endpoints share the resource's name prefix)

This guarantees that views can use `route(...)` instead of building URLs by string concat (which silently breaks when the locale prefix changes — see the bug fixed in `create.blade.php` / `edit.blade.php`).

### Locale prefix

The dashboard route group is wrapped with `LaravelLocalization::setLocale()` so every URL becomes `/{locale}/...`. **Always generate URLs through `route()` or `LaravelLocalization::localizeUrl(...)`** to keep the locale prefix correct in JavaScript / AJAX too.

---

## 8. Naming conventions

| Item                        | Convention                                                      | Example                                                |
|-----------------------------|-----------------------------------------------------------------|--------------------------------------------------------|
| Module folder               | `StudlyCase` plural for collections, singular when conceptual   | `Quizzes`, `Courses`, `Auth`                           |
| Namespace root              | `App\Modules\{ModuleName}`                                      | `App\Modules\Quizzes`                                  |
| Model                       | Singular `StudlyCase`                                           | `Quiz`, `Lesson`                                       |
| Repository                  | `{Model}Repository` + `{Model}RepositoryInterface`              | `QuizRepository`, `QuizRepositoryInterface`            |
| Service                     | `{Model}Service` (separate Api/Dashboard subfolder)             | `Api\Quiz\QuizService`, `Dashboard\Quiz\QuizService`   |
| FormRequest                 | `Store{Model}Request`, `Update{Model}Request`                   | `StoreQuizRequest`                                     |
| Controller                  | `{Model}Controller`                                             | `QuizController`                                       |
| Resource                    | `{Model}Resource`, `{Model}DetailsResource`, `{Model}Collection`| `QuizResource`, `QuizCollection`                       |
| Migration table             | `snake_case` plural                                             | `quizzes`, `question_options`                          |
| Blade view namespace        | lowercase module name                                           | `view('quizzes::dashboard.create')`                    |
| Service Provider            | `{Module}ServiceProvider`                                       | `QuizzesServiceProvider`                               |
| Route name                  | `{surface}.{resource}.{action}`                                 | `dashboard.quizzes.lessons`                            |

---

## 9. Clean replication recipe — bootstrap in a new project

Use this when you want to lift the architecture into a fresh Laravel 12 app **without copying any business module**.

### Step 1 — Create a new Laravel 12 project

```bash
composer create-project laravel/laravel my-new-app "^12.0"
cd my-new-app
```

### Step 2 — Configure PSR-4 + helper autoload

Edit `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    },
    "files": [
        "app/Modules/Base/Http/Helpers/helpers.php"
    ]
}
```

Then:

```bash
composer dump-autoload
```

> The PSR-4 root `App\\` is enough — the module namespaces `App\Modules\X\...` are reached through the same root. There is **no** need to add a separate `Modules\\` namespace unless you also move the folder out of `app/`.

### Step 3 — Copy the `Base` module

Copy **only** `app/Modules/Base/` into the new project. That brings in:

- Repository pattern (interfaces + Eloquent base).
- Traits, helpers, HTTP constants.
- `BaseServiceProvider`, `RepositoryServiceProvider`.
- The `make:module` artisan command and all its stubs.

### Step 4 — Register Base providers

Edit `bootstrap/providers.php`:

```php
<?php

return [
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,
    App\Providers\AppServiceProvider::class,
];
```

> If your modules rely on locale-aware route prefixes, also register `Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class` **first** and install the package: `composer require mcamara/laravel-localization`.

### Step 5 — Verify the command is registered

```bash
php artisan list make
# expect to see: make:module
```

### Step 6 — Generate your first module

```bash
php artisan make:module Products --with-factory
```

You'll get a complete `app/Modules/Products/` tree.

### Step 7 — Wire the new module's provider

Add it to `bootstrap/providers.php`:

```php
App\Modules\Products\Providers\ProductsServiceProvider::class,
```

(Or rely on `RepositoryServiceProvider` auto-discovery and skip this step if you only need repository bindings.)

### Step 8 — Migrate

```bash
php artisan migrate
```

Done — controller, routes, repository binding, views and migration are all live.

---

## 10. Adding a new module (the `make:module` command)

```bash
# Default: API + Dashboard scaffolding
php artisan make:module Categories

# API-only
php artisan make:module Articles --api

# Dashboard-only
php artisan make:module Settings --dashboard

# Custom model name
php artisan make:module Catalog --model=Product

# Add tests + factory + force overwrite
php artisan make:module Orders --with-tests --with-factory --force
```

After scaffolding:

1. Edit `app/Modules/{Name}/database/migrations/*_create_{table}_table.php` and add columns.
2. Fill `protected $fillable` / `protected $casts` in the Model.
3. Fill validation rules in the four FormRequests.
4. Implement business logic in the API & Dashboard service classes (the stubs already include a transactional CRUD skeleton + structured logging).
5. Run `php artisan migrate`.

---

## 11. Known issues & recommended improvements

These are concrete things to **clean up while replicating** the structure in another project. When prompting an AI to port the architecture, instruct it to apply each of these.

### 11.1 Standardize the migrations folder casing

Some modules use `database/migrations/` (lowercase, e.g. `Quizzes`) and others use `Database/Migrations/`. Pick **one** (the stub uses `Database/Migrations/`) and stick to it. Update the per-module `loadMigrationsFrom(...)` accordingly.

### 11.2 Drop deprecated helpers

`store_model()`, `update_model()`, `delete_model()` are marked `@deprecated`. Don't carry them to the new project. Use **service-level transactions** instead (the `make:module` API/Dashboard service stubs already do this with `DB::beginTransaction()` / `commit` / `rollBack`).

### 11.3 Always use `route()` in views and JS

Anywhere a Blade template generates a URL for an AJAX call, build it from the named route, **not** from `url('hardcoded/path')`. The lesson dropdown in `Quizzes/create.blade.php` originally broke because `url('dashboard/quizzes/lessons')` ignored the locale prefix. Pattern to use:

```blade
<script>
const lessonsBaseUrl = "{{ route('dashboard.quizzes.lessons', ['courseId' => 0]) }}".replace(/\/0$/, '');
$.get(lessonsBaseUrl + '/' + courseId, ...);
</script>
```

### 11.4 Prefer auto-discovery for routes/migrations/views

The current per-module providers all duplicate the same `loadRoutesFrom() / loadMigrationsFrom() / loadViewsFrom()` boilerplate. Extract it into `BaseServiceProvider::loadModules()` (already documented in `Base/README.md`). Then 90% of module providers become empty.

### 11.5 Move the `Console/Commands` into the module that owns them

Module-specific commands should live under `app/Modules/{X}/Console/Commands` and be registered in that module's provider — not in `app/Console/Kernel.php`.

### 11.6 Consistent `use` ordering and docblocks

The `make:module` stubs already enforce alphabetized imports and a docblock above each public method. Run `vendor/bin/pint` after generating modules to lock this in:

```bash
./vendor/bin/pint app/Modules
```

### 11.7 Don't ship `laravel/sail` if you use a custom Docker setup

The repo includes a custom Dockerfile + `docker-compose.yml` (PHP 8.3-FPM, Nginx, MySQL 8, Redis, Node 22). If you keep that, you can drop `laravel/sail` from `require-dev` to avoid confusion. (Optional — it is harmless if left in place.)

### 11.8 Type-hint repository **interfaces**, never concrete classes

In services and controllers, always inject `XRepositoryInterface`. The `RepositoryServiceProvider` resolves the binding. This keeps the module testable with in-memory fakes.

### 11.9 Per-surface FormRequests

Even if API and Dashboard rules are identical today, keep the two separate FormRequest classes (`Http/Requests/Api/...` vs `Http/Requests/Dashboard/...`). They will diverge — it always happens. The `make:module` command already creates both.

### 11.10 Add `--dry-run` to `make:module`

Quality-of-life: extend `MakeModuleCommand` with a `--dry-run` option that prints the list of files it **would** create. Useful when piping the command from CI or AI tools.

---

## 12. AI replication prompt (paste-ready)

Use this prompt verbatim when asking an AI agent (Cursor, Claude, Copilot Chat, …) to port the architecture into another Laravel 12 project. It encodes all the rules above so the agent does **exactly** what we did here.

> **Prompt:**
>
> You are working in a fresh Laravel 12 project. Set up a modular architecture identical to the one described in `docs/MODULAR-ARCHITECTURE.md` of the source project. Follow these rules **strictly**:
>
> 1. **Do not modify any business logic** that already exists in the target project. Only **add** files; do not refactor unrelated code.
> 2. Create `app/Modules/Base/` with the following sub-tree (copy from the source project verbatim, keeping namespaces under `App\Modules\Base\...`):
>    - `Console/Commands/MakeModuleCommand.php` (and `MakeModuleModelCommand.php` if present).
>    - `Exceptions/Handler.php`.
>    - `Http/Controllers/BaseController.php`.
>    - `Http/Helpers/{Http.php, helpers.php}`.
>    - `Http/Middleware/{Authenticate.php, LocalizeApi.php, SetLocale.php, SetLocaleFromHeader.php}`.
>    - `Http/Traits/{Responser.php, FileTrait.php, FileManager.php, Filterable.php, HasUuid.php, LanguageToggle.php}`.
>    - `Providers/{BaseServiceProvider.php, RepositoryServiceProvider.php}`.
>    - `Repositories/{ReadableRepositoryInterface.php, WritableRepositoryInterface.php, PaginatableRepositoryInterface.php, RepositoryInterface.php, Eloquent/Repository.php}`.
>    - `Resources/views/` for shared dashboard layouts and Blade components, plus error pages.
>    - `Rules/{ArabicOnly.php, EnglishOnly.php}` if present.
>    - `Routes/web.php`.
>    - `View/Components/Dashboard/Layouts/Breadcrumb.php` if present.
>    - `README.md` (the source `Base/README.md`).
> 3. Edit `composer.json`:
>    - Keep `"App\\": "app/"` under `autoload.psr-4`.
>    - Add `"app/Modules/Base/Http/Helpers/helpers.php"` to `autoload.files`.
>    - Run `composer dump-autoload`.
> 4. Edit `bootstrap/providers.php` so the order is:
>    1. `Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class` (only if locale-prefixed routes are used; install the package via composer if so).
>    2. `App\Modules\Base\Providers\BaseServiceProvider::class`.
>    3. `App\Modules\Base\Providers\RepositoryServiceProvider::class`.
>    4. `App\Providers\AppServiceProvider::class`.
> 5. Verify by running `php artisan list make` and confirming `make:module` is registered.
> 6. When generating new modules, **always** use `php artisan make:module {Name}`. Never hand-roll the directory tree. Pass `--with-factory` and `--with-tests` if the module needs them. Pass `--api` or `--dashboard` to scaffold only one surface.
> 7. After each `make:module`, append the new provider line to `bootstrap/providers.php` (unless you rely solely on `RepositoryServiceProvider` auto-discovery and the module needs no other boot logic).
> 8. Apply the **improvements** listed in section 11 of the architecture doc:
>    - Use `Database/Migrations/` casing consistently in every module.
>    - Do **not** call the deprecated `store_model` / `update_model` / `delete_model` helpers; use service-level `DB::beginTransaction()` blocks (the stubs already do).
>    - In every Blade view that builds a URL for AJAX, use `route(...)` or `LaravelLocalization::localizeUrl(...)`. Never hardcode `url('dashboard/...')`.
>    - In services and controllers, type-hint the repository **interface**, never the concrete class.
>    - Keep API services (under `Http/Services/Api`) returning JSON via `Responser`, and Dashboard services (under `Http/Services/Dashboard`) returning views/redirects with flashed messages.
>    - Run `vendor/bin/pint app/Modules` after generation.
> 9. Migrations always live under each module (`app/Modules/{X}/Database/Migrations/`) and are loaded by that module's provider. Do **not** put them in the global `database/migrations/` folder.
> 10. Before declaring "done", run:
>     - `php artisan optimize:clear`
>     - `php artisan route:list` (no exceptions)
>     - `php artisan migrate --pretend` (no errors)
>
> Deliverable: a Laravel 12 project where `php artisan make:module Foo` creates a fully-wired module that passes `php artisan route:list` and `php artisan migrate` without touching any other file.

---

## Quick reference card

```text
Create module ............... php artisan make:module Products
Create with tests ........... php artisan make:module Products --with-tests --with-factory
Create API-only ............. php artisan make:module Articles --api
Create Dashboard-only ....... php artisan make:module Settings --dashboard
Force overwrite ............. php artisan make:module Products --force

Style fix ................... ./vendor/bin/pint app/Modules
Verify routes ............... php artisan route:list
Run migrations .............. php artisan migrate
Clear caches ................ php artisan optimize:clear
Dump autoload ............... composer dump-autoload
```
