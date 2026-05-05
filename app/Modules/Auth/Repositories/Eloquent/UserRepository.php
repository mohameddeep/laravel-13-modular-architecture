<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Modules\Auth\Models\User;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use App\Modules\Base\Repositories\Eloquent\Repository;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email, array $relations = []): ?User
    {
        return $this->model->newQuery()
            ->with($relations)
            ->where('email', $email)
            ->first();
    }

    public function findByPhone(string $phone, array $relations = []): ?User
    {
        return $this->model->newQuery()
            ->with($relations)
            ->where('phone', $phone)
            ->first();
    }

    public function getActiveUsers()
    {
        return $this->model->newQuery()->where('is_active', true);
    }
}
