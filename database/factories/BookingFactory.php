<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_reference' => 'BK-' . strtoupper(Str::random(6)),
            'package_name' => fake()->words(3, true),
            'travel_date' => fake()->dateTimeBetween('+7 days', '+4 months')->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 120, 850),
            'discount_amount' => fake()->randomFloat(2, 0, 50),
            'currency' => 'BDT',
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
