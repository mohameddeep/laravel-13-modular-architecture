<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Auth\Http\Requests\Api\Auth\StoreAuthRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\UpdateAuthRequest;
use App\Modules\Auth\Http\Services\Api\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->authService->index($request);
    }

    public function store(StoreAuthRequest $request): JsonResponse
    {
        return $this->authService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->authService->show($id);
    }

    public function update(UpdateAuthRequest $request, int $id): JsonResponse
    {
        return $this->authService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->authService->destroy($id);
    }
}
