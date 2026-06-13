<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('urdu_name')->nullable();
            $table->string('slug')->unique();
            $table->enum('category', ['ride', 'delivery'])->default('ride');
            $table->string('icon_url')->nullable();
            $table->integer('min_capacity')->default(1);
            $table->integer('max_capacity')->default(4);
            $table->decimal('commission_rate', 5, 2)->default(15); // Percentage
            $table->decimal('base_fare', 10, 2)->nullable();
            $table->decimal('per_km_rate', 8, 2)->nullable();
            $table->decimal('per_minute_rate', 8, 2)->nullable();
            $table->decimal('max_weight_kg', 8, 2)->nullable(); // For delivery
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
