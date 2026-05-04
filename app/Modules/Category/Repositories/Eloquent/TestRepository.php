<?php

namespace App\Modules\Category\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Category\Models\Test;
use App\Modules\Category\Repositories\TestRepositoryInterface;

class TestRepository extends Repository implements TestRepositoryInterface
{
    public function __construct(Test $model)
    {
        parent::__construct($model);
    }
}
