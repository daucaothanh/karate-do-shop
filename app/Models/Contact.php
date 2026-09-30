<?php

// Model thông tin liên hệ/tư vấn do khách hàng gửi cho cửa hàng.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    
    protected $fillable = ['full_name', 'phone_number', 'email', 'message', 'is_replied'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
