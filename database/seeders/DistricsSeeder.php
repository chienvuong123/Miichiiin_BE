<?php

namespace Database\Seeders;

use App\Models\distric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        distric::factory()->count(10)->create();

    }
}
