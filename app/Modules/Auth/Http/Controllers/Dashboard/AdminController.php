<?php

namespace App\Modules\Auth\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\StoreAdminRequest;
use App\Modules\Auth\Http\Requests\Dashboard\Admin\UpdateAdminRequest;
use App\Modules\Auth\Http\Services\Dashboard\Admin\AdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {
    }

    public function index(): View
    {
        return $this->adminService->index();
    }

    public function create(): View
    {
        return $this->adminService->create();
    }

    public function store(StoreAdminRequest $request): RedirectResponse
    {
        return $this->adminService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->adminService->edit($id);
    }

    public function update(UpdateAdminRequest $request, int $id): RedirectResponse
    {
        return $this->adminService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->adminService->destroy($id);
    }
}
