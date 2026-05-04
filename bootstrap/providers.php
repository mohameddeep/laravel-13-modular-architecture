<?php

return [
    App\Modules\Base\Providers\BaseServiceProvider::class,
    App\Modules\Base\Providers\RepositoryServiceProvider::class,
    App\Providers\AppServiceProvider::class,

    App\Modules\Products\Providers\ProductsServiceProvider::class,
    App\Modules\Category\Providers\CategoryServiceProvider::class,
];
