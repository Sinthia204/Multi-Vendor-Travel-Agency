<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencyMap = Agency::query()->pluck('id', 'name');

        $packages = [
            [
                'agency_name' => 'Bengal Tours Ltd',
                'name' => "Cox's Bazar Premium Beach Tour",
                'category' => 'beach',
                'price' => 450,
                'duration' => '5 Days',
                'location' => "Cox's Bazar",
                'capacity' => 50,
                'booked' => 32,
                'status' => 'active',
                'is_featured' => true,
                'featured_order' => 1,
                'image_url' => 'images/dest_santorini_1775112332350.png',
                'gradient' => 'linear-gradient(135deg, #2d8a7a 0%, #3da88f 50%, #d4a030 100%)',
            ],
            [
                'agency_name' => 'Dhaka Explorers',
                'name' => 'Sundarbans Mangrove Explorer',
                'category' => 'adventure',
                'price' => 320,
                'duration' => '3 Days',
                'location' => 'Sundarbans',
                'capacity' => 35,
                'booked' => 21,
                'status' => 'active',
                'is_featured' => true,
                'featured_order' => 2,
                'image_url' => 'images/dest_maldives_1775112608148.png',
                'gradient' => 'linear-gradient(135deg, #1a2e35 0%, #2d8a7a 100%)',
            ],
            [
                'agency_name' => 'Sylhet Adventures',
                'name' => 'Rangamati Hill Retreat',
                'category' => 'mountain',
                'price' => 280,
                'duration' => '4 Days',
                'location' => 'Rangamati',
                'capacity' => 25,
                'booked' => 18,
                'status' => 'active',
                'is_featured' => true,
                'featured_order' => 3,
                'image_url' => 'images/dest_swiss_1775112801276.png',
                'gradient' => 'linear-gradient(135deg, #4b5563 0%, #111827 100%)',
            ],
            [
                'agency_name' => 'Chittagong Travels',
                'name' => 'Bandarban Trekking Adventure',
                'category' => 'adventure',
                'price' => 250,
                'duration' => '3 Days',
                'location' => 'Bandarban',
                'capacity' => 40,
                'booked' => 14,
                'status' => 'active',
                'is_featured' => false,
                'image_url' => 'images/dest_machu_picchu_1775112348652.png',
                'gradient' => 'linear-gradient(135deg, #22c55e 0%, #2d8a7a 100%)',
            ],
            [
                'agency_name' => 'Bengal Tours Ltd',
                'name' => 'Saint Martin Island Escape',
                'category' => 'beach',
                'price' => 390,
                'duration' => '4 Days',
                'location' => 'Saint Martin',
                'capacity' => 30,
                'booked' => 30,
                'status' => 'sold-out',
                'is_featured' => false,
                'image_url' => 'images/dest_tokyo_1775112740002.png',
                'gradient' => 'linear-gradient(135deg, #d4a030 0%, #e8b84a 100%)',
            ],
        ];

        foreach ($packages as $package) {
            $agencyId = $agencyMap[$package['agency_name']] ?? Agency::query()->value('id');
            if (!$agencyId) {
                continue;
            }

            Package::updateOrCreate(
                ['agency_id' => $agencyId, 'name' => $package['name']],
                [
                    'agency_id' => $agencyId,
                    'name' => $package['name'],
                    'category' => $package['category'],
                    'price' => $package['price'],
                    'duration' => $package['duration'],
                    'location' => $package['location'],
                    'capacity' => $package['capacity'],
                    'booked' => $package['booked'],
                    'status' => $package['status'],
                    'is_featured' => $package['is_featured'] ?? false,
                    'featured_order' => $package['featured_order'] ?? null,
                    'image_url' => $package['image_url'],
                    'gradient' => $package['gradient'],
                ]
            );
        }
    }
}
