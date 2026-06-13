<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'reserved_balance',
        'total_transactions',
        'total_earned',
        'total_spent',
        'last_transaction_at',
    ];

    protected $casts = [
        'last_transaction_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
        $this->increment('total_transactions');
        $this->update(['last_transaction_at' => now()]);
    }

    public function deductBalance(float $amount): bool
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            $this->increment('total_transactions');
            $this->update(['last_transaction_at' => now()]);
            return true;
        }
        return false;
    }
}
