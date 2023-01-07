<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponse
{
    /**
     * Building success response
     * @param $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */

    public function successResponse($data = null, string $message = null, int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => 'ok',
            'code' => $code
        ], 200);
    }

    /**
     * @param $message
     * @param $code
     * @return JsonResponse
     */

    public function errorResponse($message, $code): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
            'status' => 'error'
        ], $code);
    }
}
