<?php

// Model quyền hạn trong hệ thống quản trị.
// Một quyền có thể được cấp cho nhiều vai trò thông qua bảng role_permissions.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

     public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
