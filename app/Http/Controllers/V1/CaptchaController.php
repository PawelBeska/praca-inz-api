<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\VerifyProviderNotFound;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Font;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CaptchaController extends Controller
{
    private Service $service;

    public function __construct(?Service $service)
    {
        $this->service = $service;
    }

    /**
     * @param Service $service
     * @return JsonResponse
     */
    #[ArrayShape([
            'image' => "string",
            'token' => "\Ramsey\Uuid\UuidInterface"]
    )]
    public function generate(): JsonResponse
    {
        try {
            $captcha = (new Captcha('text', $this->service))->getVerifyProvider()->generate();
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    /**
     * @param Verification $verification
     * @return JsonResponse
     */
    public function verify(Verification $verification): JsonResponse
    {
        try {
            $captcha = (new Captcha('text', $this->service))->getVerifyProvider()->verify($verification);
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
