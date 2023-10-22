<?php

namespace Database\Seeders;

use App\Models\room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       room::factory()->count(10)->create();
        // $rooms = [
        //     [
        //         "name" => "P101",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 1
        //     ],
        //     [
        //         "name" => "P102",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 1
        //     ],
        //     [
        //         "name" => "P201",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 2
        //     ],
        //     [
        //         "name" => "P202",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 2
        //     ],
        //     [
        //         "name" => "P301",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 3
        //     ],
        //     [
        //         "name" => "P302",
        //         "id_hotel" => 1,
        //         "status" => 1,
        //         "id_cate" => 3
        //     ],
        //     [
        //         "name" => "P101",
        //         "id_hotel" => 2,
        //         "status" => 1,
        //         "id_cate" => 3
        //     ],
        //     [
        //         "name" => "P102",
        //         "id_hotel" => 2,
        //         "status" => 1,
        //         "id_cate" => 3
        //     ],
        //     [
        //         "name" => "P201",
        //         "id_hotel" => 2,
        //         "status" => 1,
        //         "id_cate" => 4
        //     ],
        //     [
        //         "name" => "P202",
        //         "id_hotel" => 2,
        //         "status" => 1,
        //         "id_cate" => 4
        //     ],
        //     [
        //         "name" => "P101",
        //         "id_hotel" => 3,
        //         "status" => 1,
        //         "id_cate" => 5
        //     ],
        //     [
        //         "name" => "P102",
        //         "id_hotel" => 3,
        //         "status" => 1,
        //         "id_cate" => 5
        //     ],
        //     [
        //         "name" => "P201",
        //         "id_hotel" => 3,
        //         "status" => 1,
        //         "id_cate" => 6
        //     ],
        //     [
        //         "name" => "P202",
        //         "id_hotel" => 3,
        //         "status" => 1,
        //         "id_cate" => 6
        //     ],
        // ];

        // room::query()->insert($rooms);
    }
}
