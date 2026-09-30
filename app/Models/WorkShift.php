<?php

// Model ca làm việc của nhân viên, gồm giờ bắt đầu, giờ kết thúc và trạng thái hoạt động.
// Có hàm xác định ca hiện tại dựa trên thời gian thực tế.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function staffWorkLogs()
    {
        return $this->hasMany(StaffWorkLog::class, 'shift_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shift_id');
    }

    public function assignedStaff()
    {
        return $this->hasMany(User::class, 'default_shift_id');
    }

    /**
     * Lấy ca làm việc tương ứng với thời điểm hiện tại
     */
    public static function getCurrentShift()
    {
        try {
            $time = now()->format('H:i:s');

            return self::where('is_active', true)->orderBy('start_time')->get()->first(function ($shift) use ($time) {
                if ($shift->start_time < $shift->end_time) {
                    return $time >= $shift->start_time && $time < $shift->end_time;
                }

                return $time >= $shift->start_time || $time < $shift->end_time;
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Tên ca làm việc mặc định theo thời gian hiện tại
     */
    public static function getCurrentShiftName()
    {
        $shift = self::getCurrentShift();

        return $shift ? $shift->name : 'Ngoài giờ làm việc';
    }
}
