<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface CaptchaRuleInterface
{
    public function validate(Builder $query): Builder;
}