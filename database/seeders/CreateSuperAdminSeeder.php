<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        if ( User::role('Super Admin')->get()->count() === 0 ) // check if no Super Admin yet
        {
            $user = \App\Models\User::factory()->create([
                'name' => env('APP_SUPER_ADMIN'),
                'email' => env('APP_SUPER_ADMIN_MAIL'),
                'password' => Hash::make( env('APP_SUPER_ADMIN_PASSWORD') ),
            ]);
            $user->assignRole($role);

        }

    }
}
