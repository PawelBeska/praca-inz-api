<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceRepositoryInterface
{
    public function getServicesByUserAndPaginate(User|Authenticatable $user, int $perPage = 10): LengthAwarePaginator;
}
