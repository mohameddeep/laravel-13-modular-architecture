<?php

namespace App\Modules\Base\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ReadableRepositoryInterface
{
    public function getAll(array $columns = ['*']): Collection;

    public function getById(mixed $id, array $columns = ['*']): ?Model;

    public function get(mixed $id, array $columns = ['*']): ?Model;

    public function first(array $columns = ['*']): ?Model;

    public function query(): Builder;
}
