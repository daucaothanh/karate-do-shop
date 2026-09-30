@extends('layouts.quan_tri')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-cart-shopping text-danger mr-2"></i> QUẢN LÝ ĐƠN HÀNG SHOP</h3>
    </div>
</div>

<div class="clearfix"></div>

<!-- Status Quick Tabs -->
<div class="mb-3 d-flex flex-wrap">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm mr-2 mb-2 {{ !request('status') ? 'btn-dark font-weight-bold' : 'btn-outline-dark' }}">
        Tất cả đơn ({{ $statusCounts['all'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm mr-2 mb-2 {{ request('status') === 'pending' ? 'btn-warning font-weight-bold text-dark' : 'btn-outline-warning text-dark' }}">
        <i class="fa-solid fa-clock mr-1"></i> Chờ xử lý ({{ $statusCounts['pending'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm mr-2 mb-2 {{ request('status') === 'processing' ? 'btn-primary font-weight-bold' : 'btn-outline-primary' }}">
        <i class="fa-solid fa-box mr-1"></i> Đang xử lý / đóng gói ({{ $statusCounts['processing'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="btn btn-sm mr-2 mb-2 {{ request('status') === 'shipping' ? 'btn-info font-weight-bold' : 'btn-outline-info' }}">
        <i class="fa-solid fa-truck mr-1"></i> Đang giao hàng ({{ $statusCounts['shipping'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn btn-sm mr-2 mb-2 {{ request('status') === 'completed' ? 'btn-success font-weight-bold' : 'btn-outline-success' }}">
        <i class="fa-solid fa-check-circle mr-1"></i> Hoàn thành ({{ $statusCounts['completed'] }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm mr-2 mb-2 {{ request('status') === 'cancelled' ? 'btn-danger font-weight-bold' : 'btn-outline-danger' }}">
        <i class="fa-solid fa-ban mr-1"></i> Đã hủy ({{ $statusCounts['cancelled'] }})
    </a>
</div>

<!-- Search and Filter Form -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row align-items-end">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Mã đơn, tên khách, số điện thoại:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <label class="font-weight-500">Ca làm việc:</label>
                <select name="shift_name" class="form-control">
                    <option value="">-- Tất cả ca --</option>
                    @foreach($shifts as $sh)
                        <option value="{{ $sh->name }}" {{ request('shift_name') == $sh->name ? 'selected' : '' }}>{{ $sh->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <label class="font-weight-500">Nhân viên xử lý:</label>
                <select name="handled_by" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach($staffMembers as $st)
                        <option value="{{ $st->id }}" {{ request('handled_by') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <label class="font-weight-500">Từ ngày:</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <label class="font-weight-500">Đến ngày:</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-1 col-sm-12 form-group">
                <button type="submit" class="btn btn-secondary btn-block">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list-check mr-1"></i> Danh sách đơn đặt hàng ({{ $orders->total() }} đơn)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái đơn</th>
                    <th>Ca làm việc</th>
                    <th>Nhân viên phụ trách</th>
                    <th>Thời gian đặt</th>
                    <th width="140" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="font-weight-bold text-dark">
                        <a href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->id }}</a>
                    </td>
                    <td>
                        <strong>{{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'Khách vãng lai') }}</strong>
                        <div class="small text-muted"><i class="fa-solid fa-phone mr-1"></i> {{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? '') }}</div>
                    </td>
                    <td class="font-weight-bold text-danger font-size-16">
                        <div>{{ number_format($order->total_price, 0, ',', '.') }} ₫</div>
                        @php $pm = $order->payment->payment_method ?? 'COD'; @endphp
                        <div class="mt-1 font-weight-normal" style="font-size: 11px;">
                            @if(str_contains($pm, 'MoMo'))
                                <span class="badge text-white" style="background:#d82d8b;">MoMo Pay</span>
                            @elseif(str_contains($pm, 'VNPAY'))
                                <span class="badge badge-primary">VNPAY-QR</span>
                            @elseif(str_contains($pm, 'ZaloPay'))
                                <span class="badge badge-info" style="background:#008fe5;">ZaloPay</span>
                            @elseif(str_contains($pm, 'VietQR') || str_contains($pm, 'Banking'))
                                <span class="badge badge-danger">VietQR MB</span>
                            @elseif(str_contains($pm, 'Thẻ') || str_contains($pm, 'CARD'))
                                <span class="badge badge-dark">Thẻ QT</span>
                            @else
                                <span class="badge badge-secondary">COD</span>
                            @endif

                            @if($order->payment && $order->payment->status === 'completed')
                                <i class="fa-solid fa-circle-check text-success ml-1" title="Đã thanh toán"></i>
                            @else
                                <i class="fa-solid fa-clock text-warning ml-1" title="Chưa thanh toán"></i>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($order->status === 'pending')
                            <span class="badge-status badge-pending"><i class="fa-solid fa-clock mr-1"></i> Chờ xử lý</span>
                        @elseif($order->status === 'processing')
                            <span class="badge-status badge-processing"><i class="fa-solid fa-box mr-1"></i> Đang đóng gói</span>
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
                    <td>
                        @if($order->shift_name)
                            <span class="badge badge-light border text-dark font-weight-500">{{ $order->shift_name }}</span>
                        @else
                            <span class="text-muted small">Tự động</span>
                        @endif
                    </td>
                    <td>
                        @if($order->handledByStaff)
                            <span class="text-dark font-weight-bold"><i class="fa-solid fa-user-check text-success mr-1"></i> {{ $order->handledByStaff->name }}</span>
                        @else
                            <span class="text-muted small">Chưa phân công</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm {{ (auth()->user() && auth()->user()->canApproveOrders()) ? 'btn-danger' : 'btn-info' }} btn-sm-action" title="{{ (auth()->user() && auth()->user()->canApproveOrders()) ? 'Xử lý & Duyệt đơn' : 'Xem chi tiết đơn' }}">
                            @if(auth()->user() && auth()->user()->canApproveOrders())
                                <i class="fa-solid fa-pen-to-square"></i> Duyệt
                            @else
                                <i class="fa-solid fa-eye"></i> Xem
                            @endif
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary btn-sm-action" title="In phiếu giao">
                            <i class="fa-solid fa-print"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                        Không có đơn hàng nào theo điều kiện lọc này.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $orders->firstItem() ?? 0 }} đến {{ $orders->lastItem() ?? 0 }} trong tổng số {{ $orders->total() }} đơn hàng
            </div>
            <div>
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
