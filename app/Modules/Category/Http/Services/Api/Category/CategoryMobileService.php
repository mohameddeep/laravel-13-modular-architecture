<?php

namespace App\Modules\Category\Http\Services\Api\Category;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Category\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Modules\Category\Http\Resources\Category\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryMobileService extends CategoryService
{
    use Responser;

    public static function platform(): string
    {
        return 'mobile';
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->categoryRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, CategoryResource::class);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new CategoryResource($category));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->getById($id);

        if ($category === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->categoryRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $category = $this->categoryRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new CategoryResource($category));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->categoryRepository->delete($id);

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
