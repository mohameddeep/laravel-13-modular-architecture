<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Auth\Http\Requests\Api\Auth\StoreAuthRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\UpdateAuthRequest;
use App\Modules\Auth\Repositories\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {
    }

    abstract public static function platform(): string;

    abstract public function index(Request $request): JsonResponse;

    abstract public function store(StoreAuthRequest $request): JsonResponse;

    abstract public function show(int $id): JsonResponse;

    abstract public function update(UpdateAuthRequest $request, int $id): JsonResponse;

    abstract public function destroy(int $id): JsonResponse;
}
