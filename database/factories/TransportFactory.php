<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transport>
 */
class TransportFactory extends Factory
{
    public function definition(): array
    {
        $types = ['Bus', 'Van', 'Car', 'Boat', 'Flight'];

        return [
            'name' => fake()->company() . ' ' . fake()->randomElement($types),
            'type' => fake()->randomElement($types),
            'provider' => fake()->company(),
            'price_per_trip' => fake()->randomFloat(2, 15, 250),
            'capacity' => fake()->numberBetween(4, 50),
            'status' => fake()->randomElement(['active', 'draft', 'inactive']),
            'image_url' => fake()->imageUrl(640, 480, 'transport', true),
            'description' => fake()->sentence(),
        ];
    }
}
