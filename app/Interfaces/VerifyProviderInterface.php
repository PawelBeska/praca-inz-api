<?php

namespace App\Interfaces;

use App\Dto\CaptchaGenerationDto;
use App\Dto\CaptchaVerificationDto;
use Closure;

interface  VerifyProviderInterface
{
    public function handle(CaptchaGenerationDto $captchaGenerationDto, Closure $next);

    public function verify(CaptchaVerificationDto $captchaVerificationDto): bool;

    public function generate(CaptchaGenerationDto $captchaGenerationDto): DtoInterface;
}
