<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rater_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ratee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ride_id')->nullable()->constrained('rides')->nullOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries')->nullOnDelete();
            $table->enum('rating_for', ['driver', 'customer'])->default('driver');
            $table->integer('stars')->unsigned()->min(1)->max(5);
            $table->text('comment')->nullable();
            $table->json('categories')->nullable(); // Cleanliness, Communication, etc.
            $table->timestamps();
            $table->index(['ratee_id', 'rating_for']);
            $table->index(['rater_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
