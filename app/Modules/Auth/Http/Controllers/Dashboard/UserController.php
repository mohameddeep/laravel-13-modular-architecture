<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Dashboard\User\StoreUserRequest;
use App\Modules\Auth\Http\Requests\Dashboard\User\UpdateUserRequest;
use App\Modules\Auth\Http\Services\Dashboard\User\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(): View
    {
        return $this->userService->index();
    }

    public function create(): View
    {
        return $this->userService->create();
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        return $this->userService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->userService->edit($id);
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        return $this->userService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->userService->destroy($id);
    }
}
