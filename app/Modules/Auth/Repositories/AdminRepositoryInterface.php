<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Models\Admin;
use App\Modules\Base\Repositories\RepositoryInterface;

interface AdminRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?Admin;
}
