<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'country',
        'address',
        'price_per_night',
        'rating',
        'status',
        'is_featured',
        'featured_order',
        'image_url',
        'description',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'featured_order' => 'integer',
    ];
}
