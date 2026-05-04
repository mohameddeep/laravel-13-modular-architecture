<?php

namespace App\Modules\Category\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Category\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Modules\Category\Http\Services\Api\Category\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->categoryService->index($request);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return $this->categoryService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->categoryService->show($id);
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        return $this->categoryService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->categoryService->destroy($id);
    }
}
