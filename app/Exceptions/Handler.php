<?php

namespace App\Exceptions;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'status' => "error",
            'code' => $exception->status,
            'message' => $exception->getMessage(),
            'data' => $this->transformErrors($exception),

        ], $exception->status);
    }

    private function transformErrors(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
            $errors[] = [
                'field' => $field,
                'message' => $message[0],
            ];
        }

        return $errors;
    }

    public function render($request, Throwable $e): Response|JsonResponse|Redirector|RedirectResponse|Application|ResponseAlias
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'status' => "error",
                'code' => "422",
                'message' => "Object not found",

            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => "error",
                'code' => "422",
                'message' => "Page not found",

            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        return parent::render($request, $e);
    }
}
