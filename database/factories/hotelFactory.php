<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\hotel>
 */
class hotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "name" => fake()->name(),
            "description" => fake()->text(),
            "star" => fake()->numberBetween(0,5),
            "status" => fake()->numberBetween(0,1),
            "email" => fake()->email(),
            "phone" => fake()->phoneNumber(),
            "id_city" => fake()->numberBetween(),
            "quantity_floor" => fake()->numberBetween(),
            "quantity_of_room" => fake()->numberBetween(),
        ];
    }
}
