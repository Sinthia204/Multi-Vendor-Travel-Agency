<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'category',
        'price',
        'duration',
        'location',
        'capacity',
        'booked',
        'status',
        'image_url',
        'gradient',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'booked' => 'integer',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
