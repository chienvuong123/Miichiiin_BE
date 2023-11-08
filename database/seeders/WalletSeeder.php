<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_wallet = [];
        for ($i = 1; $i <= 20; $i++) {
            $list_wallet[] = [
                "id_user" => $i,
                "created_at" => now(),
            ];
        }
        Wallet::query()->insert($list_wallet);
    }
}
