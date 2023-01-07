<?php

namespace App\Services\Captcha\Dto;

use App\Enums\VerificationTypeEnum;
use App\Interfaces\DtoInterface;
use App\Models\Verification;
use Carbon\Carbon;

class TextDto implements DtoInterface
{

    public function __construct(
        public Verification $verification,
        public string $image,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => VerificationTypeEnum::TEXT->value,
            'image' => $this->image,
            'verification_id' => $this->verification->id
        ];
    }
}

