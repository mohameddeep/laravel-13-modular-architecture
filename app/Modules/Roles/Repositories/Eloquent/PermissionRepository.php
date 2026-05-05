<?php

namespace App\Modules\Roles\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Roles\Models\Permission;
use App\Modules\Roles\Repositories\PermissionRepositoryInterface;

class PermissionRepository extends Repository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}
