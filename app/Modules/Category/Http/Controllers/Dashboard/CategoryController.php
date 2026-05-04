<?php

namespace App\Modules\Category\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Category\Http\Requests\Dashboard\Category\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\Dashboard\Category\UpdateCategoryRequest;
use App\Modules\Category\Http\Services\Dashboard\Category\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {
    }

    public function index(): View
    {
        return $this->categoryService->index();
    }

    public function create(): View
    {
        return $this->categoryService->create();
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        return $this->categoryService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->categoryService->edit($id);
    }

    public function update(UpdateCategoryRequest $request, int $id): RedirectResponse
    {
        return $this->categoryService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->categoryService->destroy($id);
    }
}
