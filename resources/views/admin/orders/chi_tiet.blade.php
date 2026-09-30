@extends('layouts.quan_tri')

@section('title', 'Chi tiết Đơn hàng #' . $order->id)

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>
            <i class="fa-solid fa-file-invoice text-danger mr-2"></i> CHI TIẾT ĐƠN HÀNG #{{ $order->id }}
            <small class="text-muted">Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</small>
        </h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-dark mr-1">
            <i class="fa-solid fa-print mr-1"></i> In hóa đơn / Phiếu giao
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Danh sách đơn
        </a>
    </div>
</div>

<div class="clearfix"></div>

<!-- Top Status Alert Box -->
<div class="x_panel">
    <div class="x_content">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-muted mr-2">Trạng thái hiện tại:</span>
                @if($order->status === 'pending')
                    <span class="badge-status badge-pending font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-clock mr-1"></i> Chờ xử lý</span>
                @elseif($order->status === 'processing')
                    <span class="badge-status badge-processing font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-box mr-1"></i> Đang đóng gói / chuẩn bị hàng</span>
                @elseif($order->status === 'shipping')
                    <span class="badge-status badge-shipping font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-truck mr-1"></i> Đang giao hàng</span>
                @elseif($order->status === 'completed')
                    <span class="badge-status badge-completed font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-check mr-1"></i> Đã hoàn thành đơn</span>
                @elseif($order->status === 'cancelled')
                    <span class="badge-status badge-cancelled font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-xmark mr-1"></i> Đã hủy đơn hàng</span>
                @endif
            </div>

            <div class="col-md-6 text-md-right mt-2 mt-md-0">
                @php $currentUser = auth()->user(); @endphp
                @if($currentUser && $currentUser->isAdmin())
                    <button type="button" class="btn btn-secondary font-weight-bold" disabled style="cursor: not-allowed;" title="Tài khoản Admin không duyệt đơn. Quyền duyệt đơn thuộc về Nhân viên trực ca.">
                        <i class="fa-solid fa-lock mr-1"></i> Admin không duyệt đơn
                    </button>
                @elseif($currentUser && $currentUser->canApproveOrders())
                    <!-- Status Update Form Button (Nhân viên đúng ca trực) -->
                    <button type="button" class="btn btn-karate font-weight-bold shadow-sm" data-toggle="modal" data-target="#updateStatusModal">
                        <i class="fa-solid fa-pen mr-1"></i> Cập nhật trạng thái đơn (Duyệt đơn)
                    </button>
                @else
                    <!-- Nút bị khóa khi nhân viên ngoài ca -->
                    <button type="button" class="btn btn-secondary font-weight-bold" disabled style="cursor: not-allowed;" title="Bạn chỉ có quyền xem, không được duyệt đơn ngoài ca trực phân công">
                        <i class="fa-solid fa-lock mr-1"></i> Chế độ chỉ xem (Ngoài ca)
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@if($currentUser && $currentUser->isAdmin())
    <div class="alert alert-secondary border-secondary shadow-sm mb-3">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-user-shield fa-2x text-muted mr-3"></i>
            <div>
                <strong class="d-block text-dark font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-circle-info mr-1 text-primary"></i> TÀI KHOẢN QUẢN TRỊ VIÊN (ADMIN)</strong>
                <span class="text-dark small">
                    Hệ thống đã phân quyền: <strong>Tài khoản Admin không trực tiếp duyệt đơn</strong>. Nhiệm vụ duyệt và xử lý đơn hàng được giao cho <strong>Nhân viên trực ca</strong>.
                </span>
            </div>
        </div>
    </div>
