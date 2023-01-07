<?php

namespace App\Providers;

use App\Repositories\ServiceRepository;
use App\Repositories\ServiceRepositoryInterface;
use App\Repositories\VerificationRepository;
use App\Repositories\VerificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        VerificationRepositoryInterface::class => VerificationRepository::class,
        ServiceRepositoryInterface::class => ServiceRepository::class,
    ];
}
