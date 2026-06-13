<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'urdu_name',
        'slug',
        'category',
        'icon_url',
        'min_capacity',
        'max_capacity',
        'commission_rate',
        'base_fare',
        'per_km_rate',
        'per_minute_rate',
        'max_weight_kg',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }
}
