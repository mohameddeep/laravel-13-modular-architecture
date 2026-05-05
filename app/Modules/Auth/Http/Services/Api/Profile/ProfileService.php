<?php

namespace App\Modules\Auth\Http\Services\Api\Profile;

use App\Modules\Auth\Http\Resources\User\UserResource;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use App\Modules\Base\Http\Helpers\Http;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function getProfile(Request $request): JsonResponse
    {
        try {
            return responseSuccess(Http::OK, __('messages.data_retrieved_successfully'), new UserResource($request->user()));
        } catch (Exception $e) {
            Log::error('Get Profile Error: '.$e->getMessage());

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }

    public function updateProfile(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $request->user();

            $data = $request->validate([
                'name'  => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id],
                'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,'.$user->id],
                'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            ]);

            $update = array_filter([
                'name'  => $data['name']  ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ], fn ($v) => $v !== null);

            if ($request->hasFile('image')) {
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                $update['image'] = $request->file('image')->store('users/images', 'public');
            }

            if (! empty($update)) {
                $this->userRepository->update($user->id, $update);
            }

            DB::commit();

            return responseSuccess(Http::OK, __('messages.Profile updated successfully'), new UserResource($user->fresh()));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Update Profile Error: '.$e->getMessage());

            return responseFail(Http::BAD_REQUEST, __('messages.Something went wrong'));
        }
    }
}
