<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Auth\Http\Requests\Api\Auth\ResendOtpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignInRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignUpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Modules\Auth\Http\Resources\User\UserResource;
use App\Modules\Base\Http\Helpers\Http;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Mobile auth uses OTP-only flow (phone or email OTP, no password required).
 */
class AuthMobileService extends AuthService
{
    public static function platform(): string
    {
        return 'mobile';
    }

    /**
     * Send OTP to phone or email — this acts as both sign-up and sign-in initiation.
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        return $this->sendOtp($request->validated('username') ?? $request->validated('email') ?? $request->validated('phone'));
    }

    /**
     * Verify OTP and return token; creates user if not found.
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $data     = $request->validated();
            $username = $data['username'];
            $isPhone  = (bool) preg_match('/^\+[1-9]\d{7,15}$/', $username);

            $otp = $isPhone
                ? $this->otpService->verifyPhoneOtpByToken($data['otp_token'], $data['code'])
                : $this->otpService->verifyEmailOtpByToken($data['otp_token'], $data['code']);

            if ($otp instanceof JsonResponse) {
                return $otp;
            }

            $user = $isPhone
                ? $this->userRepository->findByPhone($username)
                : $this->userRepository->findByEmail($username);

            if (! $user) {
                $userData = [
                    'name'         => $isPhone ? $username : Str::before($username, '@'),
                    'password'     => Str::random(40),
                    'is_active'    => true,
                    'otp_verified' => true,
                ];

                $userData[$isPhone ? 'phone' : 'email'] = $username;

                $user    = $this->userRepository->create($userData);
                $message = __('messages.Registration successful');
            } else {
                $this->userRepository->update($user->id, [
                    'otp_verified' => true,
                    'is_active'    => true,
                ]);
                $user    = $user->fresh();
                $message = __('messages.Login successful');
            }

            $token = $user->createToken('mobile')->plainTextToken;

            return responseSuccess(Http::OK, $message, [
                'user'  => new UserResource($user),
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Mobile Verify OTP Error: '.$e->getMessage(), ['ip' => $request->ip()]);

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $token    = $request->validated('otp_token');
        $existing = $this->otpService->resendEmailOtp($token);

        if ($existing instanceof JsonResponse && $existing->getStatusCode() !== 200) {
            return $this->otpService->resendPhoneOtp($token);
        }

        return $existing;
    }

    /**
     * For mobile, signIn simply sends an OTP to the given username.
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        return $this->sendOtp($request->validated('username'));
    }

    public function signOut(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return responseSuccess(Http::OK, __('messages.Successfully loggedOut'));
        } catch (Exception $e) {
            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    private function sendOtp(string $username): JsonResponse
    {
        $isPhone = (bool) preg_match('/^\+[1-9]\d{7,15}$/', $username);

        $user   = $isPhone
            ? $this->userRepository->findByPhone($username)
            : $this->userRepository->findByEmail($username);

        $userId = $user?->id;

        if ($isPhone) {
            $otp = $this->otpService->sendPhoneOtp($username, $user?->name ?? 'User', $userId);

            return responseSuccess(Http::OK, __('messages.OTP_Is_Send'), [
                'phone'              => $username,
                'otp_token'          => $otp->token,
                'expires_in_minutes' => 5,
            ]);
        }

        $otp = $this->otpService->sendEmailOtp($username, $user?->name ?? 'User', $userId);

        return responseSuccess(Http::OK, __('messages.OTP_Is_Send'), [
            'email'              => $username,
            'otp_token'          => $otp->token,
            'expires_in_minutes' => 5,
        ]);
    }
}
