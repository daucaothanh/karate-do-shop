<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\WorkShift;

class StaffShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Đảm bảo 3 ca làm việc mặc định tồn tại
        $sang = WorkShift::where('name', 'like', '%Sáng%')->first();
        $chieu = WorkShift::where('name', 'like', '%Chiều%')->first();
        $toi = WorkShift::where('name', 'like', '%Tối%')->first();

        $staffAccounts = [
            [
                'name' => 'Nguyễn Văn Sáng (NV Ca Sáng)',
                'email' => 'nhanviensang@karatedo.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0967111001',
                'status' => 'active',
                'address' => 'Hải Châu, Đà Nẵng',
                'role_id' => 2, // staff
                'default_shift_id' => $sang ? $sang->id : 1,
            ],
            [
                'name' => 'Trần Thị Chiều (NV Ca Chiều)',
                'email' => 'nhanvienchieu@karatedo.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0967222002',
                'status' => 'active',
                'address' => 'Ngũ Hành Sơn, Đà Nẵng',
                'role_id' => 2, // staff
                'default_shift_id' => $chieu ? $chieu->id : 2,
            ],
            [
                'name' => 'Lê Văn Tối (NV Ca Tối)',
                'email' => 'nhanvientoi@karatedo.com',
                'password' => Hash::make('123456'),
                'phone_number' => '0967333003',
                'status' => 'active',
                'address' => 'Sơn Trà, Đà Nẵng',
                'role_id' => 2, // staff
                'default_shift_id' => $toi ? $toi->id : 3,
            ],
        ];

        foreach ($staffAccounts as $acc) {
            User::updateOrCreate(
                ['email' => $acc['email']],
                $acc
            );
        }

        // Cập nhật tài khoản staff cũ (nếu có) vào Ca Sáng
        $existingStaff = User::where('email', 'staff@gmail.com')->first();
        if ($existingStaff && !$existingStaff->default_shift_id) {
            $existingStaff->update(['default_shift_id' => $sang ? $sang->id : 1]);
        }
    }
}
