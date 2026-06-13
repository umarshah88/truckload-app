<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('reserved_balance', 12, 2)->default(0); // For pending transactions
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_earned', 12, 2)->default(0); // For drivers
            $table->decimal('total_spent', 12, 2)->default(0); // For customers
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
