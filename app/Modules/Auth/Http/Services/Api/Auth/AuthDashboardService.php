<?php

namespace App\Modules\Auth\Http\Services\Api\Auth;

use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use App\Modules\Base\Http\Helpers\Http;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthDashboardService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
    ) {}

    public function signIn(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $admin = $this->adminRepository->findByEmail($data['email']);

            if (! $admin) {
                return responseFail(Http::NOT_FOUND, __('messages.Email not registered. Please create a new account'));
            }

            if (empty($admin->password) || ! Hash::check($data['password'], $admin->password)) {
                return responseFail(Http::BAD_REQUEST, __('messages.Incorrect email or password'));
            }

            $token = $admin->createToken('dashboard')->plainTextToken;

            Log::info('Admin signed in', ['admin_id' => $admin->id, 'ip' => $request->ip()]);

            return responseSuccess(Http::OK, __('messages.Login successful'), [
                'admin' => [
                    'id'    => $admin->id,
                    'name'  => $admin->name,
                    'email' => $admin->email,
                ],
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Dashboard Sign In Error: '.$e->getMessage(), ['ip' => $request->ip()]);

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function signOut(Request $request): JsonResponse
    {
        try {
            $request->user('admin-api')->currentAccessToken()->delete();

            return responseSuccess(Http::OK, __('messages.Successfully loggedOut'));
        } catch (Exception $e) {
            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }
}
