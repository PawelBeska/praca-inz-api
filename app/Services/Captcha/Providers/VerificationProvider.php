<?php

namespace App\Services\Captcha\Providers;

use App\Dto\CaptchaGenerationDto;
use App\Dto\CaptchaVerificationDto;
use App\Interfaces\DtoInterface;
use App\Repositories\VerificationRepositoryInterface;
use App\Services\Captcha\VerificationService;
use Closure;

abstract class VerificationProvider
{
    public function __construct(
        protected readonly VerificationRepositoryInterface $verificationRepository,
        protected readonly VerificationService $verificationService,
    ) {
    }

    public function handle(CaptchaGenerationDto $captchaGenerationDto, Closure $next)
    {
        if (!$this->active($captchaGenerationDto)) {
            return $next($captchaGenerationDto);
        }

        return $this->generate($captchaGenerationDto);
    }

    abstract protected function active(CaptchaGenerationDto $captchaGenerationDto): bool;

    abstract public function verify(CaptchaVerificationDto $captchaVerificationDto): bool;

    abstract public function generate(CaptchaGenerationDto $captchaGenerationDto): DtoInterface;
}