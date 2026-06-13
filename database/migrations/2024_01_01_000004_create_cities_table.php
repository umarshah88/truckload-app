<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('urdu_name')->nullable();
            $table->string('slug')->unique();
            $table->string('code', 10)->unique();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('region')->nullable();
            $table->json('zones')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('base_fare', 10, 2)->default(50); // PKR
            $table->decimal('per_km_rate', 8, 2)->default(15); // PKR
            $table->decimal('per_minute_rate', 8, 2)->default(2); // PKR
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
