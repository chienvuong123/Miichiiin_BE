<?php

namespace Database\Seeders;

use App\Models\floor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class floorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        floor::factory()->count(10)->create();

    }
}
