<?php

namespace App\Modules\Category\Http\Services\Dashboard\Test;

use App\Modules\Category\Http\Requests\Dashboard\Test\StoreTestRequest;
use App\Modules\Category\Http\Requests\Dashboard\Test\UpdateTestRequest;
use App\Modules\Category\Repositories\TestRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class TestService
{
    public function __construct(
        protected TestRepositoryInterface $testRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->testRepository->getAll();

        return view('category::dashboard.tests.index', compact('items'));
    }

    public function create(): View
    {
        return view('category::dashboard.tests.create');
    }

    public function store(StoreTestRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->testRepository->create($request->validated());
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
        $test = $this->testRepository->getById($id);

        if ($test === null) {
            return redirect()->route('dashboard.category.index')->with('error', __('Not found.'));
        }

        return view('category::dashboard.tests.edit', compact('test'));
    }

    public function update(UpdateTestRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->testRepository->update($id, $request->validated());

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
            $deleted = $this->testRepository->delete($id);

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
