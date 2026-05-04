<?php

namespace App\Modules\Category\Http\Services\Dashboard\Category;

use App\Modules\Category\Http\Requests\Dashboard\Category\StoreCategoryRequest;
use App\Modules\Category\Http\Requests\Dashboard\Category\UpdateCategoryRequest;
use App\Modules\Category\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->categoryRepository->getAll();

        return view('category::dashboard.categories.index', compact('items'));
    }

    public function create(): View
    {
        return view('category::dashboard.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->categoryRepository->create($request->validated());
            DB::commit();

            return redirect()
                ->route('dashboard.category.index')
                ->with('success', __('Created.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', __('Something went wrong.'))->withInput();
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        $category = $this->categoryRepository->getById($id);

        if ($category === null) {
            return redirect()->route('dashboard.category.index')->with('error', __('Not found.'));
        }

        return view('category::dashboard.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->categoryRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return redirect()->route('dashboard.category.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.category.index')
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
            $deleted = $this->categoryRepository->delete($id);

            if ($deleted === null) {
                DB::rollBack();

                return redirect()->route('dashboard.category.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.category.index')
                ->with('success', __('Deleted.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->route('dashboard.category.index')->with('error', __('Something went wrong.'));
        }
    }
}
