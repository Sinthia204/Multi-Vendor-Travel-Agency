<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'badge',
        'title',
        'subtitle',
        'background_image_url',
        'cta_text',
        'cta_url',
    ];
}
