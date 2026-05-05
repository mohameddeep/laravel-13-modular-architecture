<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1;

use App\Modules\Auth\Http\Requests\Api\Auth\ResendOtpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignInRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignUpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Modules\Auth\Http\Services\Api\Auth\AuthService;
use App\Modules\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function signUp(SignUpRequest $request): JsonResponse
    {
        return $this->authService->signUp($request);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        return $this->authService->verifyOtp($request);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        return $this->authService->resendOtp($request);
    }

    public function signIn(SignInRequest $request): JsonResponse
    {
        return $this->authService->signIn($request);
    }

    public function signOut(Request $request): JsonResponse
    {
        return $this->authService->signOut($request);
    }
}
