@extends('layouts.khach_hang')

@section('title', 'Tài khoản của tôi | Karate-Do Shop')

@section('breadcrumb', 'Tài khoản của tôi')

@section('content')
<!-- WISHLIST AREA START -->
<div class="liton__wishlist-area pb-70 mt-40">
    <div class="container">
        <div class="row">
            <!-- Left Sidebar Navigation -->
            <div class="col-lg-4 col-md-12 mb-30">
                <div class="ltn__tab-menu-list border rounded p-4 bg-white shadow-sm">
                    <div class="text-center pb-3 border-bottom mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=d32f2f&color=fff&size=100" class="rounded-circle mb-2" style="width: 80px; height: 80px;">
                        <h5 class="font-weight-bold mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active font-weight-bold py-2 mb-1" id="v-pills-profile-tab" data-bs-toggle="pill" data-toggle="pill" href="#v-pills-profile" role="tab">
                            <i class="fa fa-user mr-2 text-danger"></i> Thông tin cá nhân
                        </a>
                        <a class="nav-link font-weight-bold py-2 mb-1" href="{{ route('orders.index') }}">
                            <i class="fa fa-box-open mr-2 text-danger"></i> Sản phẩm & Đơn đã mua ({{ $orders->total() }})
                        </a>
                        <a class="nav-link font-weight-bold py-2 mb-1" id="v-pills-orders-tab" data-bs-toggle="pill" data-toggle="pill" href="#v-pills-orders" role="tab">
                            <i class="fa fa-list mr-2 text-primary"></i> Quản lý đơn hàng ({{ $orders->total() }})
                        </a>
                        <a class="nav-link font-weight-bold py-2 mb-1" href="{{ route('wishlist.index') }}">
                            <i class="fa fa-heart mr-2 text-warning"></i> Sản phẩm yêu thích
                        </a>
                        <a class="nav-link font-weight-bold py-2 text-danger" href="#" onclick="event.preventDefault(); document.getElementById('client-logout-form').submit();">
                            <i class="fa fa-sign-out-alt mr-2"></i> Đăng xuất
                        </a>
                        <form id="client-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-lg-8 col-md-12">
                <div class="tab-content border rounded p-4 bg-white shadow-sm" id="v-pills-tabContent">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                        <h4 class="font-weight-bold mb-4 pb-2 border-bottom">
                            <i class="fa fa-id-card text-danger mr-2"></i> Cập nhật thông tin tài khoản
                        </h4>
                        <form method="POST" action="{{ route('account.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Email (Không thể thay đổi)</label>
                                <input class="form-control bg-light" value="{{ $user->email }}" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Số điện thoại</label>
                                <input class="form-control" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Ví dụ: 0987654321">
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Địa chỉ giao hàng mặc định</label>
                                <input class="form-control" name="address" value="{{ old('address', $user->address) }}" placeholder="Số nhà, tên đường, phường/xã...">
                            </div>
                            <button type="submit" class="btn btn-danger font-weight-bold px-4 py-2">
                                <i class="fa fa-save mr-1"></i> Lưu thay đổi
                            </button>
                        </form>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="v-pills-orders" role="tabpanel">
                        <h4 class="font-weight-bold mb-4 pb-2 border-bottom">
                            <i class="fa fa-list-alt text-primary mr-2"></i> Lịch sử đơn hàng võ thuật của bạn
                        </h4>
                        @if($orders->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Mã đơn</th>
                                            <th>Ngày đặt</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-center">Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td class="font-weight-bold">#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="font-weight-bold text-danger">{{ number_format($order->total_price, 0, ',', '.') }} ₫</td>
                                            <td>
                                                @if($order->status === 'pending')
                                                    <span class="badge-order-status-red"><i class="fa-solid fa-clock mr-1"></i> Chờ duyệt</span>
                                                @elseif($order->status === 'processing')
                                                    <span class="badge-order-status-red"><i class="fa-solid fa-check-double mr-1"></i> Đã duyệt & Đóng gói</span>
                                                @elseif($order->status === 'shipping')
                                                    <span class="badge-order-status-red"><i class="fa-solid fa-truck mr-1"></i> Đang giao</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge-order-status-red"><i class="fa-solid fa-circle-check mr-1"></i> Hoàn thành</span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="badge-order-status-red" style="color: #991b1b !important; background:#fee2e2 !important; border-color:#fca5a5 !important;"><i class="fa-solid fa-circle-xmark mr-1"></i> Đã hủy</span>
                                                @else
                                                    <span class="badge-order-status-red">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-danger">
                                                    Xem đơn
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $orders->links('pagination::bootstrap-4') }}
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-box-open fa-3x mb-2 d-block"></i>
                                Bạn chưa có đơn đặt hàng nào.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- WISHLIST AREA END -->
@endsection