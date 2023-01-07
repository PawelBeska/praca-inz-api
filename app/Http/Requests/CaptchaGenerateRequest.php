<?php

namespace App\Http\Requests;

use App\Dto\CaptchaGenerationDto;
use App\Interfaces\RequestToDtoInterface;
use App\Models\Service;
use App\Models\Verification;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property Verification $verification
 * @property Service $service
 */
class CaptchaGenerateRequest extends FormRequest implements RequestToDtoInterface
{
    public function rules(): array
    {
        return [

        ];
    }

    public function toDto(): CaptchaGenerationDto
    {
        return new CaptchaGenerationDto(
            $this->service,
            $this->getClientIp()
        );
    }
}
