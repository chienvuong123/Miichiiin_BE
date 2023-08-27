<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'id_role' => fake()-> numberBetween(0,2),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
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
