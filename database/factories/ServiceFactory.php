<?php

namespace Database\Factories;

use App\Enums\ServiceStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'status' => ServiceStatusEnum::ACTIVE->value,
            'valid_until' => now()->addMonth(),
            'private_key' => Crypt::encrypt('test')
        ];
    }
}
