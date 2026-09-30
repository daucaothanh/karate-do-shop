<?php

// Model lịch sử thay đổi trạng thái đơn hàng.
// Dùng để biết ai, ca nào và ghi chú gì khi cập nhật đơn.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_history';
    
    protected $fillable = [
        'order_id',
        'user_id',
        'shift_name',
        'status',
        'changed_at',
        'note'
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
