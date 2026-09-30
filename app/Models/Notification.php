<?php

// Model thông báo gửi cho người dùng, có trạng thái đã đọc và liên kết liên quan.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'type', 'message', 'link', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
