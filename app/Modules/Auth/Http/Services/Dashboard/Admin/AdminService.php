<?php

namespace App\Modules\Auth\Http\Services\Dashboard\Admin;

use App\Modules\Auth\Http\Requests\Dashboard\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\UpdateAdminRequest;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class AdminService
{
    public function __construct(
        protected AdminRepositoryInterface $adminRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->adminRepository->getAll();

        return view('auth::dashboard.admins.index', compact('items'));
    }

    public function create(): View
    {
        return view('auth::dashboard.admins.create');
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->adminRepository->create($request->validated());
            DB::commit();

            return redirect()
                ->route('dashboard.auth.index')
                ->with('success', __('Created.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', __('Something went wrong.'))->withInput();
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        $admin = $this->adminRepository->getById($id);

        if ($admin === null) {
            return redirect()->route('dashboard.auth.index')->with('error', __('Not found.'));
        }

        return view('auth::dashboard.admins.edit', compact('admin'));
    }

    public function update(UpdateAdminRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->adminRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return redirect()->route('dashboard.auth.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.auth.index')
                ->with('success', __('Updated.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', __('Something went wrong.'))->withInput();
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $deleted = $this->adminRepository->delete($id);

            if ($deleted === null) {
                DB::rollBack();

                return redirect()->route('dashboard.auth.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.auth.index')
                ->with('success', __('Deleted.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->route('dashboard.auth.index')->with('error', __('Something went wrong.'));
        }
    }
}
