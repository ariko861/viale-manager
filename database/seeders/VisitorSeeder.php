<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $visitors = collect([
            [
                'name' => 'Doe',
                'surname' => 'John',
                'email' => 'john.doe@mail.com',
                'phone' => '0234237133',
                'birthyear' => '1986',

            ],
            [
                'name' => 'Gérand',
                'surname' => 'Alice',
                'email' => 'alicegérand@gog.le',
                'phone' => '0123456789',
                'birthyear' =>'1967',
            ],
            [
                'name' => 'Balas',
                'surname' => 'Tom',
                'email' => 'tomb@me.le',
                'phone' => '0324214332',
                'birthyear' =>'1987',
            ],
            [
                'name' => 'Bertrand',
                'surname' => 'Élie',
                'email' => 'elien@ert.it',
                'phone' => '03242342222',
                'birthyear' =>'1976',
            ],
        ]);

        foreach ($visitors as $visitor) {
             Visitor::create(['name' => $visitor["name"], 'surname' => $visitor["surname"], 'email' => $visitor["email"], 'phone' => $visitor["phone"], 'birthyear' => $visitor["birthyear"] ]);
        }
    }
}
