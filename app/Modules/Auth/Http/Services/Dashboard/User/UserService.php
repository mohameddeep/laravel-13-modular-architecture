<?php

namespace App\Modules\Auth\Http\Services\Dashboard\User;

use App\Modules\Auth\Http\Requests\Dashboard\User\StoreUserRequest;
use App\Modules\Auth\Http\Requests\Dashboard\User\UpdateUserRequest;
use App\Modules\Auth\Repositories\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->userRepository->getAll();

        return view('auth::dashboard.users.index', compact('items'));
    }

    public function create(): View
    {
        return view('auth::dashboard.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->userRepository->create($request->validated());
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
        $user = $this->userRepository->getById($id);

        if ($user === null) {
            return redirect()->route('dashboard.auth.index')->with('error', __('Not found.'));
        }

        return view('auth::dashboard.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->userRepository->update($id, $request->validated());

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
            $deleted = $this->userRepository->delete($id);

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
