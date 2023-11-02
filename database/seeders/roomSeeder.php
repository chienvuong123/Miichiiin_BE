<?php

namespace Database\Seeders;

use App\Models\room;
use Illuminate\Database\Seeder;

class roomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 26 ; $i++) {
            $rooms = [
                [
                    "name" => "P101",
                    "status" => 2,
                    "id_hotel_cate" => $i
                ],
                [
                    "name" => "P102",
                    "status" => 2,
                    "id_hotel_cate" => $i
                ],
                [
                    "name" => "P201",
                    "status" => 2,
                    "id_hotel_cate" => $i
                ],
                [
                    "name" => "P202",
                    "status" => 2,
                    "id_hotel_cate" => $i
                ],
                [
                    "name" => "P301",
                    "status" => 2,
                    "id_hotel_cate" => $i
                ],
            ];
            room::query()->insert($rooms);
        }
    }
}
