<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'phone',
        'email',
        'name',
        'password',
        'role',
        'role_id',
        'is_verified',
        'is_active',
        'is_blocked',
        'verified_at',
        'last_login_at',
        'avatar_url',
        'metadata',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'is_blocked' => 'boolean',
        'verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'driver_id');
    }

    public function ridesCasCustomer(): HasMany
    {
        return $this->hasMany(Ride::class, 'customer_id');
    }

    public function ridesAsDriver(): HasMany
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }

    public function deliveriesAsSender(): HasMany
    {
        return $this->hasMany(Delivery::class, 'sender_id');
    }

    public function deliveriesAsDriver(): HasMany
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function receivedRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'ratee_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DriverDocument::class, 'driver_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
