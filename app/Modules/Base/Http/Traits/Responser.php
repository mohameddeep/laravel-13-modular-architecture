<?php

namespace App\Modules\Base\Http\Traits;

use App\Modules\Base\Http\Helpers\Http;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait Responser
{
    protected function responseSuccess(int|string $status, string $message, mixed $data = null, array $headers = []): JsonResponse
    {
        return responseSuccess($status, $message, $data, $headers);
    }

    protected function responseFail(int|string $status, string $message, mixed $errors = null, array $headers = []): JsonResponse
    {
        return responseFail($status, $message, $errors, $headers);
    }

    protected function respondWithResource(
        JsonResource|AnonymousResourceCollection $resource,
        string $message = 'OK',
        int $status = Http::OK
    ): JsonResponse {
        return $this->responseSuccess($status, $message, $resource->resolve());
    }

    protected function respondWithPaginator(
        LengthAwarePaginator $paginator,
        ?string $resourceClass = null,
        string $message = 'OK',
        int $status = Http::OK
    ): JsonResponse {
        return paginatedJsonResponse($paginator, $resourceClass, $status, $message);
    }
}
