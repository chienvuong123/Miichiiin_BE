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
        $hotel_owners = Admin::query()->whereIn('id', [2,3,4,5,6])->get();
        foreach ($hotel_owners as $hotel_owner) {
            $hotel_owner->assignRole($hotel_owner_role);
        }

        $hotel_staff_role = Role::query()->select('*')->where('name','staff')->first();
        $hotel_staffs = Admin::query()->whereIn('id', [7,8,9,10,11])->get();
        foreach ($hotel_staffs as $hotel_staff) {
            $hotel_staff->assignRole($hotel_staff_role);
        }
    }
}
