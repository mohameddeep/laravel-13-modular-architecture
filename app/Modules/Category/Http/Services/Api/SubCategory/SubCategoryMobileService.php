<?php

namespace App\Modules\Category\Http\Services\Api\SubCategory;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Category\Http\Requests\Api\SubCategory\StoreSubCategoryRequest;
use App\Modules\Category\Http\Requests\Api\SubCategory\UpdateSubCategoryRequest;
use App\Modules\Category\Http\Resources\SubCategory\SubCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubCategoryMobileService extends SubCategoryService
{
    use Responser;

    public static function platform(): string
    {
        return 'mobile';
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->subCategoryRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, SubCategoryResource::class);
    }

    public function store(StoreSubCategoryRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subCategory = $this->subCategoryRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new SubCategoryResource($subCategory));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $subCategory = $this->subCategoryRepository->getById($id);

        if ($subCategory === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new SubCategoryResource($subCategory));
    }

    public function update(UpdateSubCategoryRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->subCategoryRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $subCategory = $this->subCategoryRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new SubCategoryResource($subCategory));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->subCategoryRepository->delete($id);

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
