<?php

namespace App\Enums;

enum ServiceStatusEnum: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case BLOCKED = 'blocked';
}