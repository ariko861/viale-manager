<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsAndSuperUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('db:seed', [
            '--class' => 'CreateSuperAdminSeeder',
            '--force' => true // <--- add this line
        ]);
        Artisan::call('db:seed', [
            '--class' => 'PermissionTableSeeder',
            '--force' => true // <--- add this line
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
