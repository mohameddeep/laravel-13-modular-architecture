<?php

namespace App\Modules\Category\Http\Services\Api\Test;

use App\Modules\Category\Http\Requests\Api\Test\StoreTestRequest;
use App\Modules\Category\Http\Requests\Api\Test\UpdateTestRequest;
use App\Modules\Category\Repositories\TestRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class TestService
{
    public function __construct(
        protected TestRepositoryInterface $testRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreTestRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateTestRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
