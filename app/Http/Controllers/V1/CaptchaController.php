<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CaptchaController extends Controller
{


    /**
     * @param Request $request
     * @param Service $service
     * @return JsonResponse
     */
    #[ArrayShape([
            'image' => "string",
            'token' => "\Ramsey\Uuid\UuidInterface"]
    )]
    public function generate(Request $request, Service $service): JsonResponse
    {
        try {
            $captcha = (new Captcha('text', $service, $request))->getVerifyProvider()->generate();
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /**
     * @param Request $request
     * @param Service $service
     * @param Verification $verification
     * @return JsonResponse
     */
    public function verify(Request $request, Service $service, Verification $verification): JsonResponse
    {
        try {
            $captcha = (new Captcha('text', $service, $request))
                ->getVerifyProvider()
                ->verify($verification, $request);
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
