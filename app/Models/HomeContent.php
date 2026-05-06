<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_subtitle',
        'hero_image_url',
        'hero_cta_text',
        'hero_cta_url',
        'destinations_badge',
        'destinations_title',
        'destinations_subtitle',
        'packages_badge',
        'packages_title',
        'packages_subtitle',
        'experiences_badge',
        'experiences_title',
        'experiences_subtitle',
        'stories_badge',
        'stories_title',
        'stories_subtitle',
    ];
}
