<?php

namespace App\Modules\Base\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface SoftDeletableRepositoryInterface
{
    public function getTrashed(array $columns = ['*']): Collection;

    public function restoreById(mixed $id): bool;
}
