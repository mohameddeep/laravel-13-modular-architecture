<?php

namespace App\Modules\Auth\Http\Services\Dashboard\Admin;

use App\Modules\Auth\Http\Requests\Dashboard\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\UpdateAdminRequest;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class AdminService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
    ) {}

    public function index(): View
    {
        $items = $this->adminRepository->query()->latest()->paginate(15);

        return view('auth::dashboard.admins.index', compact('items'));
    }

    public function create(): View
    {
        return view('auth::dashboard.admins.create');
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        // Single insert — no transaction overhead needed
        $this->adminRepository->create($request->validated());

        return $this->successRedirect('dashboard.admins.index', 'dashboard.created_successfully');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $admin = $this->adminRepository->getById($id);

        if (! $admin) {
            return $this->notFoundRedirect('dashboard.admins.index');
        }

        return view('auth::dashboard.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, int $id): RedirectResponse
    {
        $admin = $this->adminRepository->getById($id);

        if (! $admin) {
            return $this->notFoundRedirect('dashboard.admins.index');
        }

        try {
            $data = $request->validated();

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $admin->update($data);

            return $this->successRedirect('dashboard.admins.index', 'dashboard.updated_successfully');
        } catch (Throwable) {
            return back()->with('error', __('dashboard.something_went_wrong'))->withInput();
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $admin = $this->adminRepository->getById($id);

        if (! $admin) {
            return $this->notFoundRedirect('dashboard.admins.index');
        }

        $admin->delete();

        return $this->successRedirect('dashboard.admins.index', 'dashboard.deleted_successfully');
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
