<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types');
            $table->foreignId('city_id')->constrained('cities');
            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 8);
            $table->decimal('pickup_longitude', 11, 8);
            $table->string('dropoff_address');
            $table->decimal('dropoff_latitude', 10, 8);
            $table->decimal('dropoff_longitude', 11, 8);
            $table->enum('status', [
                'requested',
                'searching',
                'accepted',
                'driver_arrived',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('requested');
            $table->decimal('estimated_distance_km', 8, 2)->nullable();
            $table->decimal('actual_distance_km', 8, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->integer('actual_duration_minutes')->nullable();
            $table->decimal('base_fare', 10, 2)->nullable();
            $table->decimal('distance_charge', 10, 2)->nullable();
            $table->decimal('time_charge', 10, 2)->nullable();
            $table->decimal('surge_multiplier', 4, 2)->default(1);
            $table->decimal('surge_charge', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('promo_code')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->integer('passenger_count')->default(1);
            $table->text('notes')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->string('cancellation_by')->nullable(); // customer, driver, system
            $table->boolean('is_rated')->default(false);
            $table->timestamps();
            $table->index(['customer_id', 'status', 'created_at']);
            $table->index(['driver_id', 'status']);
            $table->index(['city_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
