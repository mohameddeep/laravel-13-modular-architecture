<?php

namespace App\Modules\Auth\Http\Services\Api\Admin;

use App\Modules\Auth\Http\Requests\Api\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Api\Admin\UpdateAdminRequest;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class AdminService
{
    public function __construct(
        protected AdminRepositoryInterface $adminRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreAdminRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateAdminRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
