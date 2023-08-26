<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'id_hotel' => fake()->numberBetween(0,5),
            'description' => fake()->text(20),
            'quantity' => fake()->numerify,
            'price' => fake()->numerify,
            'image' => fake()->imageUrl,
            'status' => fake()->numberBetween(0,2),
        ];
    }
}
