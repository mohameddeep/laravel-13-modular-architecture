<?php

namespace App\Modules\Products\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Requests\Dashboard\Product\StoreProductRequest;
use App\Modules\Products\Http\Requests\Dashboard\Product\UpdateProductRequest;
use App\Modules\Products\Http\Services\Dashboard\Product\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(): View
    {
        return $this->productService->index();
    }

    public function create(): View
    {
        return $this->productService->create();
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        return $this->productService->store($request);
    }

    public function edit(int $id): View|RedirectResponse
    {
        return $this->productService->edit($id);
    }

    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        return $this->productService->update($request, $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        return $this->productService->destroy($id);
    }
}
