<?php

namespace App\Enums;

use App\Services\Captcha\Providers\InvisibleProvider;
use App\Services\Captcha\Providers\TextProvider;

enum ServiceTypeEnum: string
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
}