@elseif($currentUser && !$currentUser->canApproveOrders())
    @php
        $currentShiftName = \App\Models\WorkShift::getCurrentShiftName();
        $userShiftName = $currentUser->defaultShift ? $currentUser->defaultShift->name : 'Chưa phân ca';
    @endphp
    <div class="alert alert-warning border-warning shadow-sm mb-3">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-lock fa-2x text-warning mr-3"></i>
            <div>
                <strong class="d-block text-dark font-weight-bold" style="font-size: 15px;"><i class="fa-solid fa-shield mr-1 text-danger"></i> CHẾ ĐỘ CHỈ XEM (BẠN ĐANG TRUY CẬP NGOÀI CA TRỰC ĐƯỢC PHÂN CÔNG)</strong>
                <span class="text-dark small">
                    Hệ thống đang trong <strong>{{ $currentShiftName }}</strong>. Tài khoản của bạn được phân công làm việc ở <strong>{{ $userShiftName }}</strong>. 
                    Bạn chỉ có quyền xem thông tin đơn hàng, không thể duyệt đơn hoặc thay đổi trạng thái đơn ngoài ca.
                </span>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <!-- Customer & Shipping Information -->
    <div class="col-md-4 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-user mr-1"></i> Người nhận hàng</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="mb-1"><strong>Họ và tên:</strong> {{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'N/A') }}</p>
                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? 'Chưa cung cấp') }}</p>
                <p class="mb-1"><strong>Email tài khoản:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Địa chỉ nhận:</strong> {{ $order->shippingAddress->address ?? '' }}, {{ $order->shippingAddress->city ?? '' }}</p>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-business-time mr-1"></i> Ca trực & Nhân viên</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <p class="mb-1"><strong>Ca làm việc:</strong> <span class="badge badge-light border text-dark font-weight-bold">{{ $order->shift_name ?: 'Chưa gán ca' }}</span></p>
                <p class="mb-1"><strong>Nhân viên phụ trách:</strong> <span class="text-primary font-weight-bold">{{ $order->handledByStaff->name ?? 'Chưa phân công' }}</span></p>
                <p class="mb-0"><strong>Thời gian xử lý:</strong> <small class="text-muted">{{ $order->confirmed_at ? $order->confirmed_at->format('d/m/Y H:i:s') : 'Chưa ghi nhận' }}</small></p>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-credit-card mr-1"></i> Thanh toán</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @php $payment = $order->payment; $pm = $payment->payment_method ?? 'COD'; @endphp
                <p class="mb-2">
                    <strong>Phương thức:</strong><br>
                    @if(str_contains($pm, 'MoMo'))
                        <span class="badge text-white px-2 py-1" style="background:#d82d8b; font-size:12px;"><span style="background:#a50064;padding:1px 4px;border-radius:4px;font-size:10px;font-weight:bold;margin-right:2px;">MoMo</span> Ví MoMo (MoMo Pay)</span>
                    @elseif(str_contains($pm, 'VNPAY'))
                        <span class="badge badge-primary px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-qrcode mr-1"></i> Cổng VNPAY-QR</span>
                    @elseif(str_contains($pm, 'ZaloPay'))
                        <span class="badge badge-info px-2 py-1" style="background:#008fe5; font-size:12px;"><i class="fa-solid fa-wallet mr-1"></i> Ví ZaloPay</span>
                    @elseif(str_contains($pm, 'VietQR') || str_contains($pm, 'Banking'))
                        <span class="badge badge-danger px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-building-columns mr-1"></i> Chuyển khoản VietQR (MBBank)</span>
                    @elseif(str_contains($pm, 'Thẻ') || str_contains($pm, 'CARD'))
                        <span class="badge badge-dark px-2 py-1" style="font-size:12px;"><i class="fab fa-cc-visa text-primary mr-1"></i> Thẻ Quốc Tế</span>
                    @else
                        <span class="badge badge-secondary px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-truck mr-1"></i> COD (Thanh toán khi nhận hàng)</span>
                    @endif
                </p>

                @if($payment && $payment->transaction_id)
                    <div class="alert alert-info py-2 px-3 mb-2 small font-weight-bold border">
                        <i class="fa-solid fa-receipt mr-1"></i> Mã GD / Thẻ: 
                        <span class="text-danger font-size-14">{{ $payment->transaction_id }}</span>
                    </div>
                @endif

                @if($payment && $payment->payment_proof)
                    <div class="mt-3 p-2 border rounded bg-light">
                        <div class="small font-weight-bold text-dark mb-2">
                            <i class="fa-solid fa-image mr-1 text-danger"></i> Ảnh xác nhận thanh toán của khách
                        </div>
                        <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Ảnh xác nhận thanh toán đơn #{{ $order->id }}" class="img-fluid rounded border" style="max-height: 320px; width: 100%; object-fit: contain; background: #fff;">
                        </a>
                        @if($order->status !== 'cancelled' && $payment->status !== 'completed')
                            <form action="{{ route('admin.orders.payment-proof.approve', $order->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-block font-weight-bold" onclick="return confirm('Xác nhận ảnh hợp lệ và ghi nhận thanh toán đầy đủ?')">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Xác nhận ảnh & thanh toán
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success py-2 px-3 mt-2 mb-0 small font-weight-bold">
                                <i class="fa-solid fa-check mr-1"></i> Đã xác nhận thanh toán, đơn vẫn chờ nhân viên duyệt
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-light border py-2 px-3 mt-2 mb-2 small text-muted">
                        <i class="fa-regular fa-image mr-1"></i> Khách chưa gửi ảnh xác nhận thanh toán.
                    </div>
                @endif

                <p class="mb-2"><strong>Trạng thái:</strong>
                    @if($payment && $payment->status === 'completed')
                        <span class="badge badge-success px-2 py-1"><i class="fa-solid fa-check mr-1"></i> Đã thanh toán</span>
                    @elseif($payment && $payment->status === 'failed')
                        <span class="badge badge-danger px-2 py-1"><i class="fa-solid fa-xmark mr-1"></i> Thất bại</span>
                    @else
                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fa-solid fa-clock mr-1"></i> Chưa thanh toán</span>
                    @endif
                </p>

                    <!-- Form to update payment status -->
                    <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST" class="mt-3 pt-2 border-top">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted mb-1"><i class="fa-solid fa-credit-card mr-1"></i> Cập nhật thanh toán:</label>
                            <select name="payment_status" class="form-control" style="height: auto !important; min-height: 44px !important; padding: 10px 36px 10px 14px !important; font-size: 14.5px !important; font-weight: 600 !important; line-height: 1.5 !important; color: #1e293b !important; border-radius: 8px !important; border: 1.5px solid #cbd5e1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;">
                                <option value="pending" {{ ($payment && $payment->status === 'pending') ? 'selected' : '' }}>⏳ Chưa thanh toán</option>
                                <option value="completed" {{ ($payment && $payment->status === 'completed') ? 'selected' : '' }}>✅ Đã thanh toán đầy đủ</option>
                                <option value="failed" {{ ($payment && $payment->status === 'failed') ? 'selected' : '' }}>❌ Thanh toán thất bại</option>
                                <option value="refunded" {{ ($payment && $payment->status === 'refunded') ? 'selected' : '' }}>↩️ Đã hoàn tiền</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold py-2 shadow-sm" style="border-radius: 8px; font-size: 14px;">
                            <i class="fa-solid fa-check mr-1"></i> Cập nhật thanh toán
                        </button>
                    </form>
            </div>
        </div>
    </div>

    <!-- Order Items & Calculation -->
    <div class="col-md-8 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-box-open mr-1"></i> Danh sách sản phẩm đặt mua</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Sản phẩm võ thuật</th>
                            <th class="text-center" width="90">Đơn vị</th>
                            <th class="text-right" width="130">Đơn giá</th>
                            <th class="text-center" width="80">SL</th>
                            <th class="text-right" width="140">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->images->count() > 0)
                                        <img src="{{ asset($item->product->images->first()->image) }}" class="product-thumb-sm mr-2" alt="{{ $item->product->name }}">
                                    @endif
                                    <div>
                                        <strong>{{ $item->product->name ?? 'Sản phẩm không còn tồn tại' }}</strong>
                                        <div class="mt-1">
                                            @if($item->color)
                                                <span class="badge badge-light border text-danger" style="font-size: 11px;">Màu: {{ $item->color }}</span>
                                            @endif
                                            @if($item->size)
                                                <span class="badge badge-light border text-dark" style="font-size: 11px;">Size: {{ $item->size }}</span>
                                            @endif
                                        </div>
                                        @if($item->product)
                                            <div class="small text-muted">Mã SP: #{{ $item->product->id }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->product->unit ?? 'Bộ' }}</td>
                            <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td class="text-center font-weight-bold">{{ $item->quantity }}</td>
                            <td class="text-right font-weight-bold text-danger">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold bg-light">TỔNG GIÁ TRỊ ĐƠN HÀNG:</td>
                            <td class="text-right font-weight-bold text-danger font-size-18" style="font-size: 18px;">
                                {{ number_format($order->total_price, 0, ',', '.') }} ₫
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Status History Log -->
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-clock-rotate-left mr-1"></i> Lịch sử xử lý đơn hàng</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($order->orderStatusHistories->count() > 0)
                    <ul class="list-unstyled timeline">
                        @foreach($order->orderStatusHistories as $history)
                        <li class="mb-3 pl-3 border-left" style="border-left: 3px solid #d32f2f !important;">
                            <div class="d-flex justify-content-between">
                                <strong class="text-dark">{{ $history->note ?? 'Thay đổi trạng thái sang: ' . $history->status }}</strong>
                                <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0 small">Chưa có lịch sử thay đổi bổ sung.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($currentUser && $currentUser->canApproveOrders())
<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="updateStatusModalLabel"><i class="fa-solid fa-truck-ramp-box mr-1"></i> Cập nhật trạng thái đơn hàng #{{ $order->id }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Chọn trạng thái mới <span class="text-danger">*</span>:</label>
                        <select name="status" class="form-control font-weight-bold" style="height: auto !important; min-height: 44px !important; padding: 10px 36px 10px 14px !important; font-size: 14.5px !important; line-height: 1.5 !important; border-radius: 8px !important; border: 1.5px solid #cbd5e1 !important;" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Chờ xử lý (Mới đặt)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>📦 Đang xử lý / Đóng gói võ phục</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>🚚 Đang giao hàng cho bên vận chuyển</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Giao thành công / Hoàn tất</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Hủy đơn hàng</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Ghi chú (Tùy chọn):</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Đã gọi điện xác nhận size võ phục với khách, giao qua Viettel Post mã vận đơn 123456..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-karate font-weight-bold">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
