<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'cnic',
        'cnic_front_url',
        'cnic_back_url',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'emergency_contact',
        'emergency_contact_number',
        'bio',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
