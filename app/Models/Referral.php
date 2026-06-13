<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'referral_code',
        'referral_link',
        'referrer_reward',
        'referred_reward',
        'min_rides_required',
        'referred_user_rides',
        'is_active',
        'activated_at',
        'claimed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
