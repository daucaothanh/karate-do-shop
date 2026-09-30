<?php

// Model hình ảnh sản phẩm, mỗi sản phẩm có thể có nhiều hình ảnh.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
