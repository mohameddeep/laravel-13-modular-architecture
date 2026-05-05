<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1\Password;

use App\Modules\Auth\Http\Services\Api\Auth\Password\PasswordService;
use App\Modules\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends BaseController
{
    public function __construct(
        private readonly PasswordService $passwordService,
    ) {}

    public function forgot(Request $request): JsonResponse
    {
        return $this->passwordService->forgot($request);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        return $this->passwordService->verifyOtp($request);
    }

    public function reset(Request $request): JsonResponse
    {
        return $this->passwordService->reset($request);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        return $this->passwordService->updatePassword($request);
    }
}
