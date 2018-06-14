<?php

use Illuminate\Database\Seeder;

use App\Models\User;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'quantoxBend@mail.com',
            'name' => 'Quantox Band',
            'password' => bcrypt('kompjuter')
        ]);
        User::create([
            'email' => 'kuzmic@mail.com',
            'name' => 'Kuzmic',
            'password' => bcrypt('kompjuter')
        ]);
        User::create([
            'email' => 'stefan@mail.com',
            'name' => 'Stefan',
            'password' => bcrypt('kompjuter')
        ]);
    }
}
