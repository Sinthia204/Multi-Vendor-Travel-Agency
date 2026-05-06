<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $stories = [
            [
                'title' => 'Tokyo after dark, one bite at a time',
                'category' => 'City escapes',
                'read_time' => '5 min read',
                'excerpt' => 'Private tastings, neon alleyways, and a guide who knew every hidden ramen bar.',
                'image_url' => 'images/dest_tokyo_1775112740002.png',
                'sort_order' => 1,
            ],
            [
                'title' => 'Slow mornings above the Aegean',
                'category' => 'Romance',
                'read_time' => '4 min read',
                'excerpt' => 'A honeymoon built around blue-domed sunsets and private sailings.',
                'image_url' => 'images/dest_santorini_1775112332350.png',
                'sort_order' => 2,
            ],
            [
                'title' => 'Alpine trails with a wellness twist',
                'category' => 'Adventure',
                'read_time' => '6 min read',
                'excerpt' => 'Guided hikes by day, alpine spa rituals by night, and postcard-worthy stays.',
                'image_url' => 'images/dest_swiss_1775112801276.png',
                'sort_order' => 3,
            ],
        ];

        foreach ($stories as $story) {
            Story::query()->updateOrCreate(
                ['title' => $story['title']],
                [
                    'category' => $story['category'],
                    'read_time' => $story['read_time'],
                    'excerpt' => $story['excerpt'],
                    'image_url' => $story['image_url'],
                    'is_active' => true,
                    'sort_order' => $story['sort_order'],
                ]
            );
        }
    }
}
