<?php

namespace App\Modules\Category\Http\Controllers\Api\V1;

use App\Modules\Base\Http\Controllers\BaseController;
use App\Modules\Category\Http\Requests\Api\Test\StoreTestRequest;
use App\Modules\Category\Http\Requests\Api\Test\UpdateTestRequest;
use App\Modules\Category\Http\Services\Api\Test\TestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestController extends BaseController
{
    public function __construct(
        protected TestService $testService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->testService->index($request);
    }

    public function store(StoreTestRequest $request): JsonResponse
    {
        return $this->testService->store($request);
    }

    public function show(int $id): JsonResponse
    {
        return $this->testService->show($id);
    }

    public function update(UpdateTestRequest $request, int $id): JsonResponse
    {
        return $this->testService->update($request, $id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->testService->destroy($id);
    }
}
