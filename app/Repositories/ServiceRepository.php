<?php

namespace App\Repositories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function getServicesByUserAndPaginate(User|Authenticatable $user, int $perPage = 10): LengthAwarePaginator
    {
        return Service::query()
            ->where('user_id', $user->id)
            ->paginate();
    }
}
