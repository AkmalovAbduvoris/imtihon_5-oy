<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venue>
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'location' => $this->faker->address(),
            'capacity' => $this->faker->numberBetween(50, 1000),
            'price' => $this->faker->randomFloat(2, 100, 5000),
        ];
    }
}
