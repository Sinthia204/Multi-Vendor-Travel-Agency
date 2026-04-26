<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agencies = [
            [
                'name' => 'Bengal Tours Ltd',
                'contact_person' => 'Rafiq Ahmed',
                'email' => 'contact@bengaltours.com',
                'phone' => '+880 1711-234567',
                'status' => 'approved',
                'registered_at' => now()->subDays(90),
            ],
            [
                'name' => 'Dhaka Explorers',
                'contact_person' => 'Nusrat Jahan',
                'email' => 'hello@dhakaexplorers.com',
                'phone' => '+880 1812-345678',
                'status' => 'approved',
                'registered_at' => now()->subDays(60),
            ],
            [
                'name' => 'Sylhet Adventures',
                'contact_person' => 'Imran Chowdhury',
                'email' => 'support@sylhetadventures.com',
                'phone' => '+880 1913-456789',
                'status' => 'pending',
                'registered_at' => now()->subDays(30),
            ],
            [
                'name' => 'Chittagong Travels',
                'contact_person' => 'Sharmin Akter',
                'email' => 'team@chittagongtravels.com',
                'phone' => '+880 1614-567890',
                'status' => 'approved',
                'registered_at' => now()->subDays(120),
            ],
            [
                'name' => 'Bay of Bengal Tours',
                'contact_person' => 'Sabbir Hossain',
                'email' => 'info@bayofbengaltours.com',
                'phone' => '+880 1515-678901',
                'status' => 'suspended',
                'registered_at' => now()->subDays(200),
            ],
        ];

        foreach ($agencies as $agency) {
            Agency::updateOrCreate(
                ['email' => $agency['email']],
                $agency
            );
        }
    }
}
