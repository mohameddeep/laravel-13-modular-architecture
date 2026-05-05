<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Dashboard\Auth\LoginRequest;
use App\Modules\Auth\Http\Services\Dashboard\Auth\DashboardAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardAuthController extends Controller
{
    public function __construct(
        private readonly DashboardAuthService $authService,
    ) {}

    public function showLoginForm(): View
    {
        return $this->authService->showLoginForm();
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request): RedirectResponse
    {
        return $this->authService->logout($request);
    }
}
