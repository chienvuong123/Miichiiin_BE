<?php

namespace Database\Seeders;

use App\Models\WalletVoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $data = [
                "id_wallet" => fake()->numberBetween(1,20),
                "id_voucher" => fake()->numberBetween(1,10)
            ];
            WalletVoucher::query()->create($data);
        }
    }
}
