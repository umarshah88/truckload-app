<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rater_id',
        'ratee_id',
        'ride_id',
        'delivery_id',
        'rating_for',
        'stars',
        'comment',
        'categories',
    ];

    protected $casts = [
        'categories' => 'json',
    ];

    public function rater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    public function ratee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ratee_id');
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
