<?php

// Model danh mục sản phẩm, dùng để nhóm các sản phẩm võ thuật.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
