<?php

namespace App\Modules\Auth\Http\Services\Api\User;

use App\Modules\Auth\Http\Requests\Api\User\StoreUserRequest;
use App\Modules\Auth\Http\Requests\Api\User\UpdateUserRequest;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreUserRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateUserRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
