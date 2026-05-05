<?php

namespace App\Modules\Roles\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Roles\Models\Role;
use App\Modules\Roles\Repositories\RoleRepositoryInterface;

class RoleRepository extends Repository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
