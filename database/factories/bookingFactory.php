<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\booking>
 */
class bookingFactory extends Factory
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
            "check_in" => fake()->dateTime(),
            "check_out" => fake()->dateTime(),
            "people_quantity" => fake()->numberBetween(),
            "total_amount" => fake()->numberBetween(),
            "phone" => fake()->phoneNumber(),
            "cccd" => fake()->text(),
            "nationality" => fake()->address(),
            "status" => fake()->numberBetween(1),
            "message" => fake()->text(),
            "email" => fake()->email(),
            "id_user" => fake()->numberBetween(1,10),
        ];
    }
}
