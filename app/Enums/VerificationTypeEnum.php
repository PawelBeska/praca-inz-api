<?php

namespace App\Enums;

use App\Interfaces\VerifyProviderInterface;
use App\Services\Captcha\Providers\InvisibleProvider;
use App\Services\Captcha\Providers\TextProvider;

enum VerificationTypeEnum: string
{
    case TEXT = 'text';
    case INVISIBLE = 'invisible';

    public function getVerifyProviders(): array
    {
        return [
            self::TEXT->value => TextProvider::class,
            self::INVISIBLE->value => InvisibleProvider::class
        ];
    }

    public function getVerifyProvider(): VerifyProviderInterface
    {
        return app($this->getVerifyProviders()[$this->value]);
    }

    public function getConfig(?string $key): mixed
    {
        return config('captcha.'.$this->value.($key ? ('.'.$key) : ''));
    }
}
