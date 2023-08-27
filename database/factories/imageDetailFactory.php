<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\imageDetail>
 */
class imageDetailFactory extends Factory
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
            "id_hotel" => fake()->numberBetween(),
            "id_rooms" => fake()->numberBetween(),
            "id_cate" => fake()->numberBetween(),
            "id_image" => fake()->numberBetween(),
            "id_services" => fake()->numberBetween(),
        ];
    }
}
