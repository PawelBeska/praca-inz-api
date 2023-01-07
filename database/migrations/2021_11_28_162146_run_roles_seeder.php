<?php

use Database\Seeders\CreateRolesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RunRolesSeeder extends Migration
{
    public function up(): void
    {
        (new CreateRolesSeeder)->run();
    }

    public function down(): void
    {
        //
    }
}
