<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Base\Repositories\RepositoryInterface;
use App\Modules\Auth\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email, array $relations = []): ?User;

    public function findByPhone(string $phone, array $relations = []): ?User;

    public function getActiveUsers();
}
