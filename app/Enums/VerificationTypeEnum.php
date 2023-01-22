<?php

namespace App\Enums;

use App\Services\Captcha\Providers\InvisibleProvider;
use App\Services\Captcha\Providers\TextProvider;
use App\Services\Captcha\Providers\VerificationProvider;

enum VerificationTypeEnum: string
{
    case TEXT = 'text';
    case INVISIBLE = 'invisible';

    public function getVerificationProviders(): array
    {
        return [
            self::TEXT->value => TextProvider::class,
            self::INVISIBLE->value => InvisibleProvider::class
        ];
    }

    public function getVerificationProvider(): VerificationProvider
    {
        return app($this->getVerificationProviders()[$this->value]);
    }

    public function getConfig(?string $key): mixed
    {
        return config('captcha.'.$this->value.($key ? ('.'.$key) : ''));
    }
}
