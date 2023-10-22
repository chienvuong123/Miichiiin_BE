<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::query()->select('*')->where('name','chain owner')->first();
        $permissions = Permission::query()->select('*')->get();
        $role->syncPermissions($permissions);

        $role = Role::query()->select('*')->where('name','hotel owner')->first();
        $permissions = Permission::query()
            ->select('*')
            ->whereNotIn('id', [2,3,4,9,11,13])
            ->get();
        $role->syncPermissions($permissions);
    }
}
