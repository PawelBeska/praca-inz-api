<?php

namespace App\Services\Captcha\Rules;

use App\Interfaces\CaptchaRuleInterface;
use Illuminate\Database\Eloquent\Builder;

class IpAddressRule implements CaptchaRuleInterface
{
    public function __construct(
        private readonly string $ipAddress,
    ) {
    }

    public function validate(Builder $query): Builder
    {
        return $query->where('ip_address', '=', $this->ipAddress);
    }
}