<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Hotel',
            'city' => fake()->city(),
            'country' => fake()->country(),
            'address' => fake()->address(),
            'price_per_night' => fake()->randomFloat(2, 80, 520),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'status' => fake()->randomElement(['active', 'draft', 'inactive']),
            'image_url' => fake()->imageUrl(640, 480, 'travel', true),
            'description' => fake()->paragraph(),
        ];
    }
}
