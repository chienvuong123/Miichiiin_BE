<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'admin',
            'id_hotel' => fake()->numberBetween(0,9),
            'email' => 'admin@michimail.com',
            'password' => Hash::make("admin"), // password
            'remember_token' => Str::random(10),
            'image' => fake()->imageUrl,
            'description' => fake()->text(20),
            'phone' => fake()->phoneNumber,
            'address' => fake()->address,
            'status' => fake()->numberBetween(0,1),
            'gender' => fake()->numberBetween(0,2),
            'date' => fake()->date
        ];
    }
}
