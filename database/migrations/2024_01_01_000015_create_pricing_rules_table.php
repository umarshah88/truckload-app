<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnDelete();
            $table->decimal('base_fare', 10, 2);
            $table->decimal('per_km_rate', 8, 2);
            $table->decimal('per_minute_rate', 8, 2);
            $table->decimal('minimum_fare', 10, 2);
            $table->decimal('cancellation_charge', 10, 2)->default(0);
            $table->json('surge_multipliers')->nullable(); // Time-based surge
            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_from');
            $table->timestamp('effective_until')->nullable();
            $table->timestamps();
            $table->unique(['city_id', 'vehicle_type_id', 'effective_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
