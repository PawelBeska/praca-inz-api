<?php

namespace App\Repositories;

use App\Models\Verification;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class VerificationRepository implements VerificationRepositoryInterface
{

    public function getVerificationCountByIpAndDate(
        ?string $ip,
        ?Carbon $dateFrom,
        ?Carbon $dateTo
    ): int {
        return Verification::query()
            ->when($ip, fn(Builder $query) => $query->where('ip_address', $ip))
            ->when($dateFrom, fn(Builder $query) => $query->whereDate('valid_until', '>=', $dateFrom))
            ->when($dateTo, fn(Builder $query) => $query->whereDate('valid_until', '<=', $dateTo))
            ->count();
    }
}
