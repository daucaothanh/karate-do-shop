<?php

// Model tài khoản người dùng, dùng chung cho khách hàng, nhân viên và quản trị viên.
// Quản lý đăng nhập, trạng thái tài khoản, vai trò và các dữ liệu liên quan.

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable; 

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone_number',
        'avatar',
        'address',
        'role_id',
        'default_shift_id',
        'activation_token',
        'google_id',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

   public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function defaultShift()
    {
        return $this->belongsTo(WorkShift::class, 'default_shift_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->hasMany(wishlist::class);
    }

    //check status 
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function isDeleted()
    {
        return $this->status === 'deleted';
    }

    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isStaff()
    {
        return $this->role && in_array($this->role->name, ['admin', 'staff']);
    }

    public function isCustomer()
    {
        return $this->role && $this->role->name === 'customer';
    }

    public function workLogs()
    {
        return $this->hasMany(StaffWorkLog::class);
    }

    public function shiftChangeRequests()
    {
        return $this->hasMany(ShiftChangeRequest::class);
    }

    public function assignedShiftIdForDate($date = null)
    {
        $date = $date ?: today();
        if (!Schema::hasTable('shift_change_requests')) {
            return $this->default_shift_id;
        }

        $approvedRequest = $this->shiftChangeRequests()
            ->whereDate('work_date', $date)
            ->where('status', 'approved')
            ->latest('reviewed_at')
            ->first();

        return $approvedRequest?->requested_shift_id ?: $this->default_shift_id;
    }

    public function handledOrders()
    {
        return $this->hasMany(Order::class, 'handled_by');
    }

    /**
     * Lấy bản ghi ca làm việc đang trực hiện tại của nhân viên
     */
    public function currentWorkLog()
    {
        return $this->hasOne(StaffWorkLog::class)
            ->where('status', 'active')
            ->latestOfMany();
    }

    public function isWorkingShift()
    {
        return $this->workLogs()->where('status', 'active')->exists();
    }

    /**
     * Kiểm tra xem nhân viên có đang trong ca làm việc được phân công hay không.
     * Admin luôn có toàn quyền (true).
     */
    public function canOperateInCurrentShift()
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->isStaff()) {
            return false;
        }

        $assignedShiftId = $this->assignedShiftIdForDate();
        if (!$assignedShiftId) {
            return false;
        }

        $currentShift = WorkShift::getCurrentShift();
        if (!$currentShift) {
            return false;
        }

        return (int) $assignedShiftId === (int) $currentShift->id;
    }

    /**
     * Kiểm tra quyền duyệt đơn hàng:
     * - Tài khoản Admin: XÓA quyền duyệt đơn (không duyệt đơn).
     * - Tài khoản Nhân viên: GIỮ LẠI quyền duyệt đơn (khi đang trong ca trực phân công).
     */
    public function canApproveOrders()
    {
        if ($this->isAdmin()) {
            return false;
        }

        return $this->isStaff() && $this->canOperateInCurrentShift();
    }

    /**
     * Lấy nhãn trạng thái ca trực của nhân viên
     */
    public function getShiftStatusLabel()
    {
        if ($this->isAdmin()) {
            return 'Quản trị viên (Không duyệt đơn)';
        }

        $currentShift = WorkShift::getCurrentShift();
        $defaultShift = $this->defaultShift;

        if (!$defaultShift) {
            return 'Chưa phân ca';
        }

        if ($currentShift && (int)$this->default_shift_id === (int)$currentShift->id) {
            return 'Đang trong ca trực (Được duyệt đơn)';
        }

        return 'Chế độ chỉ xem (Ngoài ca trực)';
    }
}
