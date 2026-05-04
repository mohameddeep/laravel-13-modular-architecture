<?php

namespace App\Modules\Products\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Products\Http\Requests\Api\Product\StoreProductRequest;
use App\Modules\Products\Http\Requests\Api\Product\UpdateProductRequest;
use App\Modules\Products\Http\Services\Api\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->productService->index($request);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return $this->productService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->productService->show($id);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        return $this->productService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->productService->destroy($id);
    }
}
