@extends('layouts.khach_hang')

@section('title', 'Sản phẩm đã mua | Karate-Do Shop')

@section('breadcrumb', 'Sản phẩm & Đơn hàng đã mua')

@push('styles')
<style>
    .order-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .order-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
    .order-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-item-row {
        padding: 16px 20px;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .order-item-row:last-child {
        border-bottom: none;
    }
    .order-item-img {
        width: 75px;
        height: 75px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        object-fit: contain;
        background: #ffffff;
        padding: 4px;
        flex-shrink: 0;
    }
    .order-card-footer {
        background: #fafafa;
        border-top: 1px solid #f1f5f9;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
</style>
@endpush

@section('content')
<div class="ltn__order-area mb-100 mt-40">
    <div class="container">
        <!-- Top Title Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap pb-3 border-bottom">
            <div>
                <h3 class="font-weight-bold text-dark mb-1">
                    <i class="fa-solid fa-box-open text-danger mr-2"></i> Sản Phẩm & Đơn Hàng Đã Mua
                </h3>
                <p class="text-muted small mb-0">Quản lý và theo dõi tiến độ các sản phẩm võ thuật Karate-Do bạn đã đặt hàng.</p>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('products.index') }}" class="btn btn-danger font-weight-bold">
                    <i class="fa fa-shopping-cart mr-1"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>

        @if($orders->isNotEmpty())
            @foreach($orders as $order)
                <div class="order-card">
                    <!-- Header -->
                    <div class="order-card-header">
                        <div>
                            <span class="font-weight-bold text-dark mr-2" style="font-size: 15px;">
                                Mã đơn hàng: <strong class="text-danger">#{{ $order->id }}</strong>
                            </span>
                            <span class="text-muted small">| Ngày đặt: {{ $order->created_at->format('d/m/Y - H:i') }}</span>
                        </div>
                        <div>
                            @if($order->status === 'pending')
                                <span class="badge-order-status-red"><i class="fa-solid fa-clock mr-1"></i> Chờ duyệt đơn</span>
                            @elseif($order->status === 'processing')
                                <span class="badge-order-status-red"><i class="fa-solid fa-check-double mr-1"></i> Đã duyệt đơn & Đang đóng gói</span>
                            @elseif($order->status === 'shipping')
                                <span class="badge-order-status-red"><i class="fa-solid fa-truck mr-1"></i> Đang giao hàng</span>
                            @elseif($order->status === 'completed')
                                <span class="badge-order-status-red"><i class="fa-solid fa-circle-check mr-1"></i> Đã giao thành công</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge-order-status-red" style="color: #991b1b !important; background:#fee2e2 !important; border-color:#fca5a5 !important;"><i class="fa-solid fa-circle-xmark mr-1"></i> Đã hủy đơn</span>
                            @else
                                <span class="badge-order-status-red">{{ $order->status }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="order-card-body">
                        @foreach($order->orderItems as $item)
                            @php
                                $img = $item->product && $item->product->image ? $item->product->image : ($item->product && $item->product->images->first() ? $item->product->images->first()->image : 'assets/clients/img/karate/vo-phuc-rikaido.png');
                            @endphp
                            <div class="order-item-row">
                                <img src="{{ asset($img) }}" alt="{{ $item->product->name ?? 'Sản phẩm' }}" class="order-item-img">
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold mb-1">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            <span class="text-dark">{{ $item->product_id }} (Sản phẩm)</span>
                                        @endif
                                    </h6>
                                    <div class="mb-1" style="font-size: 13px;">
                                        @if($item->color)
                                            <span class="badge badge-light border text-danger mr-1">Màu: {{ $item->color }}</span>
                                        @endif
                                        @if($item->size)
                                            <span class="badge badge-light border text-dark">Size: {{ $item->size }}</span>
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        Số lượng: <strong>x {{ $item->quantity }}</strong> {{ $item->product->unit ?? '' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold text-danger" style="font-size: 15px;">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫
                                    </div>
                                    <small class="text-muted">({{ number_format($item->price, 0, ',', '.') }} ₫/món)</small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer -->
                    <div class="order-card-footer">
                        <div class="order-footer-info">
                            <div class="small text-muted mb-1">
                                <i class="fa fa-credit-card mr-1 text-danger"></i> <strong>Thanh toán:</strong> 
                                @php $pm = $order->payment->payment_method ?? 'COD'; @endphp
                                @if(str_contains($pm, 'MoMo'))
                                    <span class="badge text-white px-2 py-1" style="background:#d82d8b;">MoMo Pay</span>
                                @elseif(str_contains($pm, 'VNPAY'))
                                    <span class="badge badge-primary px-2 py-1">VNPAY-QR</span>
                                @elseif(str_contains($pm, 'ZaloPay'))
                                    <span class="badge badge-info px-2 py-1" style="background:#008fe5;">ZaloPay</span>
                                @elseif(str_contains($pm, 'VietQR') || str_contains($pm, 'Banking'))
                                    <span class="badge badge-danger px-2 py-1">VietQR MBBank</span>
                                @elseif(str_contains($pm, 'Thẻ') || str_contains($pm, 'CARD'))
                                    <span class="badge badge-dark px-2 py-1">Thẻ Quốc Tế</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">COD (Tiền mặt)</span>
                                @endif

                                @if($order->payment && $order->payment->status === 'completed')
                                    <span class="badge badge-success ml-1"><i class="fa fa-check mr-1"></i>Đã thanh toán</span>
                                @else
                                    <span class="badge badge-warning text-dark ml-1"><i class="fa fa-clock mr-1"></i>Chưa thanh toán</span>
                                @endif
                            </div>
                            <div class="small text-muted">
                                <i class="fa fa-map-marker-alt mr-1 text-danger"></i> <strong>Người nhận:</strong> {{ $order->shippingAddress->fullname ?? $user->name }} ({{ $order->shippingAddress->phone ?? $user->phone_number }})
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                            <div class="text-right mr-3">
                                <span class="text-muted small d-block">Tổng thanh toán:</span>
                                <span class="font-weight-bold text-danger" style="font-size: 18px;">
                                    {{ number_format($order->total_price, 0, ',', '.') }} ₫
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-danger font-weight-bold btn-sm px-3 py-2" style="border-radius: 6px;">
                                    <i class="fa fa-file-invoice mr-1"></i> Xem chi tiết & Hóa đơn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="text-center py-5 bg-white border rounded shadow-sm">
                <i class="fa-solid fa-box-open fa-4x text-muted mb-3 d-block"></i>
                <h5 class="font-weight-bold text-dark">Bạn chưa có sản phẩm đã mua nào</h5>
                <p class="text-muted small mb-4">Hãy khám phá các mẫu võ phục, đai, và dụng cụ Karate-Do chuẩn thi đấu quốc tế tại cửa hàng của chúng tôi.</p>
                <a href="{{ route('products.index') }}" class="btn btn-danger font-weight-bold px-4 py-2">
                    <i class="fa fa-shopping-bag mr-1"></i> Khám phá sản phẩm ngay
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
