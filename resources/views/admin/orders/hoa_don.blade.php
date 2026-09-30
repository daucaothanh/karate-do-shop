<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hóa đơn đơn hàng #{{ $order->id }} - KARATE-DO SHOP</title>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #d32f2f;
            letter-spacing: 1px;
        }
        @media print {
            body { background: #fff; }
            .invoice-container { box-shadow: none; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- Action Bar (Not printed) -->
    <div class="no-print d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại đơn hàng
        </a>
        <button onclick="window.print()" class="btn btn-danger btn-sm">
            <i class="fa-solid fa-print mr-1"></i> In hóa đơn / Phiếu giao hàng
        </button>
    </div>

    <!-- Invoice Header -->
    <div class="row invoice-header align-items-center">
        <div class="col-sm-6">
            <div class="logo-text d-flex align-items-center mb-1">
                <img src="{{ asset('assets/admin/images/logo.png') }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; margin-right: 8px;">
                <span>KARATE-DO SHOP</span>
            </div>
            <div class="small text-muted mt-1">Chuyên cung cấp võ phục & trang thiết bị võ thuật chất lượng cao</div>
            <div class="small text-muted">Địa chỉ: Ngũ Hành Sơn, Đà Nẵng | Hotline: 0967.137.200</div>
        </div>
        <div class="col-sm-6 text-sm-right mt-3 mt-sm-0">
            <h4 class="font-weight-bold text-dark mb-0">HÓA ĐƠN BÁN HÀNG</h4>
            <div class="text-danger font-weight-bold">MÃ ĐƠN: #{{ $order->id }}</div>
            <div class="small text-muted">Ngày lập: {{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <!-- Customer & Order Info -->
    <div class="row mb-4">
        <div class="col-sm-6">
            <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">THÔNG TIN KHÁCH HÀNG:</h6>
            <div><strong>Họ và tên:</strong> {{ $order->shippingAddress->fullname ?? ($order->user->name ?? 'N/A') }}</div>
            <div><strong>Số điện thoại:</strong> {{ $order->shippingAddress->phone ?? ($order->user->phone_number ?? 'N/A') }}</div>
            <div><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</div>
            <div><strong>Địa chỉ giao:</strong> {{ $order->shippingAddress->address ?? '' }}, {{ $order->shippingAddress->city ?? '' }}</div>
        </div>
        <div class="col-sm-6 mt-3 mt-sm-0">
            <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">THÔNG TIN GIAO NHẬN:</h6>
            <div><strong>Hình thức thanh toán:</strong> {{ $order->payment->payment_method ?? 'COD (Thu tiền khi nhận hàng)' }}</div>
            @if($order->payment && $order->payment->transaction_id)
                <div><strong>Mã giao dịch / Thẻ:</strong> {{ $order->payment->transaction_id }}</div>
            @endif
            <div><strong>Trạng thái thanh toán:</strong> {{ ($order->payment && $order->payment->status === 'completed') ? 'Đã thanh toán' : 'Chưa thanh toán' }}</div>
            <div><strong>Ghi chú:</strong> Kiểm tra võ phục, size đai trước khi nhận hàng.</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="table table-bordered mb-4">
        <thead class="bg-light">
            <tr>
                <th width="40" class="text-center">STT</th>
                <th>Tên sản phẩm võ thuật</th>
                <th width="80" class="text-center">ĐVT</th>
                <th width="90" class="text-center">SL</th>
                <th width="130" class="text-right">Đơn giá</th>
                <th width="150" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->product->name ?? 'Sản phẩm võ thuật' }}</strong>
                    @if($item->color || $item->size)
                        <div class="small text-muted">
                            @if($item->color) <span>Màu: {{ $item->color }}</span> @endif
                            @if($item->color && $item->size) | @endif
                            @if($item->size) <span>Size: {{ $item->size }}</span> @endif
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $item->product->unit ?? 'Bộ' }}</td>
                <td class="text-center font-weight-bold">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                <td class="text-right font-weight-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} ₫</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right font-weight-bold bg-light">TỔNG CỘNG TIỀN HÀNG:</td>
                <td class="text-right font-weight-bold text-danger font-size-18">
                    {{ number_format($order->total_price, 0, ',', '.') }} ₫
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Signature Row -->
    <div class="row text-center mt-5 pt-3">
        <div class="col-4">
            <strong>Người nhận hàng</strong>
            <div class="small text-muted">(Ký, ghi rõ họ tên)</div>
        </div>
        <div class="col-4">
            <strong>Nhân viên giao hàng</strong>
            <div class="small text-muted">(Ký, ghi rõ họ tên)</div>
        </div>
        <div class="col-4">
            <strong>Đại diện Shop Karate-Do</strong>
            <div class="small text-muted">(Ký, đóng dấu)</div>
        </div>
    </div>
</div>

</body>
</html>
