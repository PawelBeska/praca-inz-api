<?php

namespace Database\Factories;

use App\Enums\ServiceStatusEnum;
use App\Enums\VerificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'service_id' => '',
            'active' => true,
            'ip_address' => '127.0.0.1',
            'type' => $this->faker->randomElement(VerificationTypeEnum::cases())->value,
            'control' => Hash::make('test'),
            'valid_until' => now()->addMonth()
        ];
    }
}
