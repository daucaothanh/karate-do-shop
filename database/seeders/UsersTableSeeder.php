<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'DAU CAO THANH',
                'email' => 'daucaothanh@gmail.com',
                'password' => bcrypt('123456'),
                'phone_number' => '0123456789',
                'status' => 'pending',
                'avatar' => '',
                'address' => 'Nghệ An, Việt Nam',
                'role_id' => 1,
            ],
            [
                'name' => 'DAU CAO CU',
                'email' => 'daucaocu@gmail.com',
                'password' => bcrypt('123456'),
                'phone_number' => '0123456789',
                'status' => 'pending',
                'avatar' => '',
                'address' => 'Đà Nẵng, Việt Nam',
                'role_id' => 2,
            ],
            [
                'name' => 'DAU CAO THEN',
                'email' => 'daucaothen@gmail.com',
                'password' => bcrypt('123456'),
                'phone_number' => '0123456789',
                'status' => 'pending',
                'avatar' => '',
                'address' => 'Hà Nội, Việt Nam',
                'role_id' => 3,
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::firstOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
