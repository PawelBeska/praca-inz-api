<?php
namespace App\Interfaces;

use App\Dto\CaptchaVerificationDto;
use Closure;

interface VerifyRuleInterface
{
    public function handle(CaptchaVerificationDto $captchaVerificationDto, Closure $next);
}
