<?php

namespace App\Modules\Products\Http\Services\Dashboard\Product;

use App\Modules\Products\Http\Requests\Dashboard\Product\StoreProductRequest;
use App\Modules\Products\Http\Requests\Dashboard\Product\UpdateProductRequest;
use App\Modules\Products\Repositories\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function index(): View
    {
        $items = $this->productRepository->getAll();

        return view('products::dashboard.products.index', compact('items'));
    }

    public function create(): View
    {
        return view('products::dashboard.products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $this->productRepository->create($request->validated());
            DB::commit();

            return redirect()
                ->route('dashboard.products.index')
                ->with('success', __('Created.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', __('Something went wrong.'))->withInput();
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        $product = $this->productRepository->getById($id);

        if ($product === null) {
            return redirect()->route('dashboard.products.index')->with('error', __('Not found.'));
        }

        return view('products::dashboard.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $updated = $this->productRepository->update($id, $request->validated());

            if (! $updated) {
                DB::rollBack();

                return redirect()->route('dashboard.products.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.products.index')
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
            $deleted = $this->productRepository->delete($id);

            if ($deleted === null) {
                DB::rollBack();

                return redirect()->route('dashboard.products.index')->with('error', __('Not found.'));
            }

            DB::commit();

            return redirect()
                ->route('dashboard.products.index')
                ->with('success', __('Deleted.'));
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->route('dashboard.products.index')->with('error', __('Something went wrong.'));
        }
    }
}
