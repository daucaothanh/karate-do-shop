@extends('layouts.khach_hang')

@section('title', 'Chi tiết đơn hàng #' . $order->id . ' | Karate-Do Shop')

@section('breadcrumb', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<!-- ORDER DETAIL AREA START -->
<div class="liton__order-detail-area mb-120 mt-40">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="border rounded p-4 bg-white shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap pb-3 border-bottom mb-4">
                        <div>
                            <h4 class="font-weight-bold mb-1">
                                <i class="fa fa-receipt text-danger mr-2"></i> Đơn hàng #{{ $order->id }}
                            </h4>
                            <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                        <div>
                            @if($order->status === 'pending')
                                <span class="badge-order-status-red"><i class="fa-solid fa-clock mr-1"></i> Chờ duyệt đơn</span>
                            @elseif($order->status === 'processing')
                                <span class="badge-order-status-red"><i class="fa-solid fa-check-double mr-1"></i> Đã duyệt đơn & Đang đóng gói</span>
                            @elseif($order->status === 'shipping')
                                <span class="badge-order-status-red"><i class="fa-solid fa-truck mr-1"></i> Đang giao hàng</span>
                            @elseif($order->status === 'completed')
                                <span class="badge-order-status-red"><i class="fa-solid fa-circle-check mr-1"></i> Giao hàng thành công</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge-order-status-red" style="color: #991b1b !important; background:#fee2e2 !important; border-color:#fca5a5 !important;"><i class="fa-solid fa-circle-xmark mr-1"></i> Đơn đã hủy</span>
                            @endif
                        </div>
                    </div>

                    <!-- THÔNG BÁO DUYỆT ĐƠN & LỜI CẢM ƠN BÊN USER -->
                    @if($order->status === 'processing')
                        <div class="alert p-4 mb-4 rounded border" style="background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border: 2px solid #f87171 !important; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.08);">
                            <div class="d-flex align-items-start">
                                <div class="mr-3 d-none d-sm-block">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border-radius:50%; background:#fee2e2; color:#dc2626; font-size:22px;">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <h5 class="font-weight-bold mb-0" style="color: #dc2626;">
                                            <i class="fa-solid fa-circle-check text-danger mr-1 d-sm-none"></i> 🎉 ĐƠN HÀNG #{{ $order->id }} ĐÃ ĐƯỢC DUYỆT THÀNH CÔNG!
                                        </h5>
                                        <span class="badge px-3 py-1 font-weight-bold" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-size:12px;">
                                            <i class="fa-solid fa-box mr-1"></i> Đã duyệt đơn & Đang chuẩn bị hàng
                                        </span>
                                    </div>
                                    <p class="mb-2 text-dark" style="font-size: 14.5px; line-height: 1.6;">
                                        <strong style="color: #dc2626;">🙏 Lời cảm ơn từ Karate-Do Shop:</strong> Chân thành cảm ơn Quý khách <strong>{{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'Quý khách') }}</strong> đã tin tưởng đặt mua võ phục và trang thiết bị võ thuật tại hệ thống của chúng tôi! Đơn hàng của bạn đã được nhân viên tiếp nhận, duyệt đơn và hiện đang được kiểm tra, đóng gói cẩn thận để chuẩn bị bàn giao cho đơn vị vận chuyển sớm nhất.
                                    </p>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small pt-2 border-top">
                                        <span><i class="fa-solid fa-business-time text-danger mr-1"></i> <strong>Ca trực xử lý:</strong> {{ $order->shift_name ?: 'Ca làm việc tiêu chuẩn' }}</span>
                                        <span class="mx-2 d-none d-md-inline">•</span>
                                        <span><i class="fa-solid fa-calendar-check text-danger mr-1"></i> <strong>Thời gian duyệt đơn:</strong> {{ $order->confirmed_at ? $order->confirmed_at->format('d/m/Y - H:i:s') : $order->created_at->format('d/m/Y - H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($order->status === 'shipping')
                        <div class="alert p-4 mb-4 rounded border" style="background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border: 2px solid #f87171 !important; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.08);">
                            <div class="d-flex align-items-start">
                                <div class="mr-3 d-none d-sm-block">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border-radius:50%; background:#fee2e2; color:#dc2626; font-size:22px;">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <h5 class="font-weight-bold mb-0" style="color: #dc2626;">
                                            <i class="fa-solid fa-truck-fast text-danger mr-1 d-sm-none"></i> 🚚 ĐƠN HÀNG ĐÃ ĐƯỢC DUYỆT & ĐANG GIAO ĐẾN BẠN!
                                        </h5>
                                        <span class="badge px-3 py-1 font-weight-bold" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-size:12px;">
                                            <i class="fa-solid fa-truck mr-1"></i> Đang vận chuyển
                                        </span>
                                    </div>
                                    <p class="mb-2 text-dark" style="font-size: 14.5px; line-height: 1.6;">
                                        <strong style="color: #dc2626;">🙏 Karate-Do Shop cảm ơn Quý khách:</strong> Đơn hàng #{{ $order->id }} đã xuất kho và đang trên đường chuyển đến địa chỉ của bạn. Quý khách vui lòng để ý số điện thoại <strong>{{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? '') }}</strong> để nhận hàng nhé.
                                    </p>
                                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted small pt-2 border-top">
                                        <span><i class="fa-solid fa-business-time text-danger mr-1"></i> <strong>Ca trực phụ trách:</strong> {{ $order->shift_name ?: 'Ca làm việc' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($order->status === 'completed')
                        <div class="alert p-4 mb-4 rounded border" style="background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border: 2px solid #f87171 !important; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.08);">
                            <div class="d-flex align-items-start">
                                <div class="mr-3 d-none d-sm-block">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border-radius:50%; background:#fee2e2; color:#dc2626; font-size:22px;">
                                        <i class="fa-solid fa-award"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <h5 class="font-weight-bold mb-0" style="color: #dc2626;">
                                            <i class="fa-solid fa-award text-danger mr-1 d-sm-none"></i> ✅ ĐƠN HÀNG #{{ $order->id }} ĐÃ HOÀN THÀNH XUẤT SẮC!
                                        </h5>
                                        <span class="badge px-3 py-1 font-weight-bold" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; font-size:12px;">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Giao thành công
                                        </span>
                                    </div>
                                    <p class="mb-0 text-dark" style="font-size: 14.5px; line-height: 1.6;">
                                        <strong style="color: #dc2626;">🙏 Lời cảm ơn từ Karate-Do Shop:</strong> Xin gửi lời tri ân sâu sắc nhất đến Quý khách <strong>{{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'Quý khách') }}</strong> đã đồng hành và ủng hộ sản phẩm của chúng tôi! Chúc bạn luôn rèn luyện tốt, giữ vững ý chí và đam mê cùng tinh thần Karate-Do!
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($order->status === 'pending')
                        <div class="alert p-4 mb-4 rounded border" style="background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%); border: 2px solid #fb923c !important; box-shadow: 0 4px 15px rgba(234, 88, 12, 0.08);">
                            <div class="d-flex align-items-start">
                                <div class="mr-3 d-none d-sm-block">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border-radius:50%; background:#ffedd5; color:#c2410c; font-size:22px;">
                                        <i class="fa-solid fa-clock"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <h5 class="font-weight-bold mb-0" style="color: #c2410c;">
                                            <i class="fa-solid fa-clock text-warning mr-1 d-sm-none"></i> ⏳ ĐƠN HÀNG #{{ $order->id }} ĐÃ ĐẶT - ĐANG CHỜ DUYỆT ĐƠN
                                        </h5>
                                        <span class="badge px-3 py-1 font-weight-bold" style="background:#ffedd5; color:#c2410c; border:1px solid #fdba74; font-size:12px;">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i> Chờ duyệt đơn
                                        </span>
                                    </div>
                                    <p class="mb-0 text-dark" style="font-size: 14.5px; line-height: 1.6;">
                                        <strong>🙏 Cảm ơn Quý khách:</strong> Đơn hàng đã được hệ thống Karate-Do Shop tiếp nhận. Nhân viên trực ca hiện tại (<strong>{{ $order->shift_name ?: 'Ca trực' }}</strong>) đang kiểm tra đơn hàng và sẽ duyệt đơn cho bạn trong thời gian sớm nhất!
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($order->status === 'cancelled')
                        <div class="alert p-4 mb-4 rounded border" style="background: #fef2f2; border: 2px solid #f87171 !important;">
                            <h5 class="font-weight-bold mb-1" style="color: #991b1b;"><i class="fa-solid fa-circle-xmark mr-1"></i> ĐƠN HÀNG #{{ $order->id }} ĐÃ BỊ HỦY</h5>
                            <p class="mb-0 small text-dark">Đơn hàng này đã được hủy. Nếu có bất kỳ thắc mắc nào, Quý khách vui lòng liên hệ Hotline / Zalo: <strong>0967.137.200</strong> để được hỗ trợ nhanh chóng.</p>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <!-- Receiver Info -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-user mr-1"></i> Thông tin người nhận</h6>
                                <p class="mb-1"><strong>Họ và tên:</strong> {{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'N/A') }}</p>
                                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? 'N/A') }}</p>
                                <p class="mb-0"><strong>Địa chỉ giao hàng:</strong> {{ $order->shippingAddress->address ?? '' }}, {{ $order->shippingAddress->city ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Payment & Shipping Info -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded bg-light h-100">
                                <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-credit-card mr-1"></i> Thanh toán & Vận chuyển</h6>
                                @php $pm = $order->payment->payment_method ?? 'COD'; @endphp
                                <p class="mb-1">
                                    <strong>Phương thức:</strong> 
                                    @if(str_contains($pm, 'MoMo'))
                                        <span class="badge text-white px-2 py-1" style="background:#d82d8b; font-size:12px;"><span style="background:#a50064;padding:1px 4px;border-radius:4px;font-size:10px;font-weight:bold;margin-right:2px;">MoMo</span> Ví MoMo (MoMo Pay)</span>
                                    @elseif(str_contains($pm, 'VNPAY'))
                                        <span class="badge badge-primary px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-qrcode mr-1"></i> Cổng VNPAY-QR</span>
                                    @elseif(str_contains($pm, 'ZaloPay'))
                                        <span class="badge badge-info px-2 py-1" style="background:#008fe5; font-size:12px;"><i class="fa-solid fa-wallet mr-1"></i> Ví ZaloPay</span>
                                    @elseif(str_contains($pm, 'VietQR') || str_contains($pm, 'Banking'))
                                        <span class="badge badge-danger px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-building-columns mr-1"></i> Chuyển khoản VietQR (MBBank)</span>
                                    @elseif(str_contains($pm, 'Thẻ') || str_contains($pm, 'CARD'))
                                        <span class="badge badge-dark px-2 py-1" style="font-size:12px;"><i class="fab fa-cc-visa text-primary mr-1"></i> Thẻ Quốc Tế (Visa/Mastercard)</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1" style="font-size:12px;"><i class="fa-solid fa-truck-ramp-box mr-1"></i> COD (Thanh toán khi nhận hàng)</span>
                                    @endif
                                </p>
                                <p class="mb-1"><strong>Trạng thái thanh toán:</strong> 
                                    @if($order->payment && $order->payment->status === 'completed')
                                        <span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Đã thanh toán đầy đủ</span>
                                    @elseif($order->payment && $order->payment->status === 'failed')
                                        <span class="badge badge-danger px-2 py-1"><i class="fa fa-times mr-1"></i> Thanh toán thất bại</span>
                                    @elseif($order->payment && $order->payment->payment_proof)
                                        <span class="badge badge-info px-2 py-1"><i class="fa fa-hourglass-half mr-1"></i> Đã gửi ảnh thanh toán - chờ admin xác nhận</span>
                                    @else
                                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fa fa-clock mr-1"></i> Chưa thanh toán</span>
                                    @endif
                                </p>
                                @if($order->payment && $order->payment->transaction_id)
                                    <p class="mb-1">
                                        <strong>Mã giao dịch / Thẻ:</strong> 
                                        <span class="badge badge-light border text-primary font-weight-bold" style="font-size: 13px;">
                                            <i class="fa-solid fa-receipt mr-1"></i>{{ $order->payment->transaction_id }}
                                        </span>
                                    </p>
                                @endif
                                @if($order->payment && $order->payment->payment_proof)
                                    <p class="mb-1">
                                        <strong>Ảnh thanh toán:</strong>
                                        <a href="{{ asset('storage/' . $order->payment->payment_proof) }}" target="_blank" rel="noopener" class="text-danger font-weight-bold">
                                            <i class="fa-solid fa-image mr-1"></i> Đã gửi ảnh xác nhận
                                        </a>
                                    </p>
                                @endif
                                <p class="mb-0"><strong>Đơn vị vận chuyển:</strong> Giao hàng nhanh Karate-Do Express (24h-48h)</p>
                            </div>
                        </div>
                    </div>

                    @if($order->payment && $order->payment->status !== 'completed')
                        @php
                            $userPhone = $order->shippingAddress->phone ?? ($order->user->phone_number ?? '0967137200');
                        @endphp

                        <!-- 1. Hướng dẫn thanh toán MOMO -->
                        @if(str_contains($pm, 'MoMo'))
                            <div class="alert p-3 mb-4 bg-white rounded shadow-sm" style="border: 2px solid #d82d8b;">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <div class="p-2 border rounded bg-white shadow-sm d-inline-block" style="border-color: #fbcfe8 !important;">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=2|99|0967137200|DAU%20CAO%20THANH||0|0|{{ $order->total_price }}|KARATE%20DH{{ $order->id }}|transfer_p2p" alt="Mã MoMo QR Đơn #{{ $order->id }}" class="img-fluid rounded" style="max-height: 220px;">
                                        </div>
                                        <div class="small mt-2 font-weight-bold" style="color: #d82d8b;">
                                            <i class="fa-solid fa-camera mr-1"></i> Mở App MoMo quét mã thanh toán
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="font-weight-bold mb-2 d-flex align-items-center justify-content-between" style="color: #a50064;">
                                            <span><span class="badge-momo" style="width:22px;height:22px;font-size:9px;background:#a50064;color:#fff;display:inline-flex;align-items:center;justify-content:center;border-radius:4px;margin-right:4px;">MoMo</span> Thanh toán qua Ví MoMo - Đơn hàng #{{ $order->id }}</span>
                                            <span class="badge text-white px-2 py-1" style="background:#d82d8b;">MoMo Pay</span>
                                        </h5>
                                        <ul class="list-unstyled small mb-3 text-dark" style="line-height: 2;">
                                            <li><strong>Chủ ví MoMo:</strong> <span class="text-uppercase font-weight-bold">ĐẬU CAO THÀNH</span></li>
                                            <li>
                                                <strong>Số điện thoại MoMo:</strong> 
                                                <span class="font-weight-bold" style="color: #d82d8b; font-size: 16px;">0967137200</span>
                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 ml-2" style="font-size: 11px;" onclick="copyText('0967137200', this)"><i class="far fa-copy"></i> Chép SĐT</button>
                                            </li>
                                            <li>
                                                <strong>Số tiền:</strong> 
                                                <span class="font-weight-bold text-danger font-size-16">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 ml-2" style="font-size: 11px;" onclick="copyText('{{ $order->total_price }}', this)"><i class="far fa-copy"></i> Chép tiền</button>
                                            </li>
                                            <li>
                                                <strong>Lời nhắn chuyển MoMo:</strong> 
                                                <span class="badge badge-warning text-dark font-weight-bold" style="font-size: 13px;">KARATE DH{{ $order->id }}</span>
                                                <button type="button" class="btn btn-sm btn-outline-dark py-0 px-2 ml-2" style="font-size: 11px;" onclick="copyText('KARATE DH{{ $order->id }}', this)"><i class="far fa-copy"></i> Chép lời nhắn</button>
                                            </li>
                                        </ul>
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <a href="https://me.momo.vn/0967137200" target="_blank" class="btn btn-sm text-white font-weight-bold mr-2" style="background: linear-gradient(135deg, #d82d8b, #a50064);">
                                                <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Mở App MoMo ngay
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success font-weight-bold" data-toggle="collapse" data-target="#confirmPaymentCollapse">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Đã chuyển MoMo? Gửi mã xác nhận
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Collapse Form gửi mã xác nhận -->
                                <div class="collapse mt-3 pt-3 border-top" id="confirmPaymentCollapse">
                                    <form action="{{ route('orders.confirm-payment', $order->id) }}" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded border">
                                        @csrf
                                        <h6 class="font-weight-bold text-success mb-2"><i class="fa-solid fa-paper-plane mr-1"></i> Xác nhận giao dịch MoMo</h6>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-2">
                                                <label class="small font-weight-bold">Mã giao dịch MoMo (10-12 số):</label>
                                                <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Ví dụ: 25489123456" value="{{ $order->payment->transaction_id ?? '' }}" required>
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label class="small font-weight-bold">Tên người chuyển / Ghi chú:</label>
                                                <input type="text" name="note" class="form-control form-control-sm" placeholder="Tên tài khoản MoMo của bạn...">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success font-weight-bold mt-1">
                                            <i class="fa-solid fa-check mr-1"></i> Gửi mã cho Shop đối soát
                                        </button>
                                    </form>
                                </div>
                            </div>

                        <!-- 2. Hướng dẫn thanh toán VNPAY-QR -->
                        @elseif(str_contains($pm, 'VNPAY'))
                            <div class="alert p-3 mb-4 bg-white rounded shadow-sm" style="border: 2px solid #005baa;">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <div class="p-2 border rounded bg-white shadow-sm d-inline-block" style="border-color: #bfdbfe !important;">
                                            <img src="https://img.vietqr.io/image/MB-04223320042004-compact2.png?amount={{ $order->total_price }}&addInfo=VNPAY%20KARATE%20DH{{ $order->id }}&accountName=DAU%20CAO%20THANH" onerror="this.src='{{ asset('assets/clients/img/vietqr_mb.png') }}'" alt="Mã VNPAY QR Đơn #{{ $order->id }}" class="img-fluid rounded" style="max-height: 220px;">
                                        </div>
                                        <div class="small text-primary mt-2 font-weight-bold">
                                            <i class="fa-solid fa-qrcode mr-1"></i> Quét mã qua VNPAY hoặc App Ngân Hàng
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="font-weight-bold text-primary mb-2 d-flex align-items-center justify-content-between">
                                            <span><i class="fa-solid fa-shield-halved mr-1"></i> Cổng VNPAY-QR - Đơn hàng #{{ $order->id }}</span>
                                            <span class="badge badge-primary">VNPAY-QR</span>
                                        </h5>
                                        <div class="small text-muted mb-2">
                                            Quét mã tức thì qua hơn 40 ứng dụng ngân hàng: Vietcombank, BIDV, Vietinbank, Agribank, MB, Techcombank, VPBank, TPBank...
                                        </div>
                                        <ul class="list-unstyled small mb-3 text-dark" style="line-height: 2;">
                                            <li><strong>Số tiền:</strong> <span class="font-weight-bold text-danger font-size-16">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span></li>
                                            <li><strong>Cú pháp thanh toán:</strong> <span class="badge badge-info text-white font-weight-bold">VNPAY KARATE DH{{ $order->id }}</span></li>
                                        </ul>
                                        <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" data-toggle="collapse" data-target="#confirmVnpayCollapse">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Đã thanh toán VNPAY? Gửi mã giao dịch
                                        </button>
                                    </div>
                                </div>
                                <div class="collapse mt-3 pt-3 border-top" id="confirmVnpayCollapse">
                                    <form action="{{ route('orders.confirm-payment', $order->id) }}" method="POST" class="p-3 bg-light rounded border">
                                        @csrf
                                        <h6 class="font-weight-bold text-primary mb-2">Xác nhận giao dịch VNPAY</h6>
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Mã giao dịch / Mã tham chiếu VNPAY:</label>
                                            <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Nhập mã giao dịch..." required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary font-weight-bold">Gửi xác nhận</button>
                                    </form>
                                </div>
                            </div>

                        <!-- 3. Hướng dẫn thanh toán ZALOPAY -->
                        @elseif(str_contains($pm, 'ZaloPay'))
                            <div class="alert p-3 mb-4 bg-white rounded shadow-sm" style="border: 2px solid #008fe5;">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <div class="p-2 border rounded bg-white shadow-sm d-inline-block" style="border-color: #bae6fd !important;">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=https://zalopay.vn/pay?phone=0967137200&amount={{ $order->total_price }}&note=KARATE%20DH{{ $order->id }}" alt="Mã ZaloPay QR" class="img-fluid rounded" style="max-height: 220px;">
                                        </div>
                                        <div class="small mt-2 font-weight-bold" style="color: #008fe5;">
                                            <i class="fa-solid fa-camera mr-1"></i> Mở Zalo / ZaloPay quét mã
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h5 class="font-weight-bold mb-2" style="color: #008fe5;">
                                            <i class="fa-solid fa-wallet mr-1"></i> Thanh toán Ví ZaloPay - Đơn hàng #{{ $order->id }}
                                        </h5>
                                        <ul class="list-unstyled small mb-3 text-dark" style="line-height: 2;">
                                            <li><strong>Chủ ví ZaloPay:</strong> <span class="text-uppercase font-weight-bold">ĐẬU CAO THÀNH</span></li>
                                            <li><strong>Số ZaloPay:</strong> <span class="font-weight-bold text-info font-size-16">0967137200</span> <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 ml-2" onclick="copyText('0967137200', this)">Chép</button></li>
                                            <li><strong>Số tiền:</strong> <span class="font-weight-bold text-danger font-size-16">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span></li>
                                            <li><strong>Nội dung:</strong> <span class="badge badge-warning text-dark font-weight-bold">KARATE DH{{ $order->id }}</span></li>
                                        </ul>
                                        <button type="button" class="btn btn-sm btn-outline-info font-weight-bold" data-toggle="collapse" data-target="#confirmZaloCollapse">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Đã chuyển ZaloPay? Gửi mã xác nhận
                                        </button>
                                    </div>
                                </div>
                                <div class="collapse mt-3 pt-3 border-top" id="confirmZaloCollapse">
                                    <form action="{{ route('orders.confirm-payment', $order->id) }}" method="POST" class="p-3 bg-light rounded border">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Mã giao dịch ZaloPay:</label>
                                            <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Mã giao dịch..." required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-info font-weight-bold">Gửi xác nhận</button>
                                    </form>
                                </div>
                            </div>

                        <!-- 4. Hướng dẫn chuyển khoản VIETQR MBBANK -->
                        @elseif(str_contains($pm, 'VietQR') || str_contains($pm, 'Banking'))
                            <div class="alert alert-danger border-danger p-3 mb-4 bg-white rounded shadow-sm" style="border: 2px solid #ef4444;">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <img src="https://img.vietqr.io/image/MB-04223320042004-compact2.png?amount={{ $order->total_price }}&addInfo=KARATE%20DH{{ $order->id }}&accountName=DAU%20CAO%20THANH" onerror="this.src='{{ asset('assets/clients/img/vietqr_mb.png') }}'" alt="Mã VietQR MBBank" class="img-fluid rounded border p-1" style="max-height: 220px;">
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="font-weight-bold text-danger mb-2">
                                            <i class="fa fa-qrcode mr-1"></i> Quét mã VietQR MBBank để hoàn tất đơn hàng #{{ $order->id }}
                                        </h6>
                                        <ul class="list-unstyled small mb-2 text-dark" style="line-height: 1.8;">
                                            <li><strong>Ngân hàng:</strong> MBBank (Ngân hàng TMCP Quân Đội)</li>
                                            <li><strong>Chủ tài khoản:</strong> DAU CAO THANH</li>
                                            <li><strong>Số tài khoản:</strong> <span class="font-weight-bold text-danger font-size-16">04223320042004</span> <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 ml-1" onclick="copyText('04223320042004', this)">Chép STK</button></li>
                                            <li><strong>Số tiền:</strong> <span class="font-weight-bold text-danger">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span></li>
                                            <li><strong>Nội dung chuyển khoản:</strong> <span class="badge badge-warning text-dark font-weight-bold">KARATE DH{{ $order->id }}</span> <button type="button" class="btn btn-sm btn-outline-dark py-0 px-2 ml-1" onclick="copyText('KARATE DH{{ $order->id }}', this)">Chép nội dung</button></li>
                                        </ul>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold" data-toggle="collapse" data-target="#confirmBankCollapse">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Đã chuyển khoản? Gửi mã giao dịch ngân hàng
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse mt-3 pt-3 border-top" id="confirmBankCollapse">
                                    <form action="{{ route('orders.confirm-payment', $order->id) }}" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded border">
                                        @csrf
                                        <h6 class="font-weight-bold text-danger mb-2">Gửi mã giao dịch chuyển khoản MBBank</h6>
                                        <div class="row">
                                            <div class="col-md-6 form-group mb-2">
                                                <label class="small font-weight-bold">Mã giao dịch / Số FT:</label>
                                                <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Ví dụ: FT23120...">
                                            </div>
                                            <div class="col-md-6 form-group mb-2">
                                                <label class="small font-weight-bold">Ghi chú (Tên ngân hàng chuyển...):</label>
                                                <input type="text" name="note" class="form-control form-control-sm" placeholder="Ghi chú thêm...">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Ảnh xác nhận thanh toán:</label>
                                            <input type="file" name="payment_proof" class="form-control-file" accept="image/jpeg,image/png,image/webp">
                                            <small class="text-muted">Nhập mã giao dịch hoặc chọn ảnh biên lai (tối đa 5MB).</small>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-bold">Gửi xác nhận</button>
                                    </form>
                                </div>
                            </div>

                        <!-- 5. Thẻ quốc tế -->
                        @elseif(str_contains($pm, 'Thẻ') || str_contains($pm, 'CARD'))
                            <div class="alert p-3 mb-4 bg-white rounded shadow-sm" style="border: 2px solid #334155;">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-credit-card fa-2x text-primary mr-3"></i>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-1">Thanh toán Thẻ quốc tế (Visa / MasterCard / JCB)</h6>
                                        <p class="small text-muted mb-0">Đơn hàng đã được tiếp nhận qua cổng xử lý thẻ quốc tế an toàn chuẩn PCI-DSS. Mã xác nhận thẻ: <strong>{{ $order->payment->transaction_id ?? 'Đang khởi tạo' }}</strong></p>
                                    </div>
                                </div>
                            </div>

                        <!-- 6. COD -->
                        @else
                            <div class="alert alert-secondary p-3 mb-4 bg-light rounded shadow-sm border">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-truck-ramp-box fa-2x text-danger mr-3"></i>
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-1">Thanh toán tiền mặt khi nhận hàng (COD)</h6>
                                        <p class="small text-muted mb-0">Nhân viên giao hàng sẽ liên hệ trước khi phát hàng. Quý khách vui lòng kiểm tra võ phục và chuẩn bị số tiền <strong>{{ number_format($order->total_price, 0, ',', '.') }} ₫</strong> khi nhận.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Items Table -->
                    <h5 class="font-weight-bold mb-3 pb-2 border-bottom">Danh sách sản phẩm võ thuật</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $idx => $item)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $ordImg = $item->product && $item->product->image ? $item->product->image : ($item->product && $item->product->images->first() ? $item->product->images->first()->image : 'assets/clients/img/karate/vo-phuc-rikaido.png');
                                            @endphp
                                            <img src="{{ asset($ordImg) }}" class="border rounded mr-2" style="width: 45px; height: 45px; object-fit: contain; background: #fafafa;">
                                            <div>
                                                <strong>{{ $item->product->name ?? 'Sản phẩm võ thuật' }}</strong>
                                                <div class="mt-1">
                                                    @if($item->color)
                                                        <span class="badge badge-light border text-danger" style="font-size: 11px;">Màu: {{ $item->color }}</span>
                                                    @endif
                                                    @if($item->size)
                                                        <span class="badge badge-light border text-dark" style="font-size: 11px;">Size: {{ $item->size }}</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted">{{ $item->product->category->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                                    <td class="text-center font-weight-bold">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                    <td class="text-right font-weight-bold text-danger">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Tổng thanh toán:</td>
                                    <td class="text-right font-weight-bold text-danger font-size-18">{{ number_format($order->total_price, 0, ',', '.') }} ₫</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('account') }}" class="btn btn-outline-dark">
                            <i class="fa fa-arrow-left mr-1"></i> Quay lại tài khoản
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-danger font-weight-bold">
                            Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ORDER DETAIL AREA END -->

@push('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            var original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Đã chép!';
            btn.classList.add('btn-success');
            setTimeout(function() {
                btn.innerHTML = original;
                btn.classList.remove('btn-success');
            }, 2000);
        });
    }
</script>
@endpush
@endsection
