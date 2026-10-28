<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        array|string $message,
        mixed $data = null,
        int $status = 200,
    ): JsonResponse {
        return response()->json([
            'message' => (array) $message,
            'data' => $data,
        ], $status);
    }

    public static function error(
        array|string $message,
        int $status = 400,
        mixed $data = null,
    ): JsonResponse {
        return response()->json([
            'message' => (array) $message,
            'data' => $data,
        ], $status);
    }
}
