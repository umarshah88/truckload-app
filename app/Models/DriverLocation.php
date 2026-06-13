<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'ride_id',
        'delivery_id',
        'latitude',
        'longitude',
        'accuracy',
        'heading',
        'speed',
        'is_online',
        'is_available',
        'recorded_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_online' => 'boolean',
        'is_available' => 'boolean',
        'recorded_at' => 'datetime',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }
}
