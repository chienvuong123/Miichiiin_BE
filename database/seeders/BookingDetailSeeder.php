<?php

namespace Database\Seeders;

use App\Models\bookingDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        bookingDetail::factory()->count(10)->create();

    }
}
