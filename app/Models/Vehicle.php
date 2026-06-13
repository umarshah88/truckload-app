<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'vehicle_type_id',
        'plate_number',
        'model',
        'color',
        'year',
        'vin',
        'registration_number',
        'registration_document_url',
        'insurance_document_url',
        'registration_expiry',
        'insurance_expiry',
        'inspection_certificate_url',
        'inspection_expiry',
        'is_verified',
        'is_active',
        'total_trips',
        'average_rating',
        'metadata',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'registration_expiry' => 'datetime',
        'insurance_expiry' => 'datetime',
        'inspection_expiry' => 'datetime',
        'metadata' => 'json',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function rides(): HasMany
    {
        return $this->hasMany(Ride::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function isDocumentsExpired(): bool
    {
        return now()->isAfter($this->registration_expiry) || now()->isAfter($this->insurance_expiry);
    }
}
