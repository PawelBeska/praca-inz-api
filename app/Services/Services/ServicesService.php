<?php

namespace App\Services\Services;

use App\Models\Service;
use App\Models\User;

class ServicesService
{
    private Service $service;

    public function __construct(Service $service = null)
    {
        $this->service = $service ? $service : new Service();
    }

    /**
     * @param array $data
     * @param User $user
     * @return Service
     */
    public function assignData(array $data, User $user): Service
    {
        $this->service->user_id = $user->id;
        $this->service->name = $data['name'];
        $this->service->type = $data['type'];
        $this->service->status = $data['status'];
        $this->service->valid_until = $data['valid_until'];
        $this->service->save();
        return $this->service;
    }
}
