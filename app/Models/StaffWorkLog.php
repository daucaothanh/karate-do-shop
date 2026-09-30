<?php

// Model nhật ký làm việc của nhân viên: đăng nhập, chấm công, ca trực và doanh số.
// Có thêm hàm tính thời lượng ca và cộng dồn đơn hàng đã xử lý.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StaffWorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
        'shift_name',
        'login_at',
        'check_in_at',
        'check_out_at',
        'total_minutes',
        'orders_handled_count',
        'total_revenue_handled',
        'ip_address',
        'note',
        'status',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'total_revenue_handled' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(WorkShift::class, 'shift_id');
    }

    /**
     * Định dạng thời gian làm việc (Ví dụ: 3 giờ 45 phút)
     */
    public function getDurationFormattedAttribute()
    {
        $minutes = $this->total_minutes;

        if ($this->status === 'active' && $this->check_in_at) {
            $minutes = $this->check_in_at->diffInMinutes(now());
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return "{$hours} giờ {$remainingMinutes} phút";
        }

        return "{$remainingMinutes} phút";
    }

    /**
     * Cộng dồn đơn hàng và doanh số đã xử lý trong ca
     */
    public function recordOrderHandled($orderAmount = 0)
    {
        $this->increment('orders_handled_count', 1);
        if ($orderAmount > 0) {
            $this->increment('total_revenue_handled', $orderAmount);
        }
    }
}
