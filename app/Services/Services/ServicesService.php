<?php

namespace App\Services\Services;

use App\Enums\ServiceStatusEnum;
use App\Enums\ServiceTypeEnum;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ServicesService
{
    private Service $service;

    public function __construct(Service $service = null)
    {
        $this->service = $service ?: new Service();
    }

    /**
     * @param string $name
     * @param \App\Enums\ServiceTypeEnum $type
     * @param \App\Enums\ServiceStatusEnum $status
     * @param \Illuminate\Support\Carbon $valid_until
     * @param \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User $user
     * @return Service
     */
    public function assignData(
        string               $name,
        ServiceTypeEnum      $type,
        ServiceStatusEnum    $status,
        Carbon               $valid_until,
        Authenticatable|User $user,
        string               $private_key = null
    ): Service
    {
        $this->service->user_id = $user->id;
        $this->service->name = $name;
        $this->service->type = $type;
        $this->service->status = $status;
        $this->service->valid_until = $valid_until;
        if ($private_key) {
            $this->service->private_key = Hash::make($private_key);
        }
        $this->service->save();
        return $this->service;
    }
}
