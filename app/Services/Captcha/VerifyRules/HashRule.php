<?php

namespace App\Services\Captcha\VerifyRules;

use App\Dto\CaptchaVerificationDto;
use App\Interfaces\VerifyRuleInterface;
use Closure;
use Illuminate\Support\Facades\Hash;

class HashRule implements VerifyRuleInterface
{
    public function handle(CaptchaVerificationDto $captchaVerificationDto, Closure $next)
    {
        if ($this->validate($captchaVerificationDto)) {
            return $next($captchaVerificationDto);
        }
        return false;
    }

    private function validate(CaptchaVerificationDto $captchaVerificationDto): bool
    {
        return Hash::check($captchaVerificationDto->answer, $captchaVerificationDto->verification->control);
    }
}
