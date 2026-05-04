<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;

class AdminRepository extends Repository implements AdminRepositoryInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }
}
