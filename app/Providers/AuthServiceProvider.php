<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\User;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use App\Services\User\UserService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Service::class => ServicePolicy::class,
        User::class => UserPolicy::class,

    ];

    public function boot(): void
    {
        $this->registerPolicies();
        //
    }
}
