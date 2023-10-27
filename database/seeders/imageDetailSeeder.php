<?php

namespace Database\Seeders;

use App\Models\imageDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class imageDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
//        imageDetail::factory()->count(10)->create();
        $image_index = 1;

        for ($i = 1; $i <= 5; $i++) {
            $hotel_images = [
                "id_hotel" => $i,
                "id_image" => $image_index
            ];
            imageDetail::query()->insert($hotel_images);
            $image_index++;
        }

        for ($i = 1; $i <= 25; $i++) {
            $category_images = [
                "id_cate" => $i,
                "id_image" => $image_index
            ];
            imageDetail::query()->insert($category_images);
            $image_index++;
        }

        for ($i = 1; $i <= 125; $i++) {
            $room_images = [
                "id_rooms" => $i,
                "id_image" => $image_index
            ];
            imageDetail::query()->insert($room_images);
            $image_index++;
        }


    }
}
