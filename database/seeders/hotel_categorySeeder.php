<?php

namespace Database\Seeders;

use App\Models\hotel_category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class hotel_categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        hotel_category::factory(20)->create();
    }
}
