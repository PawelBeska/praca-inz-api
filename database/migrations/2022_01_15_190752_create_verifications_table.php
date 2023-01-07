<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_id')->constrained('services')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('active')->default(1);
            $table->ipAddress();
            $table->string('type');
            $table->string('control');
            $table->datetime('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
}
