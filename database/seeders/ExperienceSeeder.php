<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'title' => 'Market-to-table tasting journeys',
                'icon' => 'fa-solid fa-utensils',
                'description' => 'Meet chefs, explore hidden street markets, and savor dishes crafted just for TravelNest guests.',
                'sort_order' => 1,
            ],
            [
                'title' => 'Signature treks and scenic escapes',
                'icon' => 'fa-solid fa-person-hiking',
                'description' => 'From sunrise hikes to coastal bike tours, find the right mix of movement and calm.',
                'sort_order' => 2,
            ],
            [
                'title' => 'Restorative stays with private guides',
                'icon' => 'fa-solid fa-spa',
                'description' => 'Balance your itinerary with spa rituals, yoga retreats, and serene hotel sanctuaries.',
                'sort_order' => 3,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::query()->updateOrCreate(
                ['title' => $experience['title']],
                [
                    'icon' => $experience['icon'],
                    'description' => $experience['description'],
                    'is_active' => true,
                    'sort_order' => $experience['sort_order'],
                ]
            );
        }
    }
}
