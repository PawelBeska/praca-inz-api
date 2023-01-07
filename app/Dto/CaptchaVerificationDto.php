<?php

namespace App\Dto;

use App\Interfaces\DtoInterface;
use App\Models\Service;
use App\Models\Verification;
use App\Traits\Pipable;

class CaptchaVerificationDto implements DtoInterface
{
    use Pipable;

    public function __construct(
        public Service $service,
        public Verification $verification,
        public string $ipAddress,
        public string $privateKey,
        public string $answer
    ) {
    }

    public function toArray(): array
    {
        return [
        ];
    }
}
