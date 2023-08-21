<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\room>
 */
class roomFactory extends Factory
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
            "short_description" => fake()->text(),
            "price" => fake()->numberBetween(),
            "acreage" => fake()->numberBetween(),
            "status" => fake()->numberBetween(0,1),
            "id_hotel" => fake()->numberBetween(),
            "id_floor" => fake()->numberBetween(),
            "likes" => fake()->numberBetween(),
            "views" => fake()->numberBetween(),
            "id_cate" => fake()->numberBetween(),
            "quantity_of_people" => fake()->numberBetween(),
        ];
    }
}
