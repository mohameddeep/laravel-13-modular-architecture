<?php

use App\Modules\Base\Http\Helpers\Http;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

if (! function_exists('responseSuccess')) {
    function responseSuccess(int|string $status, string $message, mixed $data = null, array $headers = []): JsonResponse
    {
        $payload = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        $code = is_int($status) ? $status : Http::OK;

        return response()->json($payload, $code, $headers);
    }
}

if (! function_exists('responseFail')) {
    function responseFail(int|string $status, string $message, mixed $errors = null, array $headers = []): JsonResponse
    {
        $payload = [
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ];

        $code = is_int($status) ? $status : Http::BAD_REQUEST;

        return response()->json($payload, $code, $headers);
    }
}

if (! function_exists('paginatedJsonResponse')) {
    function paginatedJsonResponse(
        LengthAwarePaginator $paginator,
        ?string $resourceClass = null,
        int $status = Http::OK,
        string $message = 'OK'
    ): JsonResponse {
        $items = $resourceClass
            ? $resourceClass::collection($paginator->getCollection())
            : $paginator->items();

        $data = [
            'items' => $items instanceof JsonResource ? $items->resolve() : $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];

        return responseSuccess($status, $message, $data);
    }
}

if (! function_exists('catchError')) {
    function catchError(Throwable $e): JsonResponse
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        Log::error($e->getMessage(), ['exception' => $e]);

        return responseFail(Http::INTERNAL_SERVER_ERROR, __('Something went wrong.'));
    }
}

if (! function_exists('fileFullPath')) {
    function fileFullPath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
    }
}

if (! function_exists('formatDate')) {
    function formatDate(mixed $date, string $format = 'Y-m-d H:i:s'): ?string
    {
        if ($date === null) {
            return null;
        }

        if ($date instanceof DateTimeInterface) {
            return $date->format($format);
        }

        try {
            return \Illuminate\Support\Carbon::parse($date)->format($format);
        } catch (Throwable) {
            return null;
        }
    }
}
