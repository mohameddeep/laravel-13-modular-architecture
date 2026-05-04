<?php

namespace App\Modules\Category\Http\Services\Api\Category;

use App\Modules\Category\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Modules\Category\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreCategoryRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateCategoryRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
