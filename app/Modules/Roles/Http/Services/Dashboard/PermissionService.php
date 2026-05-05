<?php

namespace App\Modules\Roles\Http\Services\Dashboard;

use App\Modules\Roles\Http\Requests\Dashboard\StorePermissionRequest;
use App\Modules\Roles\Http\Requests\Dashboard\UpdatePermissionRequest;
use App\Modules\Roles\Repositories\PermissionRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class PermissionService
{
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {}

    public function index(): View
    {
        $permissions = $this->permissionRepository
            ->query()
            ->orderBy('name')
            ->paginate(20);

        return view('roles::dashboard.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('roles::dashboard.permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        // Single insert — no transaction overhead needed
        $this->permissionRepository->create($request->validated());

        return $this->successRedirect('dashboard.permissions.index', 'dashboard.created_successfully');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $permission = $this->permissionRepository->getById($id);

        if (! $permission) {
            return $this->notFoundRedirect('dashboard.permissions.index');
        }

        return view('roles::dashboard.permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, int $id): RedirectResponse
    {
        $permission = $this->permissionRepository->getById($id);

        if (! $permission) {
            return $this->notFoundRedirect('dashboard.permissions.index');
        }

        try {
            $permission->update($request->validated());

            return $this->successRedirect('dashboard.permissions.index', 'dashboard.updated_successfully');
        } catch (Throwable) {
            return back()->with('error', __('dashboard.something_went_wrong'))->withInput();
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $permission = $this->permissionRepository->getById($id);

        if (! $permission) {
            return $this->notFoundRedirect('dashboard.permissions.index');
        }

        $permission->delete();

        return $this->successRedirect('dashboard.permissions.index', 'dashboard.deleted_successfully');
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function successRedirect(string $route, string $messageKey): RedirectResponse
    {
        return redirect()->route($route)->with('success', __($messageKey));
    }

    private function notFoundRedirect(string $route): RedirectResponse
    {
        return redirect()->route($route)->with('error', __('dashboard.not_found'));
    }
}
