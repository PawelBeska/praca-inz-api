<?php

namespace App\Services\Captcha;

use App\Models\Service;
use App\Models\Verification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class VerificationService
{
    /**
     * @param Verification $verification
     */
    public function __construct(private Verification $verification = new Verification())
    {
    }


    /**
     * @param string $type
     * @param string $text
     * @param Service $service
     * @return Verification
     */
    public function add(string $type, string $text, Service $service): Verification
    {
        return $this->assignData([
            'type' => $type,
            'active' => true,
            'text' => $text,
        ], $service);
    }

    /**
     * @param array $data
     * @param Service $service
     * @return Verification
     */
    public function assignData(array $data, Service $service): Verification
    {
        $this->verification->type = $data['type'];
        $this->verification->service_id = $service->id;
        $this->verification->active = $data['active'];
        $this->verification->control = Hash::make($data['text']);
        $this->verification->valid_until = Carbon::now()->addMinutes(5)->toDateTime();
        $this->verification->save();
        return $this->verification;
    }
}
