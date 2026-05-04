<?php

namespace App\Modules\Base\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface WritableRepositoryInterface
{
    public function create(array $attributes): Model;

    public function insert(array $values): bool;

    /**
     * @param  array<int, array<string, mixed>>  $records
     */
    public function createMany(array $records): Collection;

    public function update(mixed $id, array $attributes): bool;

    public function delete(mixed $id): ?bool;

    public function forceDelete(mixed $id): ?bool;
}
