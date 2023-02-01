<?php

use App\Enums\VerificationTypeEnum;

return [
    VerificationTypeEnum::INVISIBLE->value => [
        'max_attempts' => 20
    ],
    VerificationTypeEnum::TEXT->value => [
        'max_attempts' => 1000
    ]
];
