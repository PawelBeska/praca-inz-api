<?php

namespace Database\Factories;

use App\Enums\ServiceStatusEnum;
use App\Enums\ServiceTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => null,
            "name" => $this->faker->name,
            "type" => ServiceTypeEnum::TEXT->value,
            "status" => ServiceStatusEnum::ACTIVE->value,
            "valid_until" => Carbon::now()->addMonth(),
            "private_key" => Hash::make(Str::uuid())
        ];

    }
}
