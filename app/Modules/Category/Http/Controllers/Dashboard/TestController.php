<?php

namespace App\Modules\Category\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Category\Http\Requests\Dashboard\Test\StoreTestRequest;
use App\Modules\Category\Http\Requests\Dashboard\Test\UpdateTestRequest;
use App\Modules\Category\Http\Services\Dashboard\Test\TestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestController extends Controller
{
    public function __construct(
        protected TestService $testService
    ) {
    }

    public function index(): View
    {
        return $this->testService->index();
    }

    public function create(): View
    {
        return $this->testService->create();
    }

    public function store(StoreTestRequest $request): RedirectResponse
    {
        return $this->testService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->testService->edit($id);
    }

    public function update(UpdateTestRequest $request, int $id): RedirectResponse
    {
        return $this->testService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->testService->destroy($id);
    }
}
