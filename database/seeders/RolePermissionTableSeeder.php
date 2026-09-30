<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $staffRole = \App\Models\Role::where('name', 'staff')->first();

        $permissionMap = [
            'manage_users' => 1,
            'manage_products' => 2,
            'manage_orders' => 3,
            'manage_reviews' => 4,
            'manage_categories' => 5,
            'manage_contacts' => 6,
        ];

        $adminPermissions = [
            $permissionMap['manage_users'],
            $permissionMap['manage_products'],
            $permissionMap['manage_orders'],
            $permissionMap['manage_reviews'],
            $permissionMap['manage_categories'],
        ];

        $staffPermissions = [
            $permissionMap['manage_products'],
            $permissionMap['manage_categories'],
        ];

        if ($adminRole) {
            $adminRole->permissions()->sync($adminPermissions);
        }

        if ($staffRole) {
            $staffRole->permissions()->sync($staffPermissions);
        }
    }
}
