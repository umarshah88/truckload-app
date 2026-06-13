<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types');
            $table->string('plate_number')->unique();
            $table->string('model');
            $table->string('color')->nullable();
            $table->integer('year')->nullable();
            $table->string('vin')->nullable()->unique();
            $table->string('registration_number')->nullable()->unique();
            $table->string('registration_document_url')->nullable();
            $table->string('insurance_document_url')->nullable();
            $table->timestamp('registration_expiry')->nullable();
            $table->timestamp('insurance_expiry')->nullable();
            $table->string('inspection_certificate_url')->nullable();
            $table->timestamp('inspection_expiry')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(false);
            $table->integer('total_trips')->default(0);
            $table->decimal('average_rating', 3, 2)->default(5);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['driver_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
