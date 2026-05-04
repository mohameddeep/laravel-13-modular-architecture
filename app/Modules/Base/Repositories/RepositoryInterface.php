<?php

namespace App\Modules\Base\Repositories;

interface RepositoryInterface extends PaginatableRepositoryInterface, ReadableRepositoryInterface, SoftDeletableRepositoryInterface, WritableRepositoryInterface
{
}
