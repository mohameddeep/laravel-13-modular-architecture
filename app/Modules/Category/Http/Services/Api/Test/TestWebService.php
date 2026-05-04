<?php

namespace App\Modules\Category\Http\Services\Api\Test;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Category\Http\Requests\Api\Test\StoreTestRequest;
use App\Modules\Category\Http\Requests\Api\Test\UpdateTestRequest;
use App\Modules\Category\Http\Resources\Test\TestResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TestWebService extends TestService
{
    use Responser;

    public static function platform(): string
    {
        return 'website';
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->testRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, TestResource::class);
    }

    public function store(StoreTestRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $test = $this->testRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new TestResource($test));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $test = $this->testRepository->getById($id);

        if ($test === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new TestResource($test));
    }

    public function update(UpdateTestRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->testRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $test = $this->testRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new TestResource($test));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->testRepository->delete($id);

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
