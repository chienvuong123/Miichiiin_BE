<?php

namespace Database\Seeders;

use App\Models\booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        booking::factory()->count(10)->create();

    }
}
