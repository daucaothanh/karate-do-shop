<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'staff', 'customer'];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(['name' => $role]);
        }
    }
}
