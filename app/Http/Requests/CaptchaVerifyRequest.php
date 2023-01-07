<?php

namespace App\Http\Requests;

use App\Dto\CaptchaVerificationDto;
use App\Interfaces\RequestToDtoInterface;
use App\Models\Service;
use App\Models\Verification;
use Illuminate\Foundation\Http\FormRequest;
/**
 * @property Service $service
 * @property Verification $verification
 */
class CaptchaVerifyRequest extends FormRequest implements RequestToDtoInterface
{
    public function rules(): array
    {
        return [
            'private_key'=>['required', 'string', 'max:255'],
            'answer'=>['string', 'max:255'],
        ];
    }

    public function toDto(): CaptchaVerificationDto
    {
        return new CaptchaVerificationDto(
            $this->service,
            $this->verification,
            $this->getClientIp(),
            $this->get('private_key'),
            $this->get('answer')
        );
    }
}
