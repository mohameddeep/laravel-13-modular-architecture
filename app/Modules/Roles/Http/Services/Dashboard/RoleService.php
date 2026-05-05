<?php

namespace App\Modules\Roles\Http\Services\Dashboard;

use App\Modules\Roles\Http\Requests\Dashboard\StoreRoleRequest;
use App\Modules\Roles\Http\Requests\Dashboard\UpdateRoleRequest;
use App\Modules\Roles\Repositories\PermissionRepositoryInterface;
use App\Modules\Roles\Repositories\RoleRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class RoleService
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {}

    public function index(): View
    {
        // withCount avoids N+1 when displaying permissions_count per row
        $roles = $this->roleRepository
            ->query()
            ->withCount('permissions')
            ->latest()
            ->paginate(15);

        return view('roles::dashboard.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = $this->permissionRepository->query()->orderBy('name')->get();

        return view('roles::dashboard.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data         = $request->validated();
            $data['name'] = Str::slug($data['display_name_en'], '-');

            $role = $this->roleRepository->create($data);
            $role->permissions()->sync($request->input('permissions', []));

            DB::commit();

            return $this->successRedirect('dashboard.roles.index', 'dashboard.created_successfully');
        } catch (Throwable) {
            DB::rollBack();

            return back()->with('error', __('dashboard.something_went_wrong'))->withInput();
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        // Load permissions eagerly to avoid extra queries when building $rolePermissions
        $role = $this->roleRepository->query()->with('permissions')->find($id);

        if (! $role) {
            return $this->notFoundRedirect('dashboard.roles.index');
        }

        $permissions     = $this->permissionRepository->query()->orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('id')->all();

        return view('roles::dashboard.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, int $id): RedirectResponse
    {
        // Single fetch — used for both the update and the permission sync
        $role = $this->roleRepository->getById($id);

        if (! $role) {
            return $this->notFoundRedirect('dashboard.roles.index');
        }

        try {
            DB::beginTransaction();

            $data         = $request->validated();
            $data['name'] = Str::slug($data['display_name_en'], '-');

            $role->update($data);
            $role->permissions()->sync($request->input('permissions', []));

            DB::commit();

            return $this->successRedirect('dashboard.roles.index', 'dashboard.updated_successfully');
        } catch (Throwable) {
            DB::rollBack();

            return back()->with('error', __('dashboard.something_went_wrong'))->withInput();
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $role = $this->roleRepository->getById($id);

        if (! $role) {
            return $this->notFoundRedirect('dashboard.roles.index');
        }

        try {
            DB::beginTransaction();
            $role->permissions()->detach();
            $role->delete();
            DB::commit();

            return $this->successRedirect('dashboard.roles.index', 'dashboard.deleted_successfully');
        } catch (Throwable) {
            DB::rollBack();

            return $this->errorRedirect('dashboard.roles.index');
        }
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

    private function errorRedirect(string $route): RedirectResponse
    {
        return redirect()->route($route)->with('error', __('dashboard.something_went_wrong'));
    }
}
