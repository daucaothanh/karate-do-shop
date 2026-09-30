@extends('layouts.quan_tri')

@section('title', 'Bảng điều khiển & Thống kê')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-gauge-high text-danger mr-2"></i> BẢNG ĐIỀU KHIỂN <small>Tổng quan hoạt động kinh doanh Karate-Do Shop</small></h3>
    </div>
    <div class="title_right text-right">
        <span class="badge badge-light border p-2 text-secondary">
            <i class="fa-regular fa-clock mr-1"></i> Cập nhật: {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>
</div>

<div class="clearfix"></div>

<!-- KPI Tiles Row -->
<div class="row tile_count">
    <!-- Revenue -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="kpi-card kpi-green">
            <div class="kpi-icon">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">DOANH THU TỔNG</div>
                <div class="kpi-value">{{ number_format($totalRevenue, 0, ',', '.') }} <small style="font-size: 14px;">₫</small></div>
                <div class="kpi-desc text-success"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Từ các đơn đã xác nhận</div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">TỔNG ĐƠN HÀNG</div>
                <div class="kpi-value">{{ $totalOrders }} <small style="font-size: 14px;">đơn</small></div>
                <div class="kpi-desc text-primary font-weight-bold">
                    <i class="fa-regular fa-hourglass-half mr-1"></i> {{ $pendingOrders }} đơn chờ xử lý
                </div>
            </div>
        </div>
    </div>

    <!-- Products -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="kpi-card kpi-orange">
            <div class="kpi-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">SẢN PHẨM VÕ THUẬT</div>
                <div class="kpi-value">{{ $totalProducts }} <small style="font-size: 14px;">mẫu</small></div>
                <div class="kpi-desc text-danger font-weight-bold">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $lowStockProductsCount }} mẫu sắp hết hàng
                </div>
            </div>
        </div>
    </div>

    <!-- Customers & Staff -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="kpi-card kpi-purple">
            <div class="kpi-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-title">NGƯỜI DÙNG & NHÂN VIÊN</div>
                <div class="kpi-value">{{ $totalCustomers + $totalStaff }}</div>
                <div class="kpi-desc text-muted">
                    <span>{{ $totalCustomers }} Khách</span> | <span>{{ $totalStaff }} Nhân viên</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Row: Chart & Status Breakdown -->
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-chart-line"></i> Biểu đồ doanh thu 6 tháng gần nhất</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <canvas id="revenueChart" style="min-height: 280px; width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders Status Summary -->
    <div class="col-lg-4 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-chart-pie"></i> Tình trạng đơn hàng</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fa-solid fa-clock text-warning mr-2"></i> Chờ xử lý</span>
                        <span class="badge badge-warning badge-pill font-weight-bold">{{ $pendingOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fa-solid fa-box-open text-primary mr-2"></i> Đang đóng gói / xử lý</span>
                        <span class="badge badge-primary badge-pill font-weight-bold">{{ $processingOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fa-solid fa-truck-fast text-info mr-2"></i> Đang giao hàng</span>
                        <span class="badge badge-info badge-pill font-weight-bold">{{ $shippingOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fa-solid fa-circle-check text-success mr-2"></i> Hoàn thành</span>
                        <span class="badge badge-success badge-pill font-weight-bold">{{ $completedOrders }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="fa-solid fa-circle-xmark text-danger mr-2"></i> Đã hủy</span>
                        <span class="badge badge-danger badge-pill font-weight-bold">{{ $cancelledOrders }}</span>
                    </li>
                </ul>

                <div class="mt-4 pt-2 border-top text-center">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-karate btn-block">
                        <i class="fa-solid fa-list-check mr-1"></i> Quản lý toàn bộ đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row: Recent Orders & Top Products -->
<div class="row">
    <!-- Recent Orders Table -->
    <div class="col-lg-8 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-clock-rotate-left"></i> Đơn hàng mới nhất</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-xs btn-outline-secondary font-weight-bold">Xem tất cả</a>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td class="font-weight-bold text-dark">#{{ $order->id }}</td>
                            <td>
                                <div><strong>{{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'Khách vãng lai') }}</strong></div>
                                <small class="text-muted">{{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? '') }}</small>
                            </td>
                            <td class="font-weight-bold text-danger">
                                {{ number_format($order->total_price, 0, ',', '.') }} ₫
                            </td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge-status badge-pending"><i class="fa-solid fa-clock mr-1"></i> Chờ xử lý</span>
                                @elseif($order->status === 'processing')
                                    <span class="badge-status badge-processing"><i class="fa-solid fa-box mr-1"></i> Đang xử lý</span>
                                @elseif($order->status === 'shipping')
                                    <span class="badge-status badge-shipping"><i class="fa-solid fa-truck mr-1"></i> Đang giao</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge-status badge-completed"><i class="fa-solid fa-check mr-1"></i> Hoàn thành</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge-status badge-cancelled"><i class="fa-solid fa-xmark mr-1"></i> Đã hủy</span>
                                @else
                                    <span class="badge badge-secondary">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info btn-sm-action" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Selling / Low Stock Products -->
    <div class="col-lg-4 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-fire text-danger"></i> Sản phẩm bán chạy</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @forelse($topProducts as $prod)
                <div class="media mb-3 pb-2 border-bottom align-items-center">
                    @if($prod->images->count() > 0)
                        <img src="{{ asset($prod->images->first()->image) }}" class="mr-3 product-thumb-sm" alt="{{ $prod->name }}">
                    @else
                        <img src="https://via.placeholder.com/50?text=Karate" class="mr-3 product-thumb-sm" alt="{{ $prod->name }}">
                    @endif
                    <div class="media-body">
                        <h6 class="mt-0 mb-1 font-weight-bold" style="font-size: 13.5px;">
                                <a href="{{ route('admin.products.show', $prod->id) }}" class="text-dark">{{ Str::limit($prod->name, 28) }}</a>
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger font-weight-bold">{{ number_format($prod->price, 0, ',', '.') }} ₫</span>
                            <span class="badge badge-light border">Đã bán: {{ $prod->total_sold ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">Chưa có dữ liệu bán chạy.</p>
                @endforelse

                @if($lowStockProducts->count() > 0)
                <div class="mt-4 pt-2 border-top">
                    <h6 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Cảnh báo sắp hết hàng:</h6>
                    @foreach($lowStockProducts as $low)
                        <div class="d-flex justify-content-between align-items-center mb-2 small">
                            <span class="text-dark font-weight-500">{{ Str::limit($low->name, 26) }}</span>
                            <span class="badge badge-danger">Còn: {{ $low->stock }} {{ $low->unit }}</span>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const months = @json($months);
        const revenues = @json($monthlyRevenues);
        const orders = @json($monthlyOrderCounts);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Doanh thu (VNĐ)',
                        data: revenues,
                        borderColor: '#d32f2f',
                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Số lượng đơn hàng',
                        data: orders,
                        borderColor: '#3498db',
                        backgroundColor: '#3498db',
                        type: 'bar',
                        barThickness: 24,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y') {
                                    return context.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.raw) + ' ₫';
                                }
                                return context.dataset.label + ': ' + context.raw + ' đơn';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value) + ' ₫';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
