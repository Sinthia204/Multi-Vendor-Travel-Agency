<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Hotel;
use App\Models\Transport;
use App\Models\User;
use Database\Seeders\AgencySeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AgencySeeder::class);
        $this->call(PackageSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@travelnest.test'],
            [
                'name' => 'TravelNest Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_admin' => true,
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@travelnest.test'],
            [
                'name' => 'TravelNest User',
                'password' => Hash::make('User@123'),
                'role' => 'user',
                'is_admin' => false,
            ]
        );

        if (!Booking::query()->exists()) {
            Booking::factory()
                ->count(5)
                ->for($user)
                ->create();
        }

        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10,
                'active' => true,
                'expires_at' => now()->addMonths(3),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'BDT200'],
            [
                'type' => 'fixed',
                'value' => 200,
                'active' => true,
                'expires_at' => now()->addMonths(6),
            ]
        );

        if (!Hotel::query()->exists()) {
            Hotel::factory()->count(8)->create();
        }

        if (!Transport::query()->exists()) {
            Transport::factory()->count(8)->create();
        }
    }
}
