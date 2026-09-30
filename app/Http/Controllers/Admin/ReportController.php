<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\StaffWorkLog;
use App\Models\WorkShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Báo cáo kinh doanh & Doanh thu tổng hợp
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'staff_id' => ['nullable', 'integer', 'exists:users,id'],
            'shift_id' => ['nullable', 'integer', 'exists:work_shifts,id'],
        ]);

        $reportOrders = $this->ordersForReport($request, $fromDate, $toDate);

        // 1. Doanh thu theo ngày trong khoảng thời gian đã chọn
        $salesData = $reportOrders->groupBy(function ($order) {
            return ($order->confirmed_at ?: $order->created_at)->format('Y-m-d');
        })->sortKeys()->map(function ($orders, $date) {
            return (object) [
                'date' => $date,
                'total_orders' => $orders->count(),
                'revenue' => $orders->sum('total_price'),
            ];
        })->values();

        $dates = $salesData->pluck('date')->map(function ($d) {
            return \Carbon\Carbon::parse($d)->format('d/m');
        })->toArray();
        $revenues = $salesData->pluck('revenue')->toArray();
        $orderCounts = $salesData->pluck('total_orders')->toArray();

        // 2. Thống kê theo Ca làm việc trong khoảng thời gian
        $shiftReports = $reportOrders->whereNotNull('shift_name')->groupBy('shift_name')->map(function ($orders, $shiftName) {
            return (object) [
                'shift_name' => $shiftName,
                'total_orders' => $orders->count(),
                'total_revenue' => $orders->sum('total_price'),
                'cash_revenue' => $this->paymentRevenue($orders, 'cash'),
                'banking_revenue' => $this->paymentRevenue($orders, 'banking'),
            ];
        })->values();

        // 3. Hiệu suất nhân viên xử lý đơn trong khoảng thời gian
        $staffPerformances = User::whereHas('role', function ($q) {
                $q->whereIn('name', ['admin', 'staff']);
            })
            ->withCount(['handledOrders as total_orders_handled' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            }])
            ->withSum(['handledOrders as total_revenue_handled' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
            }], 'total_price')
            ->orderByDesc('total_orders_handled')
            ->get();

        // 4. Sản phẩm bán chạy nhất
        $topProducts = Product::with('category')
            ->withCount(['orderItems as total_sold' => function ($query) use ($fromDate, $toDate) {
                $query->whereHas('order', function ($subQ) use ($fromDate, $toDate) {
                    $subQ->whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
                })->select(DB::raw('coalesce(sum(quantity), 0)'));
            }])
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        // 5. Tổng hợp tóm tắt
        $totalRevenuePeriod = $reportOrders->sum('total_price');
        $totalOrdersPeriod = $reportOrders->count();
        $cashRevenuePeriod = $this->paymentRevenue($reportOrders, 'cash');
        $bankingRevenuePeriod = $this->paymentRevenue($reportOrders, 'banking');

        $staffMembers = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['admin', 'staff']);
        })->orderBy('name')->get();
        $shifts = WorkShift::orderBy('start_time')->get();

        return view('admin.reports.bao_cao', compact(
            'fromDate',
            'toDate',
            'dates',
            'revenues',
            'orderCounts',
            'shiftReports',
            'staffPerformances',
            'topProducts',
            'totalRevenuePeriod',
            'totalOrdersPeriod',
            'cashRevenuePeriod',
            'bankingRevenuePeriod',
            'staffMembers',
            'shifts'
        ));
    }

    private function ordersForReport(Request $request, string $fromDate, string $toDate)
    {
        $hasShiftScope = $request->filled('staff_id') || $request->filled('shift_id');

        if (!$hasShiftScope) {
            return Order::with('payment')
                ->whereIn('status', ['completed', 'shipping', 'processing'])
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->get();
        }

        $logs = StaffWorkLog::query()
            ->when($request->filled('staff_id'), fn ($query) => $query->where('user_id', $request->staff_id))
            ->when($request->filled('shift_id'), fn ($query) => $query->where('shift_id', $request->shift_id))
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('check_in_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                    ->orWhere(function ($query) use ($fromDate, $toDate) {
                        $query->whereNull('check_in_at')
                            ->whereBetween('login_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                    });
            })->get();

        $orders = collect();
        foreach ($logs as $log) {
            $start = $log->check_in_at ?: $log->login_at;
            $end = $log->check_out_at ?: now();

            $orders = $orders->merge(Order::with('payment')
                ->whereIn('status', ['completed', 'shipping', 'processing'])
                ->where('handled_by', $log->user_id)
                ->where('shift_id', $log->shift_id)
                ->whereNotNull('confirmed_at')
                ->whereBetween('confirmed_at', [$start, $end])
                ->get());
        }

        return $orders->unique('id')->values();
    }

    private function paymentRevenue($orders, string $type)
    {
        return $orders->filter(function ($order) use ($type) {
            $method = strtolower($order->payment?->payment_method ?? '');
            if ($type === 'cash') {
                return str_contains($method, 'cod') || str_contains($method, 'cash');
            }

            return str_contains($method, 'chuyển khoản')
                || str_contains($method, 'banking')
                || str_contains($method, 'paypal');
        })->sum(fn ($order) => $order->payment?->amount ?? $order->total_price);
    }

    /**
     * Xuất báo cáo doanh thu ra CSV
     */
    public function exportSales(Request $request)
    {
        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $fileName = "Bao_cao_doanh_thu_KarateDo_{$fromDate}_den_{$toDate}.csv";

        $orders = $this->ordersForReport($request, $fromDate, $toDate);

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Mã Đơn', 'Ngày đặt', 'Khách hàng', 'Số điện thoại', 'Tổng tiền (VNĐ)', 'Trạng thái đơn', 'Ca làm việc', 'Nhân viên xử lý', 'Thời gian xác nhận'];

        $callback = function () use ($orders, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $columns);

            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->id,
                    $o->created_at->format('d/m/Y H:i'),
                    $o->shippingAddress->fullname ?? ($o->user->name ?? 'N/A'),
                    $o->shippingAddress->phone ?? ($o->user->phone_number ?? 'N/A'),
                    $o->total_price,
                    $o->status,
                    $o->shift_name ?? 'Chưa gán',
                    $o->handledByStaff->name ?? 'Tự động',
                    $o->confirmed_at ? $o->confirmed_at->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
