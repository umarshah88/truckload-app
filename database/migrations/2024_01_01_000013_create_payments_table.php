<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ride_id')->nullable()->constrained('rides')->nullOnDelete();
            $table->foreignId('delivery_id')->nullable()->constrained('deliveries')->nullOnDelete();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('reference_number')->unique();
            $table->enum('type', ['ride', 'delivery', 'wallet_topup', 'refund', 'payout'])->default('ride');
            $table->enum('payment_method', ['wallet', 'card', 'bank_transfer', 'jazz_cash', 'easypaisa', 'sadapay', 'cash'])
                ->default('wallet');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled'])
                ->default('pending');
            $table->decimal('amount', 12, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->string('currency')->default('PKR');
            $table->string('gateway_name')->nullable(); // stripe, jazz_cash, etc.
            $table->string('gateway_response')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['reference_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
