<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::factory()->create();
        $this->command->info('Service created with id: '.$service->id);
        $this->command->info('Service private key: test');
    }
}
