<?php

// Model thanh toán của đơn hàng: phương thức, số tiền, mã giao dịch và trạng thái.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'payment_proof',
        'amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
