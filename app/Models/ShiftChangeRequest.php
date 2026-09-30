<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftChangeRequest extends Model
{
    use HasFactory;

    protected $table = 'shift_change_requests';

    protected $fillable = [
        'user_id',
        'work_date',
        'current_shift_id',
        'requested_shift_id',
        'reason',
        'status',
        'reviewed_by',
        'admin_note',
        'reviewed_at',
    ];

    protected $casts = [
        'work_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentShift()
    {
        return $this->belongsTo(WorkShift::class, 'current_shift_id');
    }

    public function requestedShift()
    {
        return $this->belongsTo(WorkShift::class, 'requested_shift_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
