<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaptchaGenerateRequest;
use App\Http\Requests\CaptchaVerifyRequest;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\CaptchaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CaptchaController extends Controller
{
    public function __construct(
        private readonly CaptchaService $captchaService
    ) {
    }

    public function generate(CaptchaGenerateRequest $request, Service $service): JsonResponse
    {
        try {
            return $this->successResponse(
                $this->captchaService->generate(
                    $request->toDto()
                )
            );
        } catch (Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('messages.Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function verify(CaptchaVerifyRequest $request, Service $service, Verification $verification): JsonResponse
    {
        try {
            return $this->successResponse(
                $this->captchaService->verify(
                    $request->toDto()
                )
            );
        } catch (Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('messages.Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
