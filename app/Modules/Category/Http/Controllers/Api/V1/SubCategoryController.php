<?php

namespace App\Modules\Category\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Category\Http\Requests\Api\SubCategory\StoreSubCategoryRequest;
use App\Modules\Category\Http\Requests\Api\SubCategory\UpdateSubCategoryRequest;
use App\Modules\Category\Http\Services\Api\SubCategory\SubCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubCategoryController extends BaseController
{
    public function __construct(
        protected SubCategoryService $subCategoryService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->subCategoryService->index($request);
    }

    public function store(StoreSubCategoryRequest $request): JsonResponse
    {
        return $this->subCategoryService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->subCategoryService->show($id);
    }

    public function update(UpdateSubCategoryRequest $request, int $id): JsonResponse
    {
        return $this->subCategoryService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->subCategoryService->destroy($id);
    }
}
