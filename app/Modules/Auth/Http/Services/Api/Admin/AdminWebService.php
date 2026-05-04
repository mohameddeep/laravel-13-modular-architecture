<?php

namespace App\Modules\Auth\Http\Services\Api\Admin;

use App\Modules\Base\Http\Helpers\Http;
use App\Modules\Base\Http\Traits\Responser;
use App\Modules\Auth\Http\Requests\Api\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Api\Admin\UpdateAdminRequest;
use App\Modules\Auth\Http\Resources\Admin\AdminResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminWebService extends AdminService
{
    use Responser;

    public static function platform(): string
    {
        return 'website';
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $paginator = $this->adminRepository->paginate($perPage);

        return $this->respondWithPaginator($paginator, AdminResource::class);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $admin = $this->adminRepository->create($request->validated());
            DB::commit();

            return $this->responseSuccess(Http::CREATED, __('Created.'), new AdminResource($admin));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function show(int $id): JsonResponse
    {
        $admin = $this->adminRepository->getById($id);

        if ($admin === null) {
            return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
        }

        return $this->responseSuccess(Http::OK, __('OK.'), new AdminResource($admin));
    }

    public function update(UpdateAdminRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->adminRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return $this->responseFail(Http::NOT_FOUND, __('Not found.'));
            }

            $admin = $this->adminRepository->getById($id);
            DB::commit();

            return $this->responseSuccess(Http::OK, __('Updated.'), new AdminResource($admin));
        } catch (Throwable $e) {
            DB::rollBack();

            return catchError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->adminRepository->delete($id);

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
