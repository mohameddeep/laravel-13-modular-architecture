<?php

namespace App\Modules\Products\Http\Services\Api\Product;

use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Products\Http\Requests\Api\Product\StoreProductRequest;
use App\Modules\Products\Http\Requests\Api\Product\UpdateProductRequest;
use App\Modules\Products\Http\Resources\Product\ProductResource;
use App\Modules\Products\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductService
{
    use Responser;

    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->productRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, ProductResource::class);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $product = $this->productRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new ProductResource($product));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->getById($id);

        if ($product === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->productRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $product = $this->productRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new ProductResource($product));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->productRepository->delete($id);

            if ($deleted === null) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            DB::commit();

            return $this->responseSuccess(Http::OK, __('Deleted.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }
}
