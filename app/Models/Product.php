<?php

// Model sản phẩm võ thuật: tên, giá, tồn kho, danh mục, màu và kích thước.
// Đây là model trung tâm của chức năng bán hàng.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'price',
        'stock',
        'initial_stock',
        'status',
        'unit',
        'colors',
        'sizes',
        'size_stocks'
    ];

    protected $casts = [
        'size_stocks' => 'array',
        'initial_stock' => 'integer',
        'stock' => 'integer',
    ];

    public function stockForSize(?string $size = null): int
    {
        if ($size && is_array($this->size_stocks) && array_key_exists($size, $this->size_stocks)) {
            return (int) $this->size_stocks[$size];
        }

        return (int) $this->stock;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->oldestOfMany();
    }
}
