<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Auth\Http\Requests\Api\Auth\ResendOtpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignInRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignUpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Modules\Auth\Http\Services\Api\Auth\Otp\OtpService;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected OtpService $otpService,
    ) {}

    abstract public static function platform(): string;

    abstract public function signUp(SignUpRequest $request): JsonResponse;

    abstract public function verifyOtp(VerifyOtpRequest $request): JsonResponse;

    abstract public function resendOtp(ResendOtpRequest $request): JsonResponse;

    abstract public function signIn(SignInRequest $request): JsonResponse;

    abstract public function signOut(Request $request): JsonResponse;
}
