<?php

namespace Database\Seeders;

use App\Models\city;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                "name" => "Hà Nội"
            ],
            [
                "name" => "Hồ Chí Minh"
            ],
            [
                "name" => "Hải Phòng"
            ],
            [
                "name" => "Đà Nẵng"
            ],
            [
                "name" => "Hà Giang"
            ],
            [
                "name" => "Cao Bằng"
            ],
            [
                "name" => "Lai Châu"
            ],
            [
                "name" => "Lào cai"
            ],
            [
                "name" => "Tuyên Qunag"
            ],
            [
                "name" => "Lạng sơn"
            ],
            [
                "name" => "Bắc Kạn"
            ],
            [
                "name" => "Thái Nguyên"
            ],
            [
                "name" => "Yên bái"
            ],
            [
                "name" => "Sơn La"
            ],
            [
                "name" => "Phú thọ"
            ],
            [
                "name" => "Vĩnh phúc"
            ],
            [
                "name" => "Quảng Ninh"
            ],
            [
                "name" => "Bắc Giang"
            ],
            [
                "name" => "Bắc Ninh"
            ],
            [
                "name" => "Hải Dương"
            ],
            [
                "name" => "Hưng Yên"
            ],
            [
                "name" => "Hòa Bình"
            ],
            [
                "name" => "Hà Nam"
            ],
            [
                "name" => "Nam Định"
            ],
            [
                "name" => "Thái Bình"
            ],
            [
                "name" => "Ninh Bình"
            ],
            [
                "name" => "Thanh Hóa"
            ],
            [
                "name" => "Nghệ An"
            ],
            [
                "name" => "Hà Tĩnh"
            ],
            [
                "name" => "Quảng Bình"
            ],
            [
                "name" => "Quảng Trị"
            ],
            [
                "name" => "Thừa thiên - Huế"
            ],
            [
                "name" => "Quảng Nam"
            ],
            [
                "name" => "Quảng ngãi"
            ],
            [
                "name" => "Kon Tum"
            ],
            [
                "name" => "Bình Định"
            ],
            [
                "name" => "Gia Lai"
            ],
            [
                "name" => "Phú Yên"
            ],
            [
                "name" => "Đắk Lắk"
            ],
            [
                "name" => "Khánh Hòa"
            ],
            [
                "name" => "Lâm Đồng"
            ],
            [
                "name" => "Bình Phước"
            ],
            [
                "name" => "Bình Dương"
            ],
            [
                "name" => "Ninh Thuận"
            ],
            [
                "name" => "Tây Ninh"
            ],
            [
                "name" => "Bình Thuận"
            ],
            [
                "name" => "Đồng Nai"
            ],
            [
                "name" => "Long An"
            ],
            [
                "name" => "Đồng Tháp"
            ],
            [
                "name" => "An Giang"
            ],
            [
                "name" => "Bà Rịa - Vũng Tàu"
            ],
            [
                "name" => "Tiền Giang"
            ],
            [
                "name" => "Kiên Giang"
            ],
            [
                "name" => "Cần Thơ"
            ],
            [
                "name" => "Bến Tre"
            ],
            [
                "name" => "Vĩnh Long"
            ],
            [
                "name" => "Trà Vinh"
            ],
            [
                "name" => "Sóc Trăng"
            ],
            [
                "name" => "Bạc Liêu"
            ],
            [
                "name" => "Cà Mau"
            ],
            [
                "name" => "Điện Biên"
            ],
            [
                "name" => "Đắk Nông"
            ],
            [
                "name" => "Hậu Giang"
            ],
        ];
        city::query()->insert($cities);
    }
}
