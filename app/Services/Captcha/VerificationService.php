<?php

namespace App\Services\Captcha;

use App\Dto\CaptchaGenerationDto;
use App\Enums\VerificationTypeEnum;
use App\Models\Service;
use App\Models\Verification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class VerificationService
{
    public function __construct(
        private Verification $verification = new Verification()
    ) {
    }

    public function setInstance(Verification $verification): static
    {
        $this->verification = $verification;

        return $this;
    }

    public function add(string $text, VerificationTypeEnum $verificationTypeEnum, CaptchaGenerationDto $captchaGenerationDto): Verification
    {
        return $this->assignData([
            'type' => $verificationTypeEnum,
            'text' => $text,
            'ip_address' => $captchaGenerationDto->ipAddress,
            'active' => true,
        ], $captchaGenerationDto->service);
    }

    public function assignData(array $data, Service $service): Verification
    {
        $this->verification->type = $data['type'];
        $this->verification->service_id = $service->id;
        $this->verification->active = $data['active'];
        $this->verification->control = Hash::make($data['text']);
        $this->verification->ip_address = $data['ip_address'];
        $this->verification->valid_until = Carbon::now()->addMinutes(5)->toDateTime();

        $this->verification->save();

        return $this->verification;
    }

    public function setActive(bool $active): VerificationService
    {
        $this->verification->active = $active;
        $this->verification->save();

        return $this;
    }
}
