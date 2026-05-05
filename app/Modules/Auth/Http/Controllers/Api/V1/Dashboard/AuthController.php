<?php

namespace App\Modules\Auth\Http\Controllers\Api\V1\Dashboard;

use App\Modules\Auth\Http\Services\Api\Auth\AuthDashboardService;
use App\Modules\Base\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(
        private readonly AuthDashboardService $authService,
    ) {}

    public function signIn(Request $request): JsonResponse
    {
        return $this->authService->signIn($request);
    }

    public function signOut(Request $request): JsonResponse
    {
        return $this->authService->signOut($request);
    }
}
