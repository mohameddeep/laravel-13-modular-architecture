<?php

namespace App\Modules\Category\Http\Services\Dashboard\SubCategory;

use App\Modules\Category\Http\Requests\Dashboard\SubCategory\StoreSubCategoryRequest;
use App\Modules\Category\Http\Requests\Dashboard\SubCategory\UpdateSubCategoryRequest;
use App\Modules\Category\Repositories\SubCategoryRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class SubCategoryService
{
    public function __construct(
        protected SubCategoryRepositoryInterface $subCategoryRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->subCategoryRepository->getAll();

        return view('category::dashboard.categories.index', compact('items'));
    }

    public function create(): View
    {
        return view('category::dashboard.categories.create');
    }

    public function store(StoreSubCategoryRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->subCategoryRepository->create($request->validated());
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
        $subCategory = $this->subCategoryRepository->getById($id);

        if ($subCategory === null) {
            return redirect()->route('dashboard.category.index')->with('error', __('Not found.'));
        }

        return view('category::dashboard.categories.edit', compact('subCategory'));
    }

    public function update(UpdateSubCategoryRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->subCategoryRepository->update($id, $request->validated());

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
            $deleted = $this->subCategoryRepository->delete($id);

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
