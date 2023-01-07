<?php

namespace App\Dto;

use App\Interfaces\DtoInterface;
use App\Models\Service;
use App\Traits\Pipable;

class CaptchaGenerationDto implements DtoInterface
{
    use Pipable;

    public function __construct(
        public Service $service,
        public string $ipAddress,
    ) {
    }


    public function toArray(): array
    {
        return [

        ];
    }
}
