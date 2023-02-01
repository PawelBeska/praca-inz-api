<?php

namespace App\Services\Captcha\VerifyRules;

use App\Dto\CaptchaVerificationDto;
use App\Interfaces\VerifyRuleInterface;
use Closure;
use Illuminate\Support\Facades\Crypt;

class PrivateKeyRule implements VerifyRuleInterface
{
    public function handle(CaptchaVerificationDto $captchaVerificationDto, Closure $next)
    {
        ray($this->validate($captchaVerificationDto));
        if ($this->validate($captchaVerificationDto)) {
            return $next($captchaVerificationDto);
        }
        return false;
    }

    private function validate(CaptchaVerificationDto $captchaVerificationDto): bool
    {
        return $captchaVerificationDto->privateKey === Crypt::decrypt($captchaVerificationDto->service->private_key);
    }
}
