<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'driver_id',
        'vehicle_id',
        'vehicle_type_id',
        'city_id',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_address',
        'dropoff_latitude',
        'dropoff_longitude',
        'status',
        'estimated_distance_km',
        'actual_distance_km',
        'estimated_duration_minutes',
        'actual_duration_minutes',
        'base_fare',
        'distance_charge',
        'time_charge',
        'surge_multiplier',
        'surge_charge',
        'discount_amount',
        'promo_code',
        'total_amount',
        'passenger_count',
        'notes',
        'requested_at',
        'accepted_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'cancellation_by',
        'is_rated',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'accepted_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_rated' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function payment(): Model
    {
        return $this->hasOne(Payment::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
