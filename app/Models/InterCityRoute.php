<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterCityRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_city_id',
        'to_city_id',
        'distance_km',
        'estimated_duration_hours',
        'base_fare',
        'per_km_rate',
        'base_fare_delivery',
        'per_km_rate_delivery',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function fromCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }

    public function toCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }
}
