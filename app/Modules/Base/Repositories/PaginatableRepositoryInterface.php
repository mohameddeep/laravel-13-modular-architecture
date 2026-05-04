<?php

namespace App\Modules\Base\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface PaginatableRepositoryInterface
{
    public function paginate(int $perPage = 15, array $columns = ['*'], ?string $pageName = 'page'): LengthAwarePaginator;

    public function paginateWithQuery(Builder $query, int $perPage = 15, array $columns = ['*'], ?string $pageName = 'page'): LengthAwarePaginator;
}
