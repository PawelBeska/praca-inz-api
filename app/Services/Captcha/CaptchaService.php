<?php

namespace App\Services\Captcha;

use App\Dto\CaptchaGenerationDto;
use App\Dto\CaptchaVerificationDto;
use App\Services\Captcha\Providers\InvisibleProvider;
use App\Services\Captcha\Providers\TextProvider;
use Illuminate\Support\Facades\Log;

class CaptchaService
{
    public function __construct(
        private readonly VerificationService $verificationService,
    ) {
    }

    public function generate(CaptchaGenerationDto $captchaGenerationDto): array
    {
        return $captchaGenerationDto->pipeThrough(
            [
                InvisibleProvider::class,
                TextProvider::class
            ]
        )
            ->thenReturn()
            ->toArray();
    }

    public function verify(CaptchaVerificationDto $captchaVerificationDto): bool
    {
        $verificationData = $captchaVerificationDto->verification
            ->type
            ->getVerificationProvider()
            ->verify($captchaVerificationDto);

        $this->verificationService
            ->setInstance($captchaVerificationDto->verification)
            ->setActive(false);

        return $verificationData;
    }
}
