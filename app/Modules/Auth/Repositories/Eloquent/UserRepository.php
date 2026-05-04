<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Modules\Base\Repositories\Eloquent\Repository;
use App\Modules\Auth\Models\User;
use App\Modules\Auth\Repositories\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
