<?php

namespace Database\Seeders;

use App\Services\Role\RoleService;
use Illuminate\Database\Seeder;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new RoleService())->assignData([
            'name' => "Użytkownik",
            'slug' => "user",
        ]);
        (new RoleService())->assignData([
            'name' => "Administrator",
            'slug' => "admin",
        ]);
    }
}
