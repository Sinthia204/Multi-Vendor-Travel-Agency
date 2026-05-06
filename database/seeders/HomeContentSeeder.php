<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        HomeContent::query()->updateOrCreate(
            ['id' => 1],
            [
                'hero_badge' => 'Designed for explorers',
                'hero_title' => 'Where your next <span>extraordinary</span> trip begins.',
                'hero_subtitle' => 'Browse handpicked escapes, build flexible itineraries, and book stays that feel like they were made for you.',
                'hero_image_url' => 'images/hero_bali_1775112308644.png',
                'hero_cta_text' => 'Explore packages',
                'hero_cta_url' => '/packages',
                'destinations_badge' => 'Featured escapes',
                'destinations_title' => 'Destinations our travelers love',
                'destinations_subtitle' => 'Explore destinations chosen for their culture, scenery, and hospitality.',
                'packages_badge' => 'Signature packages',
                'packages_title' => 'Trips designed for effortless planning',
                'packages_subtitle' => 'Pick a curated itinerary or let us personalize every detail.',
                'experiences_badge' => 'Curated experiences',
                'experiences_title' => 'Travel moments designed around you',
                'experiences_subtitle' => 'Choose immersive cultural walks, private wellness resets, or high-energy adventures.',
                'stories_badge' => 'Traveler stories',
                'stories_title' => 'Journeys that feel personal',
                'stories_subtitle' => 'Real guest stories that inspire your next trip.',
            ]
        );
    }
}
