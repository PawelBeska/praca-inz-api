<?php

namespace App\Dto;

use App\Enums\ServiceStatusEnum;
use App\Interfaces\DtoInterface;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Traits\Conditionable;

class ServiceDto implements DtoInterface
{
    use Conditionable;

    public function __construct(
        public string $name,
        public User|Authenticatable $user,
        public ServiceStatusEnum $status = ServiceStatusEnum::ACTIVE,
        public ?DateTimeInterface $valid_until = null,
        public ?string $private_key = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'user_id' => $this->user->id,
            ...$this->when($this->status, fn() => ['status' => $this->status], fn() => []),
            ...$this->when($this->valid_until, fn() => ['valid_until' => $this->valid_until], fn() => []),
            ...$this->when($this->private_key, fn() => ['private_key' => Crypt::encrypt($this->private_key)], fn() => []),
        ];
    }
}
