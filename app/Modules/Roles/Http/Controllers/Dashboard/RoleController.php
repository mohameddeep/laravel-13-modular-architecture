<?php

namespace App\Modules\Roles\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Roles\Http\Requests\Dashboard\StoreRoleRequest;
use App\Modules\Roles\Http\Requests\Dashboard\UpdateRoleRequest;
use App\Modules\Roles\Http\Services\Dashboard\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService,
    ) {}

    public function index(): View
    {
        return $this->roleService->index();
    }

    public function create(): View
    {
        return $this->roleService->create();
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        return $this->roleService->store($request);
    }

    public function edit(int $role): View|RedirectResponse
    {
        return $this->roleService->edit($role);
    }

    public function update(UpdateRoleRequest $request, int $role): RedirectResponse
    {
        return $this->roleService->update($request, $role);
    }

    public function destroy(int $role): RedirectResponse
    {
        return $this->roleService->destroy($role);
    }
}
