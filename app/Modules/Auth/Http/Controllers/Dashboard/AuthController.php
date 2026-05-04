<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Dashboard\Auth\StoreAuthRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Auth\UpdateAuthRequest;
use App\Modules\Auth\Http\Services\Dashboard\Auth\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function index(): View
    {
        return $this->authService->index();
    }

    public function create(): View
    {
        return $this->authService->create();
    }

    public function store(StoreAuthRequest $request): RedirectResponse
    {
        return $this->authService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->authService->edit($id);
    }

    public function update(UpdateAuthRequest $request, int $id): RedirectResponse
    {
        return $this->authService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->authService->destroy($id);
    }
}
