<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Auth\Http\Requests\Api\User\StoreUserRequest;
use App\Modules\Auth\Http\Requests\Api\User\UpdateUserRequest;
use App\Modules\Auth\Http\Services\Api\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->userService->index($request);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->userService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->userService->show($id);
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->userService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->userService->destroy($id);
    }
}
