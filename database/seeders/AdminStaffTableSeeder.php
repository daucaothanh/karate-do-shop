<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminStaffTableSeeder extends Seeder
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
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0967137200',
                'status' => 'active',
                'avatar' => '',
                'address' => 'Nghệ An, Việt Nam',
                'role_id' => 1,
            ],
            [
                'name' => 'staff',
                'email' => 'staff@gmail.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0966575753',
                'status' => 'active',
                'avatar' => '',
                'address' => 'Đà Nẵng, Việt Nam',
                'role_id' => 2,
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
