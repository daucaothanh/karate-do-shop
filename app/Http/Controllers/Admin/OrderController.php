<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng Karate-Do Shop
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:pending,processing,shipping,completed,cancelled'],
            'shift_name' => ['nullable', 'string', 'max:255'],
            'handled_by' => ['nullable', 'integer', 'exists:users,id'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'from_date' => ['nullable', 'date_format:Y-m-d'],
            'to_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $query = Order::with(['user', 'shippingAddress', 'payment', 'handledByStaff']);

        // Lọc theo trạng thái đơn
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo ca làm việc
        if ($request->filled('shift_name')) {
            $query->where('shift_name', $request->shift_name);
        }

        // Lọc theo nhân viên xử lý
        if ($request->filled('handled_by')) {
            $query->where('handled_by', $request->handled_by);
        }

        // Tìm kiếm theo mã đơn hoặc thông tin khách hàng
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('id', $keyword)
                  ->orWhereHas('user', function ($subQ) use ($keyword) {
                      $subQ->where('name', 'like', "%{$keyword}%")
                           ->orWhere('email', 'like', "%{$keyword}%")
                           ->orWhere('phone_number', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('shippingAddress', function ($subQ) use ($keyword) {
                      $subQ->where('fullname', 'like', "%{$keyword}%")
                           ->orWhere('phone', 'like', "%{$keyword}%")
                           ->orWhere('address', 'like', "%{$keyword}%");
                  });
            });
        }

        // Lọc theo ngày đặt
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10);
        $orders->appends($request->query());

        // Đếm nhanh trạng thái
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipping' => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Danh sách nhân viên và ca trực để lọc
        $staffMembers = \App\Models\User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'staff']);
        })->get();

        $shifts = \App\Models\WorkShift::all();

        return view('admin.orders.danh_sach', compact('orders', 'statusCounts', 'staffMembers', 'shifts'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'shippingAddress',
            'orderItems.product.images',
            'payment',
            'handledByStaff',
            'orderStatusHistories' => function ($q) {
                $q->latest();
            }
        ]);

        return view('admin.orders.chi_tiet', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng (kèm ghi nhận nhân viên và ca trực)
     */
    public function updateStatus(Request $request, Order $order)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();
        abort_unless($user instanceof User, 403);

        if ($user->isAdmin()) {
            return back()->with('error', 'Tài khoản Quản trị viên (Admin) không có quyền duyệt đơn. Chức năng duyệt đơn do Nhân viên trực ca đảm nhiệm.');
        }

        if ($user && !$user->canApproveOrders()) {
            $currentShiftName = \App\Models\WorkShift::getCurrentShiftName();
            $userShiftName = $user->defaultShift ? $user->defaultShift->name : 'Chưa phân ca';
            return back()->with('error', "Bạn không thuộc ca trực hiện tại [{$currentShiftName}]. Tài khoản của bạn được phân công trực [{$userShiftName}]. Bạn chỉ có quyền xem, không được duyệt đơn.");
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipping,completed,cancelled'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($order, $validated, $user) {
            $order = Order::whereKey($order->getKey())->lockForUpdate()->firstOrFail();

            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            if ($oldStatus !== $newStatus) {
                // Xác định ca làm việc hiện tại
                $currentShiftName = \App\Models\WorkShift::getCurrentShiftName();
                $shiftId = null;

                if ($user) {
                    $activeLog = \App\Models\StaffWorkLog::where('user_id', $user->id)
                        ->where('status', 'active')
                        ->latest()
                        ->first();

                    if ($activeLog) {
                        $currentShiftName = $activeLog->shift_name ?: $currentShiftName;
                        $shiftId = $activeLog->shift_id;
                        // Cộng dồn thống kê vào ca trực của nhân viên
                        if (!$order->confirmed_at && !in_array($newStatus, ['pending', 'cancelled'], true)) {
                            $activeLog->recordOrderHandled($order->total_price);
                        }
                    }
                }

                $order->status = $newStatus;
                $order->handled_by = $user ? $user->id : $order->handled_by;
                $order->shift_id = $shiftId ?: $order->shift_id;
                $order->shift_name = $currentShiftName ?: $order->shift_name;
                if (!$order->confirmed_at && !in_array($newStatus, ['pending', 'cancelled'], true)) {
                    $order->confirmed_at = now();
                }
                $order->save();

                // Ghi nhận lịch sử thay đổi trạng thái
                $statusLabels = [
                    'pending' => 'Chờ xử lý',
                    'processing' => 'Đang xử lý / Đóng gói',
                    'shipping' => 'Đang giao hàng',
                    'completed' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy đơn'
                ];

                $userName = $user ? $user->name : 'Hệ thống';
                $changeNote = "Chuyển từ [" . ($statusLabels[$oldStatus] ?? $oldStatus) . "] sang [" . ($statusLabels[$newStatus] ?? $newStatus) . "]. ";
                $changeNote .= "Ca trực: [{$currentShiftName}]. ";
                if (!empty($validated['note'])) {
                    $changeNote .= "Ghi chú: " . $validated['note'];
                }
                $changeNote .= " (Thực hiện bởi: {$userName})";

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $newStatus,
                    'note' => $changeNote,
                ]);

                // Gửi thông báo trực tiếp đến tài khoản khách hàng kèm lời cảm ơn
                if ($order->user_id) {
                    $customerMsg = match ($newStatus) {
                        'processing' => "Đơn hàng #{$order->id} của bạn đã được duyệt thành công! Karate-Do Shop chân thành cảm ơn bạn đã tin tưởng đặt mua võ phục và dụng cụ võ thuật.",
                        'shipping' => "Đơn hàng #{$order->id} đã được xuất kho và đang trên đường giao đến bạn. Karate-Do Shop chân thành cảm ơn Quý khách!",
                        'completed' => "Đơn hàng #{$order->id} đã giao thành công và hoàn tất! Karate-Do Shop trân trọng cảm ơn Quý khách đã đồng hành cùng chúng tôi.",
                        'cancelled' => "Đơn hàng #{$order->id} đã được cập nhật sang trạng thái Đã hủy đơn.",
                        default => "Đơn hàng #{$order->id} đã được cập nhật trạng thái: " . ($statusLabels[$newStatus] ?? $newStatus),
                    };

                    \App\Models\Notification::create([
                        'user_id' => $order->user_id,
                        'type' => 'order_status',
                        'message' => $customerMsg,
                        'link' => route('orders.show', $order->id),
                        'is_read' => 0,
                    ]);
                }
            }

        });

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $order->id . ' thành công theo ca trực.');
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePayment(Request $request, Order $order)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();

        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,completed,failed,refunded'],
        ]);

        $payment = $order->payment;
        $oldPaymentStatus = $payment?->status;
        if ($payment) {
            $payment->update([
                'status' => $validated['payment_status'],
                'paid_at' => $validated['payment_status'] === 'completed'
                    ? ($payment->paid_at ?: now())
                    : null,
            ]);
        } else {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'payment_method' => 'COD',
                'status' => $validated['payment_status'],
                'paid_at' => $validated['payment_status'] === 'completed' ? now() : null,
            ]);
        }

        if ($validated['payment_status'] === 'completed' && $oldPaymentStatus !== 'completed' && $order->user_id) {
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'type' => 'payment_confirmed',
                'message' => "Thanh toán đơn hàng #{$order->id} đã được xác nhận đầy đủ.",
                'link' => route('orders.show', $order->id),
                'is_read' => 0,
            ]);
        }

        return back()->with('success', 'Đã cập nhật trạng thái thanh toán cho đơn hàng #' . $order->id);
    }

    /**
     * Xác nhận ảnh thanh toán và báo cho khách hàng.
     * Việc duyệt đơn vẫn thực hiện qua luồng cập nhật trạng thái cũ.
     */
    public function approvePaymentProof(Order $order)
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();
        abort_unless($user instanceof User, 403);

        DB::transaction(function () use ($order, $user) {
            $order = Order::whereKey($order->getKey())->lockForUpdate()->firstOrFail();
            $payment = Payment::where('order_id', $order->id)->lockForUpdate()->firstOrFail();

            abort_unless($payment->payment_proof, 422, 'Đơn hàng chưa có ảnh xác nhận thanh toán.');
            abort_if($order->status === 'cancelled', 422, 'Không thể xác nhận thanh toán cho đơn hàng đã hủy.');

            $wasPaid = $payment->status === 'completed';
            $payment->update([
                'status' => 'completed',
                'paid_at' => $payment->paid_at ?: now(),
            ]);

            if (!$wasPaid) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'note' => "Đã kiểm tra ảnh và xác nhận thanh toán đầy đủ. Đơn hàng vẫn chờ nhân viên duyệt để chuẩn bị hàng. (Thực hiện bởi: {$user->name})",
                ]);

                if ($order->user_id) {
                    $customerMessage = "Ảnh thanh toán đơn hàng #{$order->id} đã được xác nhận. Đơn hàng đã thanh toán đầy đủ và đang chờ nhân viên duyệt để chuẩn bị hàng.";

                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'type' => 'payment_confirmed',
                    'message' => $customerMessage,
                    'link' => route('orders.show', $order->id),
                    'is_read' => 0,
                ]);
                }
            }
        });

        return back()->with('success', 'Đã xác nhận thanh toán đầy đủ cho đơn hàng #' . $order->id . '. Đơn vẫn chờ nhân viên duyệt.');
    }

    /**
     * In hóa đơn / Phiếu xuất kho
     */
    public function invoice(Order $order)
    {
        $order->load(['user', 'shippingAddress', 'orderItems.product', 'payment']);
        return view('admin.orders.hoa_don', compact('order'));
    }

    /**
     * API Lấy thông báo đơn hàng chờ duyệt theo ca trực của nhân viên
     */
    public function shiftNotifications()
    {
        /** @var User|null $user */
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return response()->json([
                'count' => 0,
                'shift_name' => '',
                'can_approve' => false,
                'orders' => []
            ]);
        }

        $myShift = $user->defaultShift;
        $query = Order::with(['shippingAddress', 'user'])->where('status', 'pending');

        if ($user->isStaff() && !$user->isAdmin()) {
            if ($user->default_shift_id) {
                $query->where(function ($q) use ($user, $myShift) {
                    $q->where('shift_id', $user->default_shift_id);
                    if ($myShift) {
                        $q->orWhere('shift_name', 'like', '%' . $myShift->name . '%');
                    }
                });
            }
        }

        $count = $query->count();
        $orders = $query->latest()->take(6)->get()->map(function ($order) {
            $customerName = $order->shippingAddress->fullname ?? ($order->user->name ?? 'Khách vãng lai');
            $customerPhone = $order->shippingAddress->phone ?? ($order->user->phone_number ?? '');
            return [
                'id' => $order->id,
                'customer' => $customerName,
                'phone' => $customerPhone,
                'total' => number_format($order->total_price, 0, ',', '.') . ' ₫',
                'time' => $order->created_at->diffForHumans(),
                'shift_name' => $order->shift_name ?: 'Chưa gán ca',
                'url' => route('admin.orders.show', $order->id),
            ];
        });

        $shiftName = $myShift ? $myShift->name : ($user->isAdmin() ? 'Tất cả các ca' : 'Chưa phân ca');

        return response()->json([
            'count' => $count,
            'shift_name' => $shiftName,
            'can_approve' => $user->canApproveOrders(),
            'orders' => $orders,
        ]);
    }
}
