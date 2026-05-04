<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Auth\Http\Requests\Api\Auth\StoreAuthRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\UpdateAuthRequest;
use App\Modules\Auth\Http\Resources\Auth\AuthResource;
use App\Modules\Auth\Repositories\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthDashboardService
{
    use Responser;

    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->authRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, AuthResource::class);
    }

    public function store(StoreAuthRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $auth = $this->authRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new AuthResource($auth));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $auth = $this->authRepository->getById($id);

        if ($auth === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new AuthResource($auth));
    }

    public function update(UpdateAuthRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->authRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $auth = $this->authRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new AuthResource($auth));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->authRepository->delete($id);

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
