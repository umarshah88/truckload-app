<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('referral_code')->unique();
            $table->string('referral_link')->unique();
            $table->decimal('referrer_reward', 10, 2)->default(0);
            $table->decimal('referred_reward', 10, 2)->default(0);
            $table->integer('min_rides_required')->default(1);
            $table->integer('referred_user_rides')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
            $table->index(['referrer_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
