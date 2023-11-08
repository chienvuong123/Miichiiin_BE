<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comfort;
use App\Models\Permission;
use App\Models\PermissionDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminSeeder::class,
            hotelSeeder::class,
            roomSeeder::class,
            cateRoomSeeder::class,
            CitySeeder::class,
            hotel_categorySeeder::class,
//            BookingSeeder::class,
            districtSeeder::class,
//            BookingDetailSeeder::class,
            UserSeeder::class,
            ImageSeeder::class,
            imageDetailSeeder::class,
            ServiceSeeder::class,
            ServiceDetailSeeder::class,
            ComfortSeeder::class,
            comfortDetailSeeder::class,
            RateSeeder::class,
            VoucherSeeder::class,
            BannerSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            AssignRoleSeeder::class,
            AssignPermissionSeeder::class,
            WalletSeeder::class,
        ]);
    }
}
