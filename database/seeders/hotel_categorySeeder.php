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
//        hotel_category::factory(20)->create();
        for ($i = 1; $i <= 5 ; $i++) {
            $hotel_cate = [
                [
                    "id_cate" => 1,
                    "id_hotel" => $i
                ],
                [
                    "id_cate" => 2,
                    "id_hotel" => $i
                ],
                [
                    "id_cate" => 3,
                    "id_hotel" => $i
                ],
                [
                    "id_cate" => 4,
                    "id_hotel" => $i
                ],
                [
                    "id_cate" => 5,
                    "id_hotel" => $i
                ],

            ];
            hotel_category::query()->insert($hotel_cate);
        }
    }
}
