<?php

namespace App\Modules\Category\Http\Services\Api\SubCategory;

use App\Modules\Category\Http\Requests\Api\SubCategory\StoreSubCategoryRequest;
use App\Modules\Category\Http\Requests\Api\SubCategory\UpdateSubCategoryRequest;
use App\Modules\Category\Repositories\SubCategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class SubCategoryService
{
    public function __construct(
        protected SubCategoryRepositoryInterface $subCategoryRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreSubCategoryRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateSubCategoryRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
