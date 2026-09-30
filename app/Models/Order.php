<?php

// Model đơn hàng: lưu khách hàng, tổng tiền, trạng thái, địa chỉ và nhân viên xử lý.
// Quan hệ với sản phẩm thông qua OrderItem và với Payment để lưu thanh toán.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address_id',
        'handled_by',
        'shift_id',
        'shift_name',
        'confirmed_at'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function handledByStaff()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function shift()
    {
        return $this->belongsTo(WorkShift::class, 'shift_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function orderStatusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
