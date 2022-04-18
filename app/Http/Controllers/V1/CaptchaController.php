<?php

namespace App\Http\Controllers\V1;

use App\Enums\ServiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\Captcha;
use Carbon\Carbon;
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

    public function generate(Request $request, Service $service): JsonResponse
    {
        try {

            if (
                $service->type === ServiceTypeEnum::INVISIBLE->value &&
                Verification::where('ip_address', '=', $request->ip())
                    ->whereTime('valid_until', '>=', Carbon::now()->addHours(-1))
                    ->whereTime('valid_until', '<', Carbon::now()->addHour())->count() < 500
            ) {
                $captcha = (new Captcha(ServiceTypeEnum::INVISIBLE, $service, $request))->getVerifyProvider()->generate();
            } else {
                $captcha = (new Captcha(ServiceTypeEnum::TEXT, $service, $request))->getVerifyProvider()->generate();
            }
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
            $captcha = (new Captcha(ServiceTypeEnum::from($verification->type), $service, $request))
                ->getVerifyProvider()
                ->verify($verification, $request);
            return $this->successResponse($captcha);
        } catch (\Exception $e) {
            $this->reportError($e);
            return $this->errorResponse(__('Something went wrong.'), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
