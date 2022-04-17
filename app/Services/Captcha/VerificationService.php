<?php

namespace App\Services\Captcha;

use App\Models\Service;
use App\Models\Verification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class VerificationService
{
    private Verification $verification;


    /**
     * @param Verification|null $verification
     */
    public function __construct(Verification $verification = null)
    {
        $this->verification = $verification ?: new Verification();
    }


    /**
     * @param string $type
     * @param string $text
     * @param Service $service
     * @param string $ipAddress
     * @return Verification
     */
    public function add(string $type, string $text, Service $service, string $ipAddress): Verification
    {
        return $this->assignData([
            'type' => $type,
            'active' => true,
            'text' => $text,
            'ip_address' => $ipAddress
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
