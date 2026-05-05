<?php

namespace App\Modules\Auth\Http\Services\Api\Auth\Password;

use App\Modules\Auth\Http\Services\Api\Auth\Otp\OtpService;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use App\Modules\Base\Http\Helpers\Http;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly OtpService $otpService,
    ) {}

    public function forgot(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $user = $this->userRepository->findByEmail($data['email']);

            if (! $user) {
                return responseFail(Http::NOT_FOUND, __('messages.User not found'));
            }

            $otp = $this->otpService->sendEmailOtp($user->email, $user->name, $user->id);

            return responseSuccess(Http::OK, __('messages.OTP_Is_Send'), [
                'otp_token'          => $otp->token,
                'expires_in_minutes' => 5,
            ]);
        } catch (Exception $e) {
            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email'     => ['required', 'email', 'exists:users,email'],
                'otp_token' => ['required', 'string'],
                'otp'       => ['required', 'string', 'min:4', 'max:6'],
            ]);

            $user = $this->userRepository->findByEmail($data['email']);

            if (! $user) {
                return responseFail(Http::NOT_FOUND, __('messages.User not found'));
            }

            $otp = $this->otpService->verifyEmailOtpByToken($data['otp_token'], $data['otp']);

            if ($otp instanceof JsonResponse) {
                return $otp;
            }

            if ($otp->identifier !== $data['email']) {
                return responseFail(Http::BAD_REQUEST, __('messages.Username does not match the OTP'));
            }

            $resetToken = Str::random(60);
            Cache::put($this->resetCacheKey($data['email']), $resetToken, now()->addMinutes(10));

            return responseSuccess(Http::OK, __('messages.OTP verified successfully'), [
                'reset_token'        => $resetToken,
                'expires_in_minutes' => 10,
            ]);
        } catch (Exception $e) {
            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function reset(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email'       => ['required', 'email', 'exists:users,email'],
                'reset_token' => ['required', 'string'],
                'password'    => ['required', 'string', 'min:8'],
            ]);

            $user = $this->userRepository->findByEmail($data['email']);

            if (! $user) {
                return responseFail(Http::NOT_FOUND, __('messages.User not found'));
            }

            $cached = Cache::get($this->resetCacheKey($data['email']));

            if (! $cached || ! hash_equals((string) $cached, (string) $data['reset_token'])) {
                return responseFail(Http::BAD_REQUEST, __('messages.Invalid or expired reset token'));
            }

            DB::beginTransaction();
            $this->userRepository->update($user->id, ['password' => $data['password']]);
            Cache::forget($this->resetCacheKey($data['email']));
            DB::commit();

            return responseSuccess(Http::OK, __('messages.Password reset successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return responseFail(Http::UNAUTHORIZED, __('messages.Unauthenticated'));
        }

        $data = $request->validate([
            'current_password' => ['required', 'string', 'current_password:sanctum'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Hash::check($data['new_password'], $user->password)) {
            return responseFail(Http::BAD_REQUEST, __('messages.The new password must be different from the current password'));
        }

        $this->userRepository->update($user->id, ['password' => $data['new_password']]);

        return responseSuccess(Http::OK, __('messages.updated successfully'));
    }

    private function resetCacheKey(string $email): string
    {
        return 'reset_token_'.sha1(strtolower(trim($email)));
    }
}
