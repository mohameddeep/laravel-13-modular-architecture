<?php

namespace App\Modules\Roles\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Roles\Http\Requests\Dashboard\StorePermissionRequest;
use App\Modules\Roles\Http\Requests\Dashboard\UpdatePermissionRequest;
use App\Modules\Roles\Http\Services\Dashboard\PermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionService $permissionService,
    ) {}

    public function index(): View
    {
        return $this->permissionService->index();
    }

    public function create(): View
    {
        return $this->permissionService->create();
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        return $this->permissionService->store($request);
    }

    public function edit(int $permission): View|RedirectResponse
    {
        return $this->permissionService->edit($permission);
    }

    public function update(UpdatePermissionRequest $request, int $permission): RedirectResponse
    {
        return $this->permissionService->update($request, $permission);
    }

    public function destroy(int $permission): RedirectResponse
    {
        return $this->permissionService->destroy($permission);
    }
}
