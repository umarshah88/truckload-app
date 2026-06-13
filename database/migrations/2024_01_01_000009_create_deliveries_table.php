<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
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
            $table->string('receiver_name');
            $table->string('receiver_phone', 15);
            $table->enum('status', [
                'pending',
                'searching',
                'accepted',
                'picked_up',
                'in_transit',
                'delivered',
                'failed',
                'cancelled'
            ])->default('pending');
            $table->string('description');
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->string('item_type')->nullable();
            $table->decimal('estimated_distance_km', 8, 2)->nullable();
            $table->decimal('actual_distance_km', 8, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->integer('actual_duration_minutes')->nullable();
            $table->decimal('base_charge', 10, 2)->nullable();
            $table->decimal('weight_charge', 10, 2)->default(0);
            $table->decimal('distance_charge', 10, 2)->nullable();
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('promo_code')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('payment_method', ['wallet', 'card', 'cash', 'cod'])->default('wallet');
            $table->text('notes')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->boolean('is_rated')->default(false);
            $table->index(['sender_id', 'status']);
            $table->index(['driver_id', 'status']);
            $table->index(['city_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
