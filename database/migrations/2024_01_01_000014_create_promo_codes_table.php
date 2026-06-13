<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->integer('usage_limit')->nullable();
            $table->integer('user_usage_limit')->default(1);
            $table->integer('times_used')->default(0);
            $table->enum('applicable_for', ['ride', 'delivery', 'all'])->default('all');
            $table->json('applicable_cities')->nullable();
            $table->json('applicable_vehicle_types')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
