<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
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
        'receiver_name',
        'receiver_phone',
        'status',
        'description',
        'weight_kg',
        'item_type',
        'estimated_distance_km',
        'actual_distance_km',
        'estimated_duration_minutes',
        'actual_duration_minutes',
        'base_charge',
        'weight_charge',
        'distance_charge',
        'platform_fee',
        'discount_amount',
        'promo_code',
        'total_amount',
        'payment_method',
        'notes',
        'picked_up_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'is_rated',
    ];

    protected $casts = [
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_rated' => 'boolean',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
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

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
