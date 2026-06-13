<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurgePricingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'zone_name',
        'area_polygon',
        'latitude',
        'longitude',
        'surge_multiplier',
        'effective_from',
        'effective_until',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
