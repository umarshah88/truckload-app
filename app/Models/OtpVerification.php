<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp',
        'type',
        'is_verified',
        'attempts',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }
}
