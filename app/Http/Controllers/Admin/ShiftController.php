<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShiftChangeRequest;
use App\Models\StaffWorkLog;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Danh sách ca làm việc & Nhật ký chấm công nhân viên
     */
    public function index(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();

        // 1. Lấy ca làm việc đang trực của nhân viên hiện tại
        $currentWorkLog = null;
        if ($user) {
            $currentWorkLog = StaffWorkLog::where('user_id', $user->id)
                ->where('status', 'active')
                ->latest()
                ->first();
        }

        // 2. Danh sách ca làm việc định nghĩa sẵn kèm nhân viên phân công
        $shifts = WorkShift::with('assignedStaff')->get();
        $currentShift = WorkShift::getCurrentShift();
        $todayAssignedShift = $user ? WorkShift::find($user->assignedShiftIdForDate()) : null;

        // 3. Nhật ký làm việc (Work logs)
        $query = StaffWorkLog::with(['user', 'shift'])->latest('login_at');

        // Lọc theo nhân viên
        if ($request->filled('staff_id')) {
            $query->where('user_id', $request->staff_id);
        }

        // Lọc theo ca làm việc
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        // Lọc theo ngày
        if ($request->filled('date')) {
            $query->whereDate('login_at', $request->date);
        }

        $workLogs = $query->paginate(15);
        $workLogs->appends($request->query());

        // Tổng hợp tiền theo đúng khoảng thời gian trực của từng nhân viên.
        $logIds = collect($workLogs->items());
        $paymentStats = collect();
        if ($logIds->isNotEmpty()) {
            $rangeStart = $logIds->map(fn ($log) => $log->check_in_at ?: $log->login_at)->filter()->min();
            $rangeEnd = $logIds->map(fn ($log) => $log->check_out_at ?: now())->filter()->max();
            $staffIds = $logIds->pluck('user_id')->filter()->unique();
            $shiftIds = $logIds->pluck('shift_id')->filter()->unique();

            $orders = Order::with('payment')
                ->whereIn('handled_by', $staffIds)
                ->whereIn('shift_id', $shiftIds)
                ->whereNotNull('confirmed_at')
                ->where('status', '!=', 'cancelled')
                ->whereBetween('confirmed_at', [$rangeStart, $rangeEnd])
                ->get();

            foreach ($logIds as $log) {
                $start = $log->check_in_at ?: $log->login_at;
                $end = $log->check_out_at ?: now();
                $logOrders = $orders->filter(function ($order) use ($log, $start, $end) {
                    return (int) $order->handled_by === (int) $log->user_id
                        && (int) $order->shift_id === (int) $log->shift_id
                        && !$order->confirmed_at->lt($start)
                        && !$order->confirmed_at->gt($end);
                });

                $paymentStats->put($log->id, [
                    'cash' => $logOrders->filter(function ($order) {
                        $method = strtolower($order->payment?->payment_method ?? '');
                        return str_contains($method, 'cod') || str_contains($method, 'cash');
                    })
                        ->sum(fn ($order) => $order->payment?->amount ?? $order->total_price),
                    'banking' => $logOrders->filter(function ($order) {
                        $method = strtolower($order->payment?->payment_method ?? '');
                        return str_contains($method, 'chuyển khoản')
                            || str_contains($method, 'banking')
                            || str_contains($method, 'paypal');
                    })
                        ->sum(fn ($order) => $order->payment?->amount ?? $order->total_price),
                ]);
            }
        }

        // 4. Danh sách nhân viên (để filter)
        $staffMembers = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'staff']);
        })->get();

        $changeRequests = ShiftChangeRequest::with(['user', 'currentShift', 'requestedShift', 'reviewer'])
            ->when($user && !$user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->latest('work_date')
            ->latest()
            ->get();

        // 5. Thống kê năng suất xử lý đơn theo ca hôm nay
        $todayShiftsStats = Order::select(
                'shift_name',
                DB::raw('count(*) as total_orders'),
                DB::raw('sum(total_price) as total_revenue')
            )
            ->whereDate('created_at', today())
            ->groupBy('shift_name')
            ->get();

        return view('admin.shifts.danh_sach', compact(
            'currentWorkLog',
            'shifts',
            'currentShift',
            'todayAssignedShift',
            'workLogs',
            'staffMembers',
            'todayShiftsStats',
            'paymentStats',
            'changeRequests'
        ));
    }

    /**
     * Nhân viên gửi yêu cầu đổi ca cho một ngày trong tương lai.
     */
    public function submitChangeRequest(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();
        abort_unless($user && $user->isStaff() && !$user->isAdmin(), 403);

        $validated = $request->validate([
            'work_date' => ['required', 'date', 'after:today'],
            'requested_shift_id' => ['required', 'integer', 'exists:work_shifts,id'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $currentShiftId = $user->assignedShiftIdForDate($validated['work_date']);
        if ((int) $currentShiftId === (int) $validated['requested_shift_id']) {
            return back()->with('error', 'Ca đề nghị phải khác ca đang được phân công trong ngày đó.');
        }

        $alreadyPending = ShiftChangeRequest::where('user_id', $user->id)
            ->whereDate('work_date', $validated['work_date'])
            ->where('status', 'pending')
            ->exists();
        if ($alreadyPending) {
            return back()->with('error', 'Bạn đã có một yêu cầu đổi ca đang chờ duyệt cho ngày này.');
        }

        $changeRequest = ShiftChangeRequest::create([
            'user_id' => $user->id,
            'work_date' => $validated['work_date'],
            'current_shift_id' => $currentShiftId,
            'requested_shift_id' => $validated['requested_shift_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $requestedShift = WorkShift::find($validated['requested_shift_id']);
        $admins = User::whereHas('role', fn ($query) => $query->where('name', 'admin'))->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type' => 'shift_change_request',
                'message' => "{$user->name} xin đổi sang {$requestedShift->name} ngày " . Carbon::parse($validated['work_date'])->format('d/m/Y') . '.',
                'link' => route('admin.shifts.index'),
                'is_read' => 0,
            ]);
        }

        return back()->with('success', 'Đã gửi yêu cầu đổi ca đến quản trị viên.');
    }

    /**
     * Admin duyệt yêu cầu đổi ca theo ngày.
     */
    public function approveChangeRequest(ShiftChangeRequest $changeRequest)
    {
        /** @var User|null $admin */
        $admin = Auth::guard('admin')->user();
        abort_unless($admin && $admin->isAdmin(), 403);

        if ($changeRequest->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý trước đó.');
        }

        $changeRequest->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        \App\Models\Notification::create([
            'user_id' => $changeRequest->user_id,
            'type' => 'shift_change_approved',
            'message' => 'Yêu cầu đổi ca ngày ' . $changeRequest->work_date->format('d/m/Y') . ' đã được duyệt.',
            'link' => route('admin.shifts.index'),
            'is_read' => 0,
        ]);

        return back()->with('success', 'Đã duyệt yêu cầu đổi ca cho ' . ($changeRequest->user->name ?? 'nhân viên') . '.');
    }

    /**
     * Admin từ chối yêu cầu đổi ca.
     */
    public function rejectChangeRequest(Request $request, ShiftChangeRequest $changeRequest)
    {
        /** @var User|null $admin */
        $admin = Auth::guard('admin')->user();
        abort_unless($admin && $admin->isAdmin(), 403);

        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($changeRequest->status !== 'pending') {
            return back()->with('error', 'Yêu cầu này đã được xử lý trước đó.');
        }

        $changeRequest->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'admin_note' => $validated['admin_note'] ?? null,
            'reviewed_at' => now(),
        ]);

        \App\Models\Notification::create([
            'user_id' => $changeRequest->user_id,
            'type' => 'shift_change_rejected',
            'message' => 'Yêu cầu đổi ca ngày ' . $changeRequest->work_date->format('d/m/Y') . ' đã bị từ chối.',
            'link' => route('admin.shifts.index'),
            'is_read' => 0,
        ]);

        return back()->with('success', 'Đã từ chối yêu cầu đổi ca.');
    }

    /**
     * Bắt đầu ca làm việc (Check-in)
     */
    public function checkIn(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Admin không chấm công
        if ($user->isAdmin()) {
            return back()->with('error', 'Tài khoản Quản trị viên (Admin) không thực hiện chấm công ca. Chức năng chấm công dành riêng cho Nhân viên trực ca.');
        }

        // Bắt buộc phải có ca phân công, kể cả ca được duyệt đổi riêng cho hôm nay
        $assignedShiftId = $user->assignedShiftIdForDate();
        if (!$assignedShiftId) {
            return back()->with('error', 'Tài khoản của bạn chưa được phân công ca làm việc. Vui lòng liên hệ Quản trị viên.');
        }

        // Kiểm tra xem thời điểm hiện tại có đúng ca làm việc phân công không
        if (!$user->canOperateInCurrentShift()) {
            $currentShiftName = WorkShift::getCurrentShiftName();
            $myShiftName = WorkShift::find($assignedShiftId)?->name ?: 'Chưa phân ca';
            return back()->with('error', "Hiện tại hệ thống đang trong [{$currentShiftName}]. Bạn được phân công làm việc ở [{$myShiftName}]. Bạn chỉ có quyền xem, không được phép chấm công ngoài ca trực của mình.");
        }

        // Kiểm tra ca gửi lên: Bắt buộc phải là ca được phân công
        $shiftId = (int) $request->input('shift_id', $assignedShiftId);
        if ($shiftId !== (int) $assignedShiftId) {
            $myShiftName = WorkShift::find($assignedShiftId)?->name ?: 'được phân công';
            return back()->with('error', "Bạn chỉ được phép chấm công đúng ca làm việc của mình [{$myShiftName}].");
        }

        // Kiểm tra xem đã có ca nào đang mở không
        $existingLog = StaffWorkLog::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($existingLog) {
            return back()->with('error', 'Bạn đang trong ca trực [' . $existingLog->shift_name . ']. Hãy kết thúc ca trước khi bắt đầu ca mới.');
        }

        $shift = WorkShift::find($assignedShiftId);
        $shiftName = $shift ? $shift->name : WorkShift::getCurrentShiftName();

        StaffWorkLog::create([
            'user_id' => $user->id,
            'shift_id' => $shift ? $shift->id : null,
            'shift_name' => $shiftName,
            'login_at' => now(),
            'check_in_at' => now(),
            'ip_address' => $request->ip(),
            'note' => $request->input('note', 'Bắt đầu ca trực xử lý đơn hàng Karate-Do'),
            'status' => 'active',
        ]);

        return back()->with('success', "Đã Check-in thành công vào {$shiftName}! Bắt đầu phiên làm việc.");
    }

    /**
     * Kết thúc ca làm việc (Check-out & Bàn giao ca)
     */
    public function checkOut(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $workLog = StaffWorkLog::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$workLog) {
            return back()->with('error', 'Không tìm thấy ca trực nào đang hoạt động.');
        }

        $checkOutTime = now();
        $checkInTime = $workLog->check_in_at ?: $workLog->login_at;
        $totalMinutes = $checkInTime->diffInMinutes($checkOutTime);

        $handoverNote = $request->input('note', '');
        $finalNote = $workLog->note;
        if (!empty($handoverNote)) {
            $finalNote .= " | Bàn giao ca: " . $handoverNote;
        }

        $workLog->update([
            'check_out_at' => $checkOutTime,
            'total_minutes' => $totalMinutes,
            'note' => $finalNote,
            'status' => 'completed',
        ]);

        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;
        $timeStr = $hours > 0 ? "{$hours} giờ {$mins} phút" : "{$mins} phút";

        return back()->with('success', "Đã kết thúc ca làm việc [{$workLog->shift_name}]. Tổng thời gian trực: {$timeStr}, Đã xử lý: {$workLog->orders_handled_count} đơn hàng.");
    }

    /**
     * Xuất báo cáo ca làm việc & chấm công ra file CSV (Excel compatible)
     */
    public function export(Request $request)
    {
        $fileName = 'Bao_cao_ca_lam_viec_KarateDo_' . date('Y_m_d_His') . '.csv';

        $query = StaffWorkLog::with(['user', 'shift'])->latest('login_at');

        if ($request->filled('staff_id')) {
            $query->where('user_id', $request->staff_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('login_at', $request->date);
        }

        $logs = $query->get();

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Mã Log', 'Nhân viên', 'Email', 'Tên Ca Trực', 'Giờ Check-in', 'Giờ Check-out', 'Thời lượng (Phút)', 'Số đơn đã xử lý', 'Doanh số xử lý (VNĐ)', 'Trạng thái', 'Ghi chú'];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            // Thêm UTF-8 BOM để Excel hiển thị tiếng Việt không bị lỗi font
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->user->email ?? 'N/A',
                    $log->shift_name ?? 'N/A',
                    $log->check_in_at ? $log->check_in_at->format('d/m/Y H:i:s') : 'N/A',
                    $log->check_out_at ? $log->check_out_at->format('d/m/Y H:i:s') : 'Đang trực',
                    $log->total_minutes,
                    $log->orders_handled_count,
                    $log->total_revenue_handled,
                    $log->status === 'active' ? 'Đang trực ca' : 'Đã kết thúc',
                    $log->note,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
