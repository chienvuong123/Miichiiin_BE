<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Admin;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chain_owner_role = Role::query()->select('*')->where('name','chain owner')->first();
        $chain_owner = Admin::query()->where('id', "=", 1)->first();
        $chain_owner->assignRole($chain_owner_role);

        $hotel_owner_role = Role::query()->select('*')->where('name','hotel owner')->first();
        $hotel_owner = Admin::query()->where('id', "=", 2)->first();
        $hotel_owner->assignRole($hotel_owner_role);
    }
}
