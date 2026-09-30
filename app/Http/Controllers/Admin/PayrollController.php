<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffWorkLog;
use App\Models\User;
use App\Models\WorkShift;
use App\Services\MonthlyPayroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request, MonthlyPayroll $calculator)
    {
        $request->validate(['month' => 'nullable|date_format:Y-m', 'staff_id' => 'nullable|integer|exists:users,id']);
        $month = $request->input('month') ?: now()->format('Y-m');
        $start = Carbon::createFromFormat('!Y-m', $month);
        $shifts = WorkShift::orderBy('start_time')->get();
        $staffMembers = User::whereHas('role', fn ($q) => $q->where('name', 'staff'))->orderBy('name')->get();
        $selectedStaff = $staffMembers->when($request->filled('staff_id'), fn ($users) => $users->where('id', $request->staff_id));
        $logs = StaffWorkLog::whereIn('user_id', $selectedStaff->pluck('id'))
            ->where(function ($query) use ($start) {
                $query->where(function ($q) use ($start) {
                    $q->where('check_in_at', '>=', $start)->where('check_in_at', '<', $start->copy()->addMonth()->addDay());
                })->orWhere(function ($q) use ($start) {
                    $q->whereNull('check_in_at')->where('login_at', '>=', $start)->where('login_at', '<', $start->copy()->addMonth());
                });
            })
            ->get()->groupBy('user_id');
        $rates = DB::table('staff_shift_rates')->where('month', $start->toDateString())->get()->groupBy('user_id');
        $rows = $selectedStaff->map(function ($staff) use ($logs, $rates, $shifts, $month, $calculator) {
            return ['staff' => $staff, 'report' => $calculator->summarize(
                $logs->get($staff->id, collect()), $shifts, $month,
                $rates->get($staff->id, collect())->mapWithKeys(function ($rate) use ($shifts) {
                    // Preserve previously saved per-shift rates as equivalent hourly rates.
                    $shift = $shifts->firstWhere('id', $rate->shift_id);
                    $start = Carbon::parse($shift->start_time);
                    $end = Carbon::parse($shift->end_time);
                    if ($end->lte($start)) {
                        $end->addDay();
                    }
                    return [$rate->shift_id => $rate->hourly_amount ?? $rate->amount * 60 / $start->diffInMinutes($end)];
                })->all()
            )];
        });

        return view('admin.shifts.payroll', compact('month', 'shifts', 'staffMembers', 'rows'));
    }

    public function updateRates(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|date_format:Y-m',
            'staff_id' => 'required|integer|exists:users,id',
            'rates' => 'required|array',
            'rates.*' => 'nullable|integer|min:0|max:100000000',
        ]);
        $staff = User::findOrFail($data['staff_id']);
        abort_unless($staff->role && $staff->role->name === 'staff', 422);
        $shiftIds = WorkShift::pluck('id')->all();
        foreach (array_keys($data['rates']) as $id) {
            abort_unless(in_array((int) $id, $shiftIds), 422);
        }
        DB::transaction(function () use ($data) {
            foreach ($data['rates'] as $shiftId => $amount) {
                $key = ['user_id' => $data['staff_id'], 'shift_id' => $shiftId, 'month' => $data['month'].'-01'];
                if ($amount === null) {
                    DB::table('staff_shift_rates')->where($key)->delete();
                } else {
                    DB::table('staff_shift_rates')->updateOrInsert($key, ['amount' => 0, 'hourly_amount' => $amount, 'updated_at' => now(), 'created_at' => now()]);
                }
            }
        });

        return redirect()->route('admin.shifts.payroll', ['month' => $data['month'], 'staff_id' => $data['staff_id']])
            ->with('success', 'Đã lưu đơn giá theo giờ của nhân viên cho tháng đã chọn.');
    }
}
