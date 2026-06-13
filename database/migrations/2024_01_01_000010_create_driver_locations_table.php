<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ride_id')->nullable()->constrained('rides')->nullOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries')->nullOnDelete();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable(); // meters
            $table->integer('heading')->nullable(); // degrees
            $table->decimal('speed', 8, 2)->nullable(); // km/h
            $table->boolean('is_online')->default(false);
            $table->boolean('is_available')->default(false);
            $table->timestamp('recorded_at');
            $table->timestamps();
            $table->index(['driver_id', 'recorded_at']);
            $table->index(['ride_id']);
            $table->index(['delivery_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};
