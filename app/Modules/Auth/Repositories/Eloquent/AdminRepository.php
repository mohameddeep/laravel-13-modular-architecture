<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Modules\Auth\Models\Admin;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use App\Modules\Base\Repositories\Eloquent\Repository;

class AdminRepository extends Repository implements AdminRepositoryInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?Admin
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }
}
