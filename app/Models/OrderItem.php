<?php

// Model chi tiết từng sản phẩm bên trong một đơn hàng.
// Lưu số lượng, giá bán, màu và kích thước tại thời điểm đặt hàng.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'color',
        'size'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
