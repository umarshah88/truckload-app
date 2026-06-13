<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'vehicle_type_id',
        'base_fare',
        'per_km_rate',
        'per_minute_rate',
        'minimum_fare',
        'cancellation_charge',
        'surge_multipliers',
        'is_active',
        'effective_from',
        'effective_until',
    ];

    protected $casts = [
        'surge_multipliers' => 'json',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }
}
