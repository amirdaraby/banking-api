<?php

namespace App\Http\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseJson
{
    public static function success($data, string $message, int $code = 200): JsonResponse
    {
        return self::toJson($data, $message, $code);
    }

    public static function error(string $message, int $code = 400, $data = null): JsonResponse
    {
        return self::toJson($data, $message, $code);
    }

    private static function toJson($data, string $message, int $code): JsonResponse
    {
        return new JsonResponse(["data" => $data, "message" => $message], $code);
    }
}
