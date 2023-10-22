<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Admin::factory(1)->create();
        $admin = [
            [
                "name" => "admin",
                "id_hotel" => null,
                "email" => "superadmin@michimail.com",
                "password" => "admin",
                "image" => "https://via.placeholder.com/640x480.png/00bb55?text=ad",
                "description" => "Tài khoản của chủ chuỗi",
                "phone" => "0396007890",
                "address" => "Thanh Oai HN",
                "status" => 1,
                "gender" => 0,
                "date" => "2003-11-09",
                "created_at" => now()
            ],
            [
                "name" => "hotel owner",
                "id_hotel" => 1,
                "email" => "hotel@michimail.com",
                "password" => "admin",
                "image" => "https://via.placeholder.com/640x480.png/00bb55?text=ad",
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007899",
                "address" => "Thanh Oai HN",
                "status" => 1,
                "gender" => 0,
                "date" => "2003-11-08",
                "created_at" => now()
            ]
        ];

        Admin::query()->insert($admin);
    }
}
