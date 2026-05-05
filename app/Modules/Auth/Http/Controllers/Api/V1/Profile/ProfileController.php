<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1\Profile;

use App\Modules\Auth\Http\Services\Api\Profile\ProfileService;
use App\Modules\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    public function __construct(
        private readonly ProfileService $profileService,
    ) {}

    public function getProfile(Request $request): JsonResponse
    {
        return $this->profileService->getProfile($request);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        return $this->profileService->updateProfile($request);
    }
}
