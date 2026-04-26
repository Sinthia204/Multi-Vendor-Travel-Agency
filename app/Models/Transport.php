<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'provider',
        'price_per_trip',
        'capacity',
        'status',
        'image_url',
        'description',
    ];

    protected $casts = [
        'price_per_trip' => 'decimal:2',
        'capacity' => 'integer',
    ];
}
