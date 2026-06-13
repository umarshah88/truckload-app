<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inter_city_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_city_id')->constrained('cities');
            $table->foreignId('to_city_id')->constrained('cities');
            $table->decimal('distance_km', 10, 2);
            $table->integer('estimated_duration_hours');
            $table->decimal('base_fare', 10, 2);
            $table->decimal('per_km_rate', 8, 2);
            $table->decimal('base_fare_delivery', 10, 2);
            $table->decimal('per_km_rate_delivery', 8, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->unique(['from_city_id', 'to_city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inter_city_routes');
    }
};
