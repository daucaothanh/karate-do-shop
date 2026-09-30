@extends('layouts.khach_hang')

@section('title', 'Thanh toán & Đặt hàng | Karate-Do Shop')
@section('breadcrumb', 'Thanh toán đơn hàng')

@push('styles')
<style>
    .payment-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        background: #fff;
        cursor: pointer;
        transition: all .2s ease;
        margin-bottom: 12px;
    }
    .payment-option-card:hover,
    .payment-option-card.active {
        border-color: #d32f2f;
        background: #fff8f8;
        box-shadow: 0 4px 14px rgba(211, 47, 47, .12);
    }
</style>
@endpush

@section('content')
<div class="ltn__checkout-area mb-120 mt-40">
    <div class="container">
        <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            <div class="row">
                <div class="col-lg-7 col-md-12 mb-30">
                    <div class="ltn__checkout-single-content border rounded p-4 bg-white shadow-sm">
                        <h4 class="font-weight-bold mb-4 pb-2 border-bottom">
                            <i class="fa fa-map-marker-alt text-danger mr-2"></i> Thông tin nhận hàng
                        </h4>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="fullname" class="form-control" value="{{ old('fullname', auth()->user()->name ?? '') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="customerPhoneInput" class="form-control" value="{{ old('phone', auth()->user()->phone_number ?? '') }}" required oninput="updateTransferContent()">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="font-weight-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address ?? '') }}" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Ghi chú đơn hàng & Size võ phục</label>
                            <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                        </div>

                        <h5 class="font-weight-bold mb-3 pb-2 border-bottom">
                            <i class="fa fa-credit-card text-danger mr-2"></i> Chọn phương thức thanh toán
                        </h5>

                        <div class="payment-option-card" id="cardCOD" onclick="selectPaymentMethod('COD')">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="paymentCOD" name="payment_method" value="COD" class="custom-control-input" onchange="handlePaymentChange()">
                                <label class="custom-control-label font-weight-bold" for="paymentCOD">
                                    <i class="fa-solid fa-truck-ramp-box text-danger mr-1"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                                <div class="small text-muted pl-4 mt-1">Kiểm tra hàng trước khi thanh toán tiền mặt cho bưu tá.</div>
                            </div>
                        </div>

                        <div class="payment-option-card" id="cardBanking" onclick="selectPaymentMethod('Banking')">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="paymentBanking" name="payment_method" value="Banking" class="custom-control-input" onchange="handlePaymentChange()">
                                <label class="custom-control-label font-weight-bold" for="paymentBanking">
                                    <i class="fa-solid fa-qrcode text-danger mr-1"></i> Chuyển khoản bằng QR MBBank
                                </label>
                                <div class="small text-muted pl-4 mt-1">Quét mã QR bên phải, sau đó tải ảnh biên lai để thanh toán được ghi nhận ngay.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="ltn__checkout-single-content border rounded p-4 bg-white shadow-sm">
                        <h4 class="font-weight-bold mb-4 pb-2 border-bottom">Đơn hàng của bạn</h4>
                        <div class="table-responsive mb-3">
                            <table class="table table-borderless">
                                <tbody>
                                @foreach($items as $item)
                                    <tr class="border-bottom">
                                        <td class="pl-0">
                                            <div class="font-weight-bold">{{ $item->product->name }}</div>
                                            <small class="text-muted">SL: {{ $item->quantity }} {{ $item->product->unit }}</small>
                                            @if($item->color || $item->size)
                                                <div class="small text-muted">{{ $item->color ? 'Màu: ' . $item->color : '' }} {{ $item->size ? 'Size: ' . $item->size : '' }}</div>
                                            @endif
                                        </td>
                                        <td class="text-right font-weight-bold text-nowrap">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} ₫</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php
                            $finalOrderTotal = $total >= 500000 ? $total : $total + 30000;
                            $userPhone = auth()->user()->phone_number ?? '0967137200';
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <strong>{{ number_format($total, 0, ',', '.') }} ₫</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí giao hàng:</span>
                            <strong class="text-success">{{ $total >= 500000 ? 'Miễn phí' : '30.000 ₫' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>TỔNG CỘNG:</strong>
                            <strong class="text-danger" style="font-size:24px;">{{ number_format($finalOrderTotal, 0, ',', '.') }} ₫</strong>
                        </div>

                        <div class="mb-4 p-3 rounded border text-center" role="button" tabindex="0" onclick="selectPaymentMethod('Banking')" onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); selectPaymentMethod('Banking'); }" style="background:#fff8f8; border-color:#f5c2c7 !important; cursor:pointer;">
                            <div class="font-weight-bold text-danger mb-2"><i class="fa-solid fa-qrcode mr-1"></i> Hãy quét mã QR để chuyển khoản</div>
                            <img src="https://img.vietqr.io/image/MB-04223320042004-compact2.png?amount={{ $finalOrderTotal }}&addInfo=KARATE%20{{ $userPhone }}&accountName=DAU%20CAO%20THANH" onerror="this.src='{{ asset('assets/clients/img/vietqr_mb.png') }}'" alt="Mã QR MBBank" class="img-fluid rounded border bg-white p-1" style="max-width:220px;">
                            <div class="small text-muted mt-2">Số tiền đã điền sẵn: {{ number_format($finalOrderTotal, 0, ',', '.') }} ₫</div>
                            <div class="small mt-1"><strong>STK:</strong> 04223320042004 - <strong>DAU CAO THANH</strong></div>
                            <div class="small text-success font-weight-bold mt-2"><i class="fa-solid fa-bolt mr-1"></i> Gửi ảnh biên lai để admin xử lý và gửi hàng nhanh hơn.</div>
                        </div>

                        <div class="mb-4 p-3 rounded border" style="background:#fff8f8; border-color:#fecaca !important;">
                            <label for="paymentProof" class="font-weight-bold text-danger mb-1"><i class="fa-solid fa-image mr-1"></i> Tải ảnh biên lai thanh toán</label>
                            <input type="file" id="paymentProof" name="payment_proof" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                            <small class="form-text text-muted">JPG, PNG hoặc WEBP, tối đa 5MB. Gửi ảnh để thanh toán tự động ghi nhận thành công.</small>
                        </div>

                        <button type="submit" class="btn btn-danger btn-block btn-lg font-weight-bold py-3" style="border-radius:8px;">
                            <i class="fa fa-check-circle mr-2"></i> XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectPaymentMethod(method) {
        var radio = document.getElementById('payment' + method);
        if (radio) radio.checked = true;
        handlePaymentChange();
    }

    function handlePaymentChange() {
        ['COD', 'Banking'].forEach(function(method) {
            var card = document.getElementById('card' + method);
            var radio = document.getElementById('payment' + method);
            if (card) card.classList.toggle('active', radio && radio.checked);
        });
    }

    function updateTransferContent() {
        var phoneInput = document.getElementById('customerPhoneInput');
        var badge = document.getElementById('transferContentBadge');
        if (phoneInput && badge) badge.innerText = 'KARATE ' + (phoneInput.value.trim() || '0967137200');
    }
</script>
@endpush
