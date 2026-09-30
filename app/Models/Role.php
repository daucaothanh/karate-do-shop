<?php

// Model vai trò tài khoản như quản trị viên, nhân viên hoặc khách hàng.
// Vai trò liên kết với các quyền thông qua bảng role_permissions.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
