<div align="center">

# Laravel 13 — Modular Architecture

**A production-ready `app/Modules/*` structure for Laravel 13.**  
Every business domain is a self-contained folder. One command scaffolds everything.

[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-22c55e)](LICENSE)

---

[**Live Docs**](http://laravel-13.test/docs) · [Architecture Overview](#-architecture-overview) · [Quick Start](#-quick-start) · [Commands](#-artisan-commands) · [File Structure](#-generated-file-structure)

</div>

---

## Architecture Overview

Each business area lives under `app/Modules/`. A module owns its **models, migrations, services, controllers, repositories, requests, resources, routes, views, and provider**. Cross-module coupling happens only through shared model imports and the `Base` module utilities.

```
app/Modules/
├── Base/           ← Shared foundation (repository pattern, helpers, make:module)
├── Auth/
├── Category/
│   ├── Models/Category.php
│   └── Models/SubCategory.php      ← Sub-models via make:module-model
├── Products/
└── Orders/
```

**Key benefits:**
- Delete a module → remove its folder + one line in `bootstrap/providers.php`
- Lift a module to another project → copy its folder + register its provider
- Zero manual repository bindings — auto-discovered by `RepositoryServiceProvider`

---

## Quick Start

### 1 — Copy the Base module

Copy `app/Modules/Base/` into your Laravel 13 project.

### 2 — Add helper autoload

```json
// composer.json
"autoload": {
    "psr-4": { "App\\": "app/" },
    "files": [
        "app/Modules/Base/Http/Helpers/helpers.php"
    ]
}
```

```bash
composer dump-autoload
```

### 3 — Register Base providers

```php
// bootstrap/providers.php
return [
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,
    App\Providers\AppServiceProvider::class,
];
```

### 4 — Enable API routes

```php
// bootstrap/app.php
->withRouting(
    web:      __DIR__.'/../routes/web.php',
    api:      __DIR__.'/../routes/api.php',   // ← add this
    commands: __DIR__.'/../routes/console.php',
    health:   '/up',
)
```

### 5 — Verify

```bash
php artisan list make
# You should see: make:module  and  make:module-model
```

---

## Artisan Commands

### `make:module` — Create a new module

```bash
php artisan make:module {name}
    [--model=]        # Custom model name (default: singular of module name)
    [--api]           # Scaffold API surface only
    [--dashboard]     # Scaffold Dashboard surface only
    [--force]         # Overwrite existing files
    [--with-factory]  # Add Eloquent factory
    [--with-tests]    # Add feature test
```

**Examples:**

```bash
# Full module — API + Dashboard
php artisan make:module Products

# API only
php artisan make:module Articles --api

# Dashboard only
php artisan make:module Settings --dashboard

# Custom model name
php artisan make:module Catalog --model=Product

# With factory + tests
php artisan make:module Orders --with-factory --with-tests

# Force overwrite
php artisan make:module Products --force
```

After generation, the command automatically adds the provider line to `bootstrap/providers.php`.

---

### `make:module-model` — Add a model to an existing module

```bash
php artisan make:module-model {module} {model}
    [--api]           # API surface only
    [--dashboard]     # Dashboard surface only
    [--force]         # Overwrite existing files
    [--with-factory]  # Add Eloquent factory
    [--with-tests]    # Add feature test
```

**Examples:**

```bash
# Add SubCategory inside the Category module
php artisan make:module-model Category SubCategory

# API only
php artisan make:module-model Category SubCategory --api

# With factory
php artisan make:module-model Orders OrderItem --with-factory
```

This also automatically appends `bindPlatformService(SubCategoryService::class, ...)` to `CategoryServiceProvider` — no manual wiring needed.

---

## Generated File Structure

Running `php artisan make:module Products` creates:

```
app/Modules/Products/
├── Models/
│   └── Product.php
├── database/
│   ├── migrations/
│   │   └── 2026_01_01_000000_create_products_table.php
│   └── seeders/
│       └── ProductSeeder.php
├── Repositories/
│   ├── ProductRepositoryInterface.php
│   └── Eloquent/
│       └── ProductRepository.php
├── Providers/
│   └── ProductsServiceProvider.php
├── Http/
│   ├── Services/
│   │   ├── Api/Product/
│   │   │   ├── ProductService.php         ← abstract
│   │   │   ├── ProductWebService.php      ← web platform
│   │   │   └── ProductMobileService.php   ← mobile platform
│   │   └── Dashboard/Product/
│   │       └── ProductService.php
│   ├── Controllers/
│   │   ├── Api/V1/ProductController.php
│   │   └── Dashboard/ProductController.php
│   ├── Requests/
│   │   ├── Api/Product/
│   │   │   ├── StoreProductRequest.php
│   │   │   └── UpdateProductRequest.php
│   │   └── Dashboard/Product/
│   │       ├── StoreProductRequest.php
│   │       └── UpdateProductRequest.php
│   └── Resources/
│       └── Product/
│           └── ProductResource.php
└── Routes/
    ├── api/v1/
    │   ├── web.php        → GET api/v1/web/products
    │   └── mobile.php     → GET api/v1/mobile/products
    └── dashboard/
        └── dashboard.php  → GET dashboard/products
```

---

## Route Surfaces

Each module exposes three surfaces. The container resolves the right service automatically — controllers never know which platform they are on.

| Surface | URL Prefix | Route Name Prefix | Resolved Service |
|---------|-----------|------------------|-----------------|
| Web API | `api/v1/web/{resource}` | `api.v1.web.*` | `ProductWebService` |
| Mobile API | `api/v1/mobile/{resource}` | `api.v1.mobile.*` | `ProductMobileService` |
| Dashboard | `dashboard/{resource}` | `dashboard.*` | Dashboard `ProductService` |

---

## Platform Services Pattern

Each API entity has three service classes:

```php
// Abstract — defines the contract
abstract class ProductService
{
    abstract public static function platform(): string;
    abstract public function index(Request $request): JsonResponse;
    abstract public function store(StoreProductRequest $request): JsonResponse;
    // ...
}

// Web implementation
class ProductWebService extends ProductService
{
    public static function platform(): string { return 'website'; }
    // returns JSON via Responser trait
}

// Mobile implementation
class ProductMobileService extends ProductService
{
    public static function platform(): string { return 'mobile'; }
    // same logic, can differ per platform needs
}
```

The module's `ServiceProvider` binds the right one based on the request URL:

```php
use App\Modules\Base\Http\Traits\ResolvesPlatformService;

class ProductsServiceProvider extends ServiceProvider
{
    use ResolvesPlatformService;

    public function register(): void
    {
        $this->bindPlatformService(
            ProductService::class,        // ← what controller injects
            ProductWebService::class,     // ← resolved for api/v1/web/*
            ProductMobileService::class,  // ← resolved for api/v1/mobile/*
        );
    }
}
```

The controller is completely platform-agnostic:

```php
class ProductController extends BaseController
{
    public function __construct(
        protected ProductService $productService // container picks Web or Mobile
    ) {}

    public function index(Request $request): JsonResponse
    {
        return $this->productService->index($request);
    }
}
```

---

## Request Lifecycle

Strict layering — nothing skips a layer. Controllers stay under 30 lines.

```
HTTP Request
     │
     ▼
 Route              Picks the controller
     │
     ▼
 FormRequest         Validates + authorizes
     │
     ▼
 Controller          Forwards to service — nothing else (≤ 30 lines)
     │
     ▼
 Service             Business logic, DB transactions, file uploads
     │
     ▼
 Repository          All Eloquent access — no raw queries elsewhere
     │
     ▼
 Model               Schema, relations, scopes, accessors
```

**A controller must NOT:**
- ❌ Touch the DB directly (`Product::create(...)` in a controller is forbidden)
- ❌ Validate input — use a FormRequest
- ❌ Send notifications or emails
- ❌ Write files or call external services

---

## Repository Pattern

All DB access goes through a repository. Services type-hint the interface, never the concrete class.

```php
// Interface — extends the base composite interface
interface ProductRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Product;
}

// Concrete — extends the base Repository
class ProductRepository extends Repository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model->where('slug', $slug)->first();
    }
}
```

`RepositoryServiceProvider` auto-binds every `XRepositoryInterface → XRepository` pair it finds — no manual binding needed.

**Available base methods:**

| Method | Returns | Description |
|--------|---------|-------------|
| `getAll()` | `Collection` | All rows |
| `getById($id)` | `?Model` | Find by primary key |
| `first()` | `?Model` | First row |
| `query()` | `Builder` | Raw builder for custom filters |
| `create($data)` | `Model` | Insert one row |
| `update($id, $data)` | `bool` | Update by id |
| `delete($id)` | `?bool` | Soft-delete or delete |
| `forceDelete($id)` | `?bool` | Permanent delete |
| `paginate($perPage)` | `LengthAwarePaginator` | Paginated results |
| `paginateWithQuery($query)` | `LengthAwarePaginator` | Paginate a scoped query |
| `getTrashed()` | `Collection` | Only soft-deleted rows |
| `restoreById($id)` | `bool` | Restore a soft-deleted row |

---

## Global Helpers

Autoloaded via `composer.json autoload.files` — available everywhere without a `use` statement.

```php
// Standard JSON success response
return responseSuccess(Http::OK, 'Fetched.', $data);
// → { "status": 200, "message": "Fetched.", "data": {...} }

// Standard JSON failure response
return responseFail(Http::NOT_FOUND, 'Not found.');
// → { "status": 404, "message": "Not found.", "errors": null }

// Paginated response with a JSON resource
return paginatedJsonResponse($paginator, ProductResource::class);
// → { "status": 200, "data": { "items": [...], "meta": { "total": 50, ... } } }

// In a catch block — rolls back DB, logs the error, returns 500
return catchError($e);

// Full URL to a storage file
fileFullPath('products/image.jpg');
// → https://example.com/storage/products/image.jpg
```

---

## Seeding

`DatabaseSeeder` auto-discovers every module seeder — no manual registration ever needed.

```bash
# Seed all modules
php artisan db:seed

# Seed one module only
php artisan db:seed --class="App\Modules\Products\Database\Seeders\ProductSeeder"

# Migrate + seed in one step
php artisan migrate --seed
```

```php
// app/Modules/Products/database/seeders/ProductSeeder.php
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->count(20)->create();
    }
}
```

---

## Base Module — What It Provides

`app/Modules/Base/` is the only module every other module depends on:

| File | Purpose |
|------|---------|
| `Repositories/RepositoryInterface.php` | Composite interface (read + write + paginate + soft-delete) |
| `Repositories/Eloquent/Repository.php` | Abstract base — all CRUD implemented |
| `Http/Helpers/Http.php` | HTTP status code constants (`Http::OK`, `Http::NOT_FOUND`, …) |
| `Http/Helpers/helpers.php` | Global functions: `responseSuccess`, `responseFail`, `catchError`, `paginatedJsonResponse` |
| `Http/Traits/Responser.php` | Trait — clean JSON response helpers for services |
| `Http/Traits/ResolvesPlatformService.php` | Trait — `bindPlatformService()` for module providers |
| `Http/Controllers/BaseController.php` | Abstract controller all API controllers extend |
| `Providers/BaseServiceProvider.php` | Registers `make:module` and `make:module-model` commands |
| `Providers/RepositoryServiceProvider.php` | Auto-discovers + binds all `XRepositoryInterface → XRepository` |
| `Console/Commands/MakeModuleCommand.php` | `php artisan make:module` |
| `Console/Commands/MakeModuleModelCommand.php` | `php artisan make:module-model` |
| `Console/Stubs/` | 23 stub templates used during code generation |

---

## Naming Conventions

| Item | Convention | Example |
|------|-----------|---------|
| Module folder | StudlyCase plural | `Products`, `Quizzes` |
| Model | Singular StudlyCase | `Product`, `Quiz` |
| Repository interface | `{Model}RepositoryInterface` | `ProductRepositoryInterface` |
| Repository concrete | `{Model}Repository` | `ProductRepository` |
| Abstract API service | `{Model}Service` | `ProductService` |
| Web service | `{Model}WebService` | `ProductWebService` |
| Mobile service | `{Model}MobileService` | `ProductMobileService` |
| Dashboard service | `{Model}Service` inside `Services/Dashboard/{Model}/` | `ProductService` |
| Form requests | `Store{Model}Request`, `Update{Model}Request` | `StoreProductRequest` |
| JSON resource | `{Model}Resource` inside `Resources/{Model}/` | `ProductResource` |
| Migration table | `snake_case` plural | `products`, `sub_categories` |
| View namespace | lowercase module name | `products`, `category` |
| Route name | `{surface}.{resource}.{action}` | `api.v1.web.products.index` |
| Service provider | `{Module}ServiceProvider` | `ProductsServiceProvider` |

---

## Quick Reference

```bash
# Create a module
php artisan make:module Products
php artisan make:module Products --with-factory --with-tests
php artisan make:module Articles --api
php artisan make:module Settings --dashboard

# Add a model to an existing module
php artisan make:module-model Category SubCategory
php artisan make:module-model Orders OrderItem --with-factory

# After generating
php artisan migrate
php artisan route:list
php artisan db:seed

# Code style
./vendor/bin/pint app/Modules

# Clear all caches
php artisan optimize:clear
```

---

## Documentation

Full interactive documentation is available at:

```
http://your-app.test/docs
```

---

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
