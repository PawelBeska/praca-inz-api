<?php

namespace App\Services\Captcha\Providers;

use App\Dto\CaptchaGenerationDto;
use App\Dto\CaptchaVerificationDto;
use App\Enums\VerificationTypeEnum;
use App\Interfaces\DtoInterface;
use App\Services\Captcha\Dto\InvisibleDto;
use App\Services\Captcha\VerifyRules\ActiveRule;
use App\Services\Captcha\VerifyRules\HashRule;
use App\Services\Captcha\VerifyRules\IpAddressRule;
use App\Services\Captcha\VerifyRules\NotExpiredRule;
use App\Services\Captcha\VerifyRules\PrivateKeyRule;
use App\Services\Captcha\VerifyRules\ServiceIdRule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvisibleProvider extends VerificationProvider
{
    public function verify(CaptchaVerificationDto $captchaVerificationDto): bool
    {
        return (bool)$captchaVerificationDto->pipeThrough(
            [
                PrivateKeyRule::class,
                ActiveRule::class,
                ServiceIdRule::class,
                IpAddressRule::class,
                NotExpiredRule::class,
                HashRule::class
            ]
        )->thenReturn();
    }

    public function generate(CaptchaGenerationDto $captchaGenerationDto): DtoInterface
    {
        $token = Str::uuid();

        $verification = $this->verificationService->add(
            $token,
            VerificationTypeEnum::INVISIBLE,
            $captchaGenerationDto,
        );

        return new InvisibleDto(
            $verification,
            $token
        );
    }

    protected function active(CaptchaGenerationDto $captchaGenerationDto): bool
    {
        return $this->verificationRepository->getVerificationCountByIpAndDate(
                $captchaGenerationDto->ipAddress,
                Carbon::now()->subHour(),
                Carbon::now()->addHour()
            ) < VerificationTypeEnum::INVISIBLE->getConfig('max_attempts');
    }
}
