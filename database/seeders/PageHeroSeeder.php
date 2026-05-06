<?php

namespace Database\Seeders;

use App\Models\PageHero;
use Illuminate\Database\Seeder;

class PageHeroSeeder extends Seeder
{
    public function run(): void
    {
        $heroes = [
            'destinations' => [
                'badge' => 'Destinations',
                'title' => 'Discover places that match your pace.',
                'subtitle' => 'From island hideaways to alpine wellness stays, TravelNest curates destinations that feel tailored to your travel style.',
                'background_image_url' => 'images/dest_maldives_1775112608148.png',
            ],
            'packages' => [
                'badge' => 'Packages',
                'title' => 'Curated itineraries, zero planning stress.',
                'subtitle' => 'Choose a ready-to-book package or let TravelNest personalize every detail for your travelers and budget.',
                'background_image_url' => 'images/dest_swiss_1775112801276.png',
            ],
            'experiences' => [
                'badge' => 'Experiences',
                'title' => 'Moments curated by local insiders.',
                'subtitle' => 'Choose immersive activities, luxury touches, and cultural discoveries created exclusively for TravelNest guests.',
                'background_image_url' => 'images/dest_tokyo_1775112740002.png',
            ],
            'stories' => [
                'badge' => 'Stories',
                'title' => 'Real journeys, memorable details.',
                'subtitle' => 'TravelNest guests share the moments, people, and surprises that made their trips unforgettable.',
                'background_image_url' => 'images/dest_santorini_1775112332350.png',
            ],
            'contact' => [
                'badge' => 'Contact',
                'title' => 'Let us design your next journey.',
                'subtitle' => 'Share your travel dreams and our concierge team will craft a custom itinerary within 24 hours.',
                'background_image_url' => 'images/dest_machu_picchu_1775112348652.png',
            ],
        ];

        foreach ($heroes as $slug => $data) {
            PageHero::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'badge' => $data['badge'],
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'],
                    'background_image_url' => $data['background_image_url'],
                ]
            );
        }
    }
}
