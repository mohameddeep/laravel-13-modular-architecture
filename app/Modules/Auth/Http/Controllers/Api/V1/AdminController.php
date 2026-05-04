<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Auth\Http\Requests\Api\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Api\Admin\UpdateAdminRequest;
use App\Modules\Auth\Http\Services\Api\Admin\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    public function __construct(
        protected AdminService $adminService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->adminService->index($request);
    }

    public function store(StoreAdminRequest $request): JsonResponse
    {
        return $this->adminService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->adminService->show($id);
    }

    public function update(UpdateAdminRequest $request, int $id): JsonResponse
    {
        return $this->adminService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->adminService->destroy($id);
    }
}
