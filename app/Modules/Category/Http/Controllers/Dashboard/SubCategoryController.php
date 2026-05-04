<?php

namespace App\Modules\Category\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Category\Http\Requests\Dashboard\SubCategory\StoreSubCategoryRequest;
use App\Modules\Category\Http\Requests\Dashboard\SubCategory\UpdateSubCategoryRequest;
use App\Modules\Category\Http\Services\Dashboard\SubCategory\SubCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubCategoryController extends Controller
{
    public function __construct(
        protected SubCategoryService $subCategoryService
    ) {
    }

    public function index(): View
    {
        return $this->subCategoryService->index();
    }

    public function create(): View
    {
        return $this->subCategoryService->create();
    }

    public function store(StoreSubCategoryRequest $request): RedirectResponse
    {
        return $this->subCategoryService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->subCategoryService->edit($id);
    }

    public function update(UpdateSubCategoryRequest $request, int $id): RedirectResponse
    {
        return $this->subCategoryService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->subCategoryService->destroy($id);
    }
}
