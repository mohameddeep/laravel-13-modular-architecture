<?php

namespace App\Modules\Auth\Http\Services\Api\User;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Auth\Http\Requests\Api\User\StoreUserRequest;
use App\Modules\Auth\Http\Requests\Api\User\UpdateUserRequest;
use App\Modules\Auth\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserWebService extends UserService
{
    use Responser;

    public static function platform(): string
    {
        return 'website';
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->userRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, UserResource::class);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new UserResource($user));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if ($user === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new UserResource($user));
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->userRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $user = $this->userRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new UserResource($user));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->userRepository->delete($id);

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
