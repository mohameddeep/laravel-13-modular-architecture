<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Auth\Http\Requests\Api\Auth\ResendOtpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignInRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\SignUpRequest;
use App\Modules\Auth\Http\Requests\Api\Auth\VerifyOtpRequest;
use App\Modules\Auth\Http\Resources\User\UserResource;
use App\Modules\Auth\Models\User;
use App\Modules\Base\Http\Helpers\Http;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthWebService extends AuthService
{
    public static function platform(): string
    {
        return 'web';
    }

    public function signUp(SignUpRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = $this->userRepository->create([
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? null,
                'password'     => $data['password'],
                'is_active'    => false,
                'otp_verified' => false,
            ]);

            $otp = $this->otpService->sendEmailOtp($user->email, $user->name, $user->id);

            DB::commit();

            Log::info('User signed up', ['user_id' => $user->id, 'ip' => $request->ip()]);

            return responseSuccess(Http::CREATED, __('messages.created successfully'), [
                'user'      => new UserResource($user->fresh()),
                'otp_token' => $otp->token,
            ]);
        } catch (Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error('Sign Up Error: '.$e->getMessage(), ['ip' => $request->ip()]);

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $user = $this->userRepository->findByEmail($data['username']);

            if (! $user) {
                return responseFail(Http::NOT_FOUND, __('messages.User not found'));
            }

            $otp = $this->otpService->verifyEmailOtpByToken($data['otp_token'], $data['code']);

            if ($otp instanceof JsonResponse) {
                return $otp;
            }

            if ($otp->identifier !== $data['username']) {
                return responseFail(Http::BAD_REQUEST, __('messages.Username does not match the OTP'));
            }

            $this->userRepository->update($user->id, [
                'otp_verified' => true,
                'is_active'    => true,
            ]);

            $user  = $user->fresh();
            $token = $user->createToken('web')->plainTextToken;

            Log::info('OTP verified', ['user_id' => $user->id, 'ip' => $request->ip()]);

            return responseSuccess(Http::OK, __('messages.Login verified successfully'), [
                'user'  => new UserResource($user),
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Verify OTP Error: '.$e->getMessage(), ['ip' => $request->ip()]);

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        return $this->otpService->resendEmailOtp($request->validated('otp_token'));
    }

    public function signIn(SignInRequest $request): JsonResponse
    {
        try {
            $data     = $request->validated();
            $username = trim((string) $data['username']);
            $isPhone  = (bool) preg_match('/^\+[1-9]\d{7,15}$/', $username);

            $user = $isPhone
                ? $this->userRepository->findByPhone($username)
                : $this->userRepository->findByEmail($username);

            if (! $user) {
                return responseFail(Http::NOT_FOUND, $isPhone
                    ? __('messages.Phone not registered. Please create a new account')
                    : __('messages.Email not registered. Please create a new account')
                );
            }

            if ($error = $this->ensureUserCanLogin($user, $data['password'])) {
                return $error;
            }

            $token = $user->createToken('web')->plainTextToken;

            Log::info('User signed in', ['user_id' => $user->id, 'ip' => $request->ip()]);

            return responseSuccess(Http::OK, __('messages.Login successful'), [
                'user'  => new UserResource($user->fresh()),
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Sign In Error: '.$e->getMessage(), ['ip' => $request->ip()]);

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
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

    private function ensureUserCanLogin(User $user, string $password): ?JsonResponse
    {
        if (! $user->is_active || ! $user->otp_verified) {
            return responseFail(Http::FORBIDDEN, __('messages.Account is inactive. Please contact the administration'));
        }

        if (empty($user->password) || ! Hash::check($password, $user->password)) {
            return responseFail(Http::BAD_REQUEST, __('messages.Incorrect email or password'));
        }

        return null;
    }
}
