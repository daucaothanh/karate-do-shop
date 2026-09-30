<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use App\Models\Contact;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển tổng quan (Dashboard)
     */
    public function index()
    {
        // 1. Thống kê tổng quan KPI
        $totalRevenue = Order::whereIn('status', ['completed', 'shipping', 'processing'])
            ->sum('total_price');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippingOrders = Order::where('status', 'shipping')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalProducts = Product::count();
        $lowStockProductsCount = Product::where('stock', '<=', 5)->count();

        $totalCustomers = User::whereHas('role', function ($query) {
            $query->where('name', 'customer');
        })->count();

        $totalStaff = User::whereHas('role', function ($query) {
            $query->where('name', 'staff');
        })->count();

        // 2. Dữ liệu biểu đồ doanh thu theo 6 tháng gần nhất
        $months = [];
        $monthlyRevenues = [];
        $monthlyOrderCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthLabel = 'Tháng ' . $monthDate->format('m/Y');
            $months[] = $monthLabel;

            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();

            $rev = Order::whereIn('status', ['completed', 'shipping', 'processing'])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_price');

            $cnt = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $monthlyRevenues[] = (float) $rev;
            $monthlyOrderCounts[] = $cnt;
        }

        // 3. Đơn hàng gần đây nhất
        $recentOrders = Order::with(['user', 'shippingAddress'])
            ->latest()
            ->take(7)
            ->get();

        // 4. Sản phẩm bán chạy nhất
        $topProducts = Product::with(['category', 'images'])
            ->withCount(['orderItems as total_sold' => function ($query) {
                $query->select(DB::raw('coalesce(sum(quantity), 0)'));
            }])
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 5. Sản phẩm sắp hết hàng (tồn kho <= 5)
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 6. Đánh giá mới nhất
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // 7. Tin nhắn liên hệ mới nhất
        $recentContacts = Contact::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.tong_quan', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalProducts',
            'lowStockProductsCount',
            'totalCustomers',
            'totalStaff',
            'months',
            'monthlyRevenues',
            'monthlyOrderCounts',
            'recentOrders',
            'topProducts',
            'lowStockProducts',
            'recentReviews',
            'recentContacts'
        ));
    }
}
