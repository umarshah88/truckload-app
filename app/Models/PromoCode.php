<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_amount',
        'usage_limit',
        'user_usage_limit',
        'times_used',
        'applicable_for',
        'applicable_cities',
        'applicable_vehicle_types',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'applicable_cities' => 'json',
        'applicable_vehicle_types' => 'json',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function isValid(): bool
    {
        $now = now();
        return $this->is_active
            && $now->isBetween($this->valid_from, $this->valid_until)
            && ($this->usage_limit === null || $this->times_used < $this->usage_limit);
    }
}
