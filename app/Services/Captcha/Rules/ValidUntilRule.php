<?php

namespace App\Services\Captcha\Rules;

use App\Interfaces\CaptchaRuleInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ValidUntilRule implements CaptchaRuleInterface
{
    public function __construct()
    {
    }

    public function validate(Builder $query): Builder
    {
        return $query
            ->whereTime('valid_until', '>=', Carbon::now()->addHours(-1))
            ->whereTime('valid_until', '<', Carbon::now()->addHour());
    }
}