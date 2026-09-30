<?php

// Model địa chỉ nhận hàng của người dùng.
// Một người dùng có thể lưu nhiều địa chỉ giao hàng.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;
    
   protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'address',
        'city',
        'default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
