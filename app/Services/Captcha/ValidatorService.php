<?php

namespace App\Services\Captcha;

use App\Interfaces\CaptchaRuleInterface;
use App\Interfaces\RuleWithParametersInterface;
use App\Models\Verification;
use Illuminate\Database\Eloquent\Builder;

class ValidatorService
{
    private array $rules = [];

    private Builder $query;

    public function __construct()
    {
    }

    public function setQuery(Builder $query): static
    {
        $this->query = $query;
        return $this;
    }

    public function clearRules(): static
    {
        $this->rules = [];
        return $this;
    }

    public function addRule(CaptchaRuleInterface $rule): ValidatorService
    {
        $this->rules[] = $rule;
        return $this;
    }

    public function validate(): Builder
    {
        foreach ($this->rules as $rule) {
            $this->query = $rule->validate($this->query);
        }

        return $this->query;
    }
}