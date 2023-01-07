<?php

namespace App\Repositories;

use Carbon\Carbon;

interface VerificationRepositoryInterface
{
    public function getVerificationCountByIpAndDate(?string $ip, ?Carbon $dateFrom, ?Carbon $dateTo): int;
}
