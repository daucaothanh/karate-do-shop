@extends('layouts.khach_hang')

@section('title', 'Giỏ hàng của bạn | Karate-Do Shop')

@section('breadcrumb', 'Giỏ hàng')

@section('content')
<!-- SHOPPING CART AREA START -->
<div class="liton__shoping-cart-area mb-120 mt-40">
    <div class="container">
        @if($items->isNotEmpty())
            <div class="row">
                <div class="col-lg-8 col-md-12 mb-30">
                    <div class="shoping-cart-inner border rounded p-4 bg-white shadow-sm">
                        <h4 class="font-weight-bold mb-4 pb-2 border-bottom">
                            <i class="fa fa-shopping-cart text-danger mr-2"></i> Giỏ hàng ({{ $items->count() }} loại sản phẩm)
                        </h4>

                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-center" width="130">Số lượng</th>
                                        <th class="text-right">Thành tiền</th>
                                        <th class="text-center" width="50">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $cartImg = $item->product->image ?: ($item->product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                                                @endphp
                                                <img src="{{ asset($cartImg) }}" alt="{{ $item->product->name }}" class="border rounded mr-3" style="width: 65px; height: 65px; object-fit: contain; background: #fafafa;">
                                                <div>
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="font-weight-bold text-dark d-block">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <div class="mt-1">
                                                        @if($item->color)
                                                            <span class="badge badge-light border text-danger mr-1" style="font-size: 11px;">Màu: {{ $item->color }}</span>
                                                        @endif
                                                        @if($item->size)
                                                            <span class="badge badge-light border text-dark" style="font-size: 11px;">Size: {{ $item->size }}</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted d-block mt-1">Đơn vị: {{ $item->product->unit }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right font-weight-bold align-middle">
                                            {{ number_format($item->product->price, 0, ',', '.') }} ₫
                                        </td>
                                        <td class="align-middle text-center">
                                            <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center font-weight-bold" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" onchange="this.form.submit()" style="width: 70px;">
                                            </form>
                                        </td>
                                        <td class="text-right font-weight-bold text-danger align-middle font-size-16">
                                            {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} ₫
                                        </td>
                                        <td class="text-center align-middle">
                                            <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa khỏi giỏ hàng">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left mr-1"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Side -->
                <div class="col-lg-4 col-md-12">
                    <div class="shoping-cart-total border rounded p-4 bg-white shadow-sm">
                        <h5 class="font-weight-bold mb-3 pb-2 border-bottom">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="font-weight-bold">{{ number_format($total, 0, ',', '.') }} ₫</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="text-success font-weight-bold">
                                @if($total >= 500000)
                                    Miễn phí
                                @else
                                    30.000 ₫
                                @endif
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="font-weight-bold font-size-18">Tổng thanh toán:</span>
                            <span class="font-weight-bold text-danger" style="font-size: 22px;">
                                {{ number_format($total >= 500000 ? $total : $total + 30000, 0, ',', '.') }} ₫
                            </span>
                        </div>

                        <a href="{{ route('checkout.create') }}" class="btn btn-danger btn-block btn-lg font-weight-bold py-3">
                            TIẾN HÀNH ĐẶT HÀNG <i class="fa fa-arrow-right ml-1"></i>
                        </a>

                        <div class="small text-muted mt-3 text-center">
                            <i class="fa fa-shield-alt text-success mr-1"></i> Thanh toán an toàn & Bảo mật 100%
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center py-5 border rounded bg-white shadow-sm">
                    <i class="fa fa-shopping-basket fa-4x text-muted mb-3 d-block"></i>
                    <h4 class="font-weight-bold text-dark">Giỏ hàng của bạn đang trống!</h4>
                    <p class="text-muted mb-4">Hãy chọn mua các sản phẩm võ phục và dụng cụ Karate-Do chính hãng bạn yêu thích.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-danger btn-lg font-weight-bold">
                        <i class="fa fa-shopping-cart mr-2"></i> MUA SẮM NGAY
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- SHOPPING CART AREA END -->
@endsection