<?php

// Model sản phẩm yêu thích của người dùng.
// Mỗi bản ghi nối một người dùng với một sản phẩm.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wishlist extends Model
{
    use HasFactory;
    
   protected $fillable = [
        'user_id',
        'product_id'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
