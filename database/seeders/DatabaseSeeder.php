<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Hotel;
use App\Models\Transport;
use App\Models\User;
use Database\Seeders\AgencySeeder;
use Database\Seeders\ExperienceSeeder;
use Database\Seeders\HomeContentSeeder;
use Database\Seeders\HotelSeeder;
use Database\Seeders\PageHeroSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\StorySeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AgencySeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(HomeContentSeeder::class);
        $this->call(ExperienceSeeder::class);
        $this->call(StorySeeder::class);
        $this->call(PageHeroSeeder::class);

        $roles = [
            'Admin',
            'Service Provider',
            'Customer',
            'Technician',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@travelnest.test'],
            [
                'name' => 'TravelNest Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'is_admin' => true,
            ]
        );

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        $user = User::updateOrCreate(
            ['email' => 'user@travelnest.test'],
            [
                'name' => 'TravelNest User',
                'password' => Hash::make('User@123'),
                'role' => 'customer',
                'is_admin' => false,
            ]
        );

        if (!$user->hasRole('Customer')) {
            $user->assignRole('Customer');
        }

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

        $this->call(HotelSeeder::class);

        if (!Transport::query()->exists()) {
            Transport::factory()->count(8)->create();
        }
    }
}
