@extends('layouts.quan_tri')

@section('title', 'Báo cáo Doanh thu & Kinh doanh')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-chart-pie text-danger mr-2"></i> BÁO CÁO DOANH THU & KINH DOANH CAO CẤP</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.reports.export.sales', request()->query()) }}" class="btn btn-outline-success font-weight-bold mr-1">
            <i class="fa-solid fa-file-excel mr-1"></i> Xuất Excel / CSV
        </a>
        <button onclick="window.print()" class="btn btn-outline-dark font-weight-bold">
            <i class="fa-solid fa-print mr-1"></i> In báo cáo
        </button>
    </div>
</div>

<div class="clearfix"></div>

<!-- Date Filter Panel -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row align-items-end">
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Từ ngày:</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Đến ngày:</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Nhân viên:</label>
                <select name="staff_id" class="form-control">
                    <option value="">Tất cả nhân viên</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" @selected(request('staff_id') == $staff->id)>{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Ca làm việc:</label>
                <select name="shift_id" class="form-control">
                    <option value="">Tất cả ca</option>
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" @selected(request('shift_id') == $shift->id)>{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 col-sm-12 form-group">
                <button type="submit" class="btn btn-karate btn-block font-weight-bold">
                    <i class="fa-solid fa-chart-simple mr-1"></i> XEM BÁO CÁO THEO CA / NHÂN VIÊN
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary KPI Row -->
<div class="row tile_count">
    <div class="col-md-4 col-sm-6">
        <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="kpi-content">
                <div class="kpi-title">DOANH THU TRONG KỲ</div>
                <div class="kpi-value">{{ number_format($totalRevenuePeriod, 0, ',', '.') }} <small style="font-size: 14px;">₫</small></div>
                <div class="kpi-desc text-muted">Từ {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="kpi-content">
                <div class="kpi-title">TỔNG SỐ ĐƠN HÀNG</div>
                <div class="kpi-value">{{ $totalOrdersPeriod }} <small style="font-size: 14px;">đơn</small></div>
                <div class="kpi-desc text-muted">Các đơn phát sinh trong kỳ</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="kpi-card kpi-orange">
            <div class="kpi-icon"><i class="fa-solid fa-calculator"></i></div>
            <div class="kpi-content">
                <div class="kpi-title">GIÁ TRỊ TRUNG BÌNH / ĐƠN</div>
                <div class="kpi-value">
                    {{ $totalOrdersPeriod > 0 ? number_format($totalRevenuePeriod / $totalOrdersPeriod, 0, ',', '.') : 0 }} <small style="font-size: 14px;">₫</small>
                </div>
                <div class="kpi-desc text-muted">Hiệu quả đơn hàng</div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Summary -->
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
            <div class="kpi-content">
                <div class="kpi-title">TỔNG TIỀN MẶT</div>
                <div class="kpi-value">{{ number_format($cashRevenuePeriod, 0, ',', '.') }} <small style="font-size: 14px;">₫</small></div>
                <div class="kpi-desc text-muted">Các đơn COD / tiền mặt trong kỳ</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div class="kpi-content">
                <div class="kpi-title">TỔNG TIỀN CHUYỂN KHOẢN</div>
                <div class="kpi-value">{{ number_format($bankingRevenuePeriod, 0, ',', '.') }} <small style="font-size: 14px;">₫</small></div>
                <div class="kpi-desc text-muted">Các đơn chuyển khoản trong kỳ</div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Trend Chart -->
<div class="row">
    <div class="col-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-chart-line mr-1"></i> Biểu đồ diễn biến doanh thu theo ngày</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if(count($dates) > 0)
                    <canvas id="periodSalesChart" style="min-height: 280px; width: 100%;"></canvas>
                @else
                    <p class="text-muted text-center py-4">Chưa có phát sinh doanh thu trong khoảng thời gian này.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Row: Shift Breakdown & Staff Leaderboard -->
<div class="row">
    <!-- Performance by Shift -->
    <div class="col-md-6 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-business-time mr-1"></i> Doanh số theo Ca làm việc</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Ca làm việc</th>
                            <th class="text-center">Số đơn</th>
                            <th class="text-right">Tổng doanh số</th>
                            <th class="text-right">Tiền mặt</th>
                            <th class="text-right">Tiền CK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shiftReports as $sr)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $sr->shift_name }}</td>
                            <td class="text-center font-weight-bold text-primary">{{ $sr->total_orders }} đơn</td>
                            <td class="text-right font-weight-bold text-danger">{{ number_format($sr->total_revenue, 0, ',', '.') }} ₫</td>
                            <td class="text-right font-weight-bold text-success">{{ number_format($sr->cash_revenue, 0, ',', '.') }} ₫</td>
                            <td class="text-right font-weight-bold text-primary">{{ number_format($sr->banking_revenue, 0, ',', '.') }} ₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Chưa có dữ liệu đơn theo ca.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Staff Performance Leaderboard -->
    <div class="col-md-6 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-trophy text-warning mr-1"></i> Hiệu suất nhân viên xử lý đơn</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nhân viên</th>
                            <th class="text-center">Đơn đã duyệt</th>
                            <th class="text-right">Doanh số phụ trách</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffPerformances as $sp)
                        <tr>
                            <td>
                                <strong>{{ $sp->name }}</strong>
                                <div class="small text-muted">{{ $sp->role->name ?? '' }}</div>
                            </td>
                            <td class="text-center font-weight-bold text-primary">{{ $sp->total_orders_handled }} đơn</td>
                            <td class="text-right font-weight-bold text-danger">{{ number_format($sp->total_revenue_handled ?? 0, 0, ',', '.') }} ₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Chưa có dữ liệu nhân viên.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Top Selling Products in Period -->
<div class="row">
    <div class="col-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-fire text-danger mr-1"></i> Top 10 sản phẩm võ thuật bán chạy trong kỳ</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th width="40">STT</th>
                            <th>Tên sản phẩm võ thuật</th>
                            <th>Danh mục</th>
                            <th class="text-right">Giá bán</th>
                            <th class="text-center">Số lượng bán ra</th>
                            <th class="text-right">Ước tính doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $idx => $tp)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $tp->name }}</td>
                            <td><span class="badge badge-light border">{{ $tp->category->name ?? 'N/A' }}</span></td>
                            <td class="text-right font-weight-bold">{{ number_format($tp->price, 0, ',', '.') }} ₫</td>
                            <td class="text-center font-weight-bold text-primary">{{ $tp->total_sold ?? 0 }} {{ $tp->unit }}</td>
                            <td class="text-right font-weight-bold text-danger">{{ number_format(($tp->total_sold ?? 0) * $tp->price, 0, ',', '.') }} ₫</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Chưa có dữ liệu bán hàng trong kỳ này.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(count($dates) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('periodSalesChart').getContext('2d');
        const dates = @json($dates);
        const revenues = @json($revenues);
        const orderCounts = @json($orderCounts);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Doanh thu (VNĐ)',
                        data: revenues,
                        backgroundColor: '#d32f2f',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Số lượng đơn',
                        data: orderCounts,
                        type: 'line',
                        borderColor: '#3498db',
                        borderWidth: 3,
                        yAxisID: 'y1',
                        tension: 0.2
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        position: 'left',
                        ticks: {
                            callback: function(val) {
                                return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(val) + ' ₫';
                            }
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush
