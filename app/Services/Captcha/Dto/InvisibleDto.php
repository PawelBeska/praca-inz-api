<?php

namespace App\Services\Captcha\Dto;

use App\Enums\VerificationTypeEnum;
use App\Interfaces\DtoInterface;
use App\Models\Verification;

class InvisibleDto implements DtoInterface
{
    public function __construct(
        public Verification $verification,
        public string $accessToken,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => VerificationTypeEnum::INVISIBLE->value,
            'verification_id' => $this->verification->id,
            'access_token' => $this->accessToken
        ];
    }
}
