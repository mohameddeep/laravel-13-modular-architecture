<?php

namespace App\Modules\Category\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Category\Models\SubCategory;
use App\Modules\Category\Repositories\SubCategoryRepositoryInterface;

class SubCategoryRepository extends Repository implements SubCategoryRepositoryInterface
{
    public function __construct(SubCategory $model)
    {
        parent::__construct($model);
    }
}
