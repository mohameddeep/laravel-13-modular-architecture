<?php

namespace App\Modules\Auth\Http\Services\Dashboard\Profile;

use App\Modules\Auth\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Modules\Auth\Repositories\AdminRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class ProfileService
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
    ) {}

    public function edit(): View|RedirectResponse
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return redirect()->route('dashboard.auth.login');
        }

        return view('auth::dashboard.profile.edit', ['admin' => $admin]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            return redirect()->route('dashboard.auth.login');
        }

        try {
            $data = $request->validated();

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $admin->update($data);

            return redirect()
                ->route('dashboard.profile.edit')
                ->with('success', __('dashboard.updated_successfully'));
        } catch (Throwable) {
            return back()->with('error', __('dashboard.something_went_wrong'))->withInput();
        }
    }
}
