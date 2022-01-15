<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\VerifyProviderNotFound;
use App\Http\Controllers\Controller;
use App\Models\Service;
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
    /**
     * @param Service $service
     * @return JsonResponse
     */
    #[ArrayShape([
            'image' => "string",
            'token' => "\Ramsey\Uuid\UuidInterface"]
    )]
    public function generate(Service $service): JsonResponse
    {
        try {
            $captcha = (new Captcha('text', $service))->getVerifyProvider()->generate();
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            dd($e);
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function verify()
    {

    }
}
