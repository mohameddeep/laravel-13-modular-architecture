<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Dashboard\Profile\UpdateProfileRequest;
use App\Modules\Auth\Http\Services\Dashboard\Profile\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function edit(): View|RedirectResponse
    {
        return $this->profileService->edit();
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        return $this->profileService->update($request);
    }
}
