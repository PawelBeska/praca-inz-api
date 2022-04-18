<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\User;
use App\Policies\ServicePolicy;
use App\Services\User\UserService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Service::class => ServicePolicy::class,
        User::class => UserService::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
