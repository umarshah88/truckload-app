<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'urdu_name',
        'slug',
        'code',
        'latitude',
        'longitude',
        'region',
        'zones',
        'is_active',
        'base_fare',
        'per_km_rate',
        'per_minute_rate',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
        'zones' => 'json',
    ];

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
