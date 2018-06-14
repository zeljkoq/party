<?php

use Illuminate\Database\Seeder;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleTableSeeder
 */
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin',
        ]);
        Role::create([
            'name' => 'DJ',
        ]);
        Role::create([
            'name' => 'Party Maker',
        ]);
        Role::create([
            'name' => 'Normal User',
        ]);

        DB::table('user_role')->insert([
            'user_id' => 2,
            'role_id' => 1
        ]);
    }
}
