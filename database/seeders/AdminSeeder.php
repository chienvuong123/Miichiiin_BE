<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                "password" => Hash::make("admin"),
                "image" => "https://via.placeholder.com/640x480.png/00bb55?text=ad",
                "description" => "Tài khoản của chủ chuỗi",
                "phone" => "0396007890",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-09",
            ],
            [
                "name" => "Hà Nội hotel owner",
                "id_hotel" => 1,
                "email" => "hotel.hanoi@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007899",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Đà Nẵng hotel owner",
                "id_hotel" => 2,
                "email" => "hotel.danang@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007898",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Sài Gòn hotel owner",
                "id_hotel" => 3,
                "email" => "hotel.hcm@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007898",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Đà Lạt hotel owner",
                "id_hotel" => 4,
                "email" => "hotel.dalat@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007838",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Hạ Long hotel owner",
                "id_hotel" => 5,
                "email" => "hotel.halong@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007898",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Hà Nội hotel staff",
                "id_hotel" => 1,
                "email" => "staff.hanoi@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007819",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Đà Nẵng hotel staff",
                "id_hotel" => 2,
                "email" => "staff.danang@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007828",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Sài Gòn hotel staff",
                "id_hotel" => 3,
                "email" => "staff.hcm@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007898",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Đà Lạt hotel staff",
                "id_hotel" => 4,
                "email" => "staff.dalat@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007848",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
            [
                "name" => "Hạ Long hotel staff",
                "id_hotel" => 5,
                "email" => "staff.halong@michimail.com",
                "password" => Hash::make("admin"),
                "image" => fake()->imageUrl,
                "description" => "Tài khoản của chủ khách sạn",
                "phone" => "0396007858",
                "address" => "Thanh Oai HN",
                "status" => 2,
                "gender" => 0,
                "date" => "2003-11-08",
            ],
        ];

        Admin::query()->insert($admin);
    }
}
