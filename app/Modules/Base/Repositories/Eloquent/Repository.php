<?php

namespace App\Modules\Base\Repositories\Eloquent;

use App\Modules\Base\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Repository implements RepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->get($columns);
    }

    public function getById(mixed $id, array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->find($id, $columns);
    }

    public function get(mixed $id, array $columns = ['*']): ?Model
    {
        return $this->getById($id, $columns);
    }

    public function first(array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->first($columns);
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function create(array $attributes): Model
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function insert(array $values): bool
    {
        return $this->model->newQuery()->insert($values);
    }

    public function createMany(array $records): Collection
    {
        $models = new Collection;

        foreach ($records as $record) {
            $models->push($this->create($record));
        }

        return $models;
    }

    public function update(mixed $id, array $attributes): bool
    {
        $model = $this->getById($id);

        if ($model === null) {
            return false;
        }

        return (bool) $model->update($attributes);
    }

    public function delete(mixed $id): ?bool
    {
        $model = $this->getById($id);

        if ($model === null) {
            return null;
        }

        return $model->delete();
    }

    public function forceDelete(mixed $id): ?bool
    {
        $model = $this->getById($id);

        if ($model === null) {
            return null;
        }

        if (in_array(SoftDeletes::class, class_uses_recursive($model), true)) {
            return (bool) $model->forceDelete();
        }

        return (bool) $model->delete();
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], ?string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage, $columns, $pageName);
    }

    public function paginateWithQuery(Builder $query, int $perPage = 15, array $columns = ['*'], ?string $pageName = 'page'): LengthAwarePaginator
    {
        return $query->paginate($perPage, $columns, $pageName);
    }

    public function getTrashed(array $columns = ['*']): Collection
    {
        $query = $this->model->newQuery();

        if (in_array(SoftDeletes::class, class_uses_recursive($this->model), true)) {
            return $query->onlyTrashed()->get($columns);
        }

        return new Collection;
    }

    public function restoreById(mixed $id): bool
    {
        if (! in_array(SoftDeletes::class, class_uses_recursive($this->model), true)) {
            return false;
        }

        $model = $this->model->newQuery()->onlyTrashed()->find($id);

        if ($model === null) {
            return false;
        }

        return (bool) $model->restore();
    }
}
