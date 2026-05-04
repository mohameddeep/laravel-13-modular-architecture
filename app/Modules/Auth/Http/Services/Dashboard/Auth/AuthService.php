<?php

namespace App\Modules\Auth\Http\Services\Dashboard\Auth;

use App\Modules\Auth\Http\Requests\Dashboard\Auth\StoreAuthRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Auth\UpdateAuthRequest;
use App\Modules\Auth\Repositories\AuthRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->authRepository->getAll();

        return view('auth::dashboard.auths.index', compact('items'));
    }

    public function create(): View
    {
        return view('auth::dashboard.auths.create');
    }

    public function store(StoreAuthRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->authRepository->create($request->validated());
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
        $auth = $this->authRepository->getById($id);

        if ($auth === null) {
            return redirect()->route('dashboard.auth.index')->with('error', __('Not found.'));
        }

        return view('auth::dashboard.auths.edit', compact('auth'));
    }

    public function update(UpdateAuthRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->authRepository->update($id, $request->validated());

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
            $deleted = $this->authRepository->delete($id);

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
