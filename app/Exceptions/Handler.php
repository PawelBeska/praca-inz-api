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
use Throwable;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'status' => "error",
            'code' => $exception->status,
            'message' => $exception->getMessage(),
            'data' => $this->transformErrors($exception),

        ], $exception->status);
    }

    /**
     * @param ValidationException $exception
     * @return array
     */
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

    /**
     * @param $request
     * @param Throwable $e
     * @return Response|JsonResponse|Redirector|RedirectResponse|Application|ResponseAlias
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|Redirector|RedirectResponse|Application|ResponseAlias
    {
        if($e instanceof ModelNotFoundException){
            return response()->json([
                'status' => "error",
                'code' => "422",
                'message' => "Object not found",

            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        return parent::render($request, $e);
    }
}
