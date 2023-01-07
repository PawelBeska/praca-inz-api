<?php

namespace App\Services\Services;

use App\Dto\ServiceDto;
use App\Models\Service;

class ServicesService
{
    public function __construct(private Service $service = new Service())
    {
    }

    public function setInstance(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function assignData(
        ServiceDto $serviceDto,
    ): Service {
        return Service::query()->updateOrCreate(
            ['id' => $this->service->id],
            $serviceDto->toArray()
        );
    }
}
