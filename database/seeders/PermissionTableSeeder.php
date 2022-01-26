<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [
           'role-manage',
           'user-manage',
           'config-manage',
           'reservation-create',
           'reservation-edit',
           'reservation-list',
           'reservation-delete',
           'visitor-list',
           'visitor-create',
           'visitor-edit',
           'visitor-delete',
        ];



        foreach ($permissions as $permission) {
             Permission::create(['guard_name' => 'sanctum', 'name' => $permission]);
        }
    }
}
