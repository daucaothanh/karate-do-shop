@extends('layouts.khach_hang')

@section('title', $product->name . ' | Karate-Do Shop')

@section('breadcrumb', 'Chi tiết sản phẩm')

@push('styles')
<style>
    /* Color and Size Selector Styles */
    .karate-option-wrap {
        margin-bottom: 20px;
    }
    .karate-option-label {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .karate-color-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        border: 2px solid #e2e8f0;
        background: #ffffff;
        color: #334155;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        outline: none !important;
    }
    .karate-color-chip:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }
    .karate-color-chip.active {
        border-color: #d32f2f !important;
        background: #fff5f5 !important;
        color: #d32f2f !important;
        font-weight: 700;
        box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
    }
    .karate-color-dot {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .karate-size-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 52px;
        height: 40px;
        padding: 0 14px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        outline: none !important;
    }
    .karate-size-chip:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
        transform: translateY(-1px);
    }
    .karate-size-chip.active {
        border-color: #d32f2f !important;
        background: #fee2e2 !important;
        color: #d32f2f !important;
        font-weight: 700;
        box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
    }

    /* Custom Quantity Stepper - Guaranteed horizontal alignment */
    .karate-qty-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 24px;
    }
    .karate-qty-box {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center !important;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        width: 140px !important;
        height: 44px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .karate-qty-btn {
        width: 42px !important;
        height: 44px !important;
        border: none !important;
        background: #f8fafc !important;
        color: #1e293b !important;
        font-size: 18px !important;
        font-weight: 700 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        padding: 0 !important;
        margin: 0 !important;
        user-select: none !important;
        outline: none !important;
    }
    .karate-qty-btn:hover {
        background: #e2e8f0 !important;
        color: #d32f2f !important;
    }
    .karate-qty-input {
        width: 56px !important;
        height: 44px !important;
        border: none !important;
        border-left: 1px solid #e2e8f0 !important;
        border-right: 1px solid #e2e8f0 !important;
        text-align: center !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        outline: none !important;
        box-shadow: none !important;
    }
    /* Hide native number spinners */
    .karate-qty-input::-webkit-outer-spin-button,
    .karate-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .karate-qty-input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endpush

@section('content')
<!-- SHOP DETAILS AREA START -->
<div class="ltn__shop-details-area pb-85 pt-40">
    <div class="container">
        <div class="row">
            <!-- Left Column: Product Gallery -->
            <div class="col-lg-6 col-md-12 mb-40">
                <div class="ltn__shop-details-img-gallery border rounded p-3 text-center bg-white shadow-sm">
                    <div class="main-image mb-3" style="max-height: 420px; display: flex; align-items: center; justify-content: center; background: #fafafa; border-radius: 8px; overflow: hidden; padding: 15px;">
                        @php
                            $mainImg = $product->image ?: ($product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                        @endphp
                        <img id="mainProductImage" src="{{ asset($mainImg) }}" alt="{{ $product->name }}" style="max-height: 380px; width: 100%; object-fit: contain;">
                    </div>

                    @php
                        $allImages = collect([$product->image])->merge($product->images->pluck('image'))->filter()->unique();
                    @endphp
                    @if($allImages->count() > 1)
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            @foreach($allImages as $img)
                                <img src="{{ asset($img) }}" class="border rounded p-1 thumb-gallery cursor-pointer" style="width: 70px; height: 70px; object-fit: contain; cursor: pointer; background: #fff;" onclick="document.getElementById('mainProductImage').src = this.src;">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Product Info & Purchase -->
            <div class="col-lg-6 col-md-12 mb-40">
                <div class="modal-product-info shop-details-info pl-0 pl-lg-4">
                    <div class="product-ratting mb-2 text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span class="text-muted small ml-2 font-weight-normal">(Đánh giá 5.0 sao - Hàng chính hãng)</span>
                    </div>

                    <h3 class="font-weight-bold text-dark mb-2">{{ $product->name }}</h3>

                    <div class="mb-3">
                        <span class="badge badge-danger p-2 mr-2">
                            <i class="fa fa-tag mr-1"></i> {{ $product->category->name ?? 'Võ thuật Karate-Do' }}
                        </span>
                        @if($product->stock > 0)
                            <span class="badge badge-success p-2"><i class="fa fa-check-circle mr-1"></i> Còn hàng ({{ $product->stock }} {{ $product->unit }})</span>
                        @else
                            <span class="badge badge-secondary p-2">Tạm hết hàng</span>
                        @endif
                    </div>

                    <div class="modal-product-price mb-4 p-3 bg-light rounded">
                        <span class="text-danger font-weight-bold" style="font-size: 28px;">
                            {{ number_format($product->price, 0, ',', '.') }} ₫
                        </span>
                        <span class="text-muted"> / {{ $product->unit }}</span>
                        <div class="small text-success mt-1"><i class="fa fa-truck mr-1"></i> Miễn phí vận chuyển toàn quốc cho đơn hàng từ 500.000₫</div>
                    </div>

                    <div class="modal-product-brief mb-4 text-muted" style="line-height: 1.8;">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <hr>

                    <!-- Add to Cart Form -->
                    <form method="POST" action="{{ route('cart.store') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Color Selector -->
                        @if($product->colors)
                            @php
                                $colorList = array_values(array_filter(array_map('trim', explode(',', $product->colors))));
                                $colorMap = [
                                    'Đỏ' => '#ef4444',
                                    'Xanh dương' => '#2563eb',
                                    'Xanh' => '#2563eb',
                                    'Trắng' => '#ffffff',
                                    'Đen' => '#0f172a',
                                    'Vàng' => '#eab308',
                                    'Cam' => '#ea580c',
                                    'Nâu' => '#78350f',
                                    'Xanh lá' => '#16a34a',
                                    'Tím' => '#9333ea',
                                ];
                                $defaultColor = $colorList[0] ?? '';
                            @endphp
                            @if(count($colorList) > 0)
                                <div class="karate-option-wrap">
                                    <label class="karate-option-label">
                                        <i class="fas fa-palette text-danger"></i> Chọn Màu sắc:
                                        <span id="selectedColorLabel" class="text-danger ml-1 font-weight-bold">{{ $defaultColor }}</span>
                                    </label>
                                    <input type="hidden" name="color" id="selectedColorInput" value="{{ $defaultColor }}">
                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                        @foreach($colorList as $idx => $c)
                                            @php
                                                $bgHex = $colorMap[$c] ?? '#cbd5e1';
                                                $isWhite = (mb_strtolower($c) === 'trắng');
                                            @endphp
                                            <button type="button" 
                                                    class="karate-color-chip {{ $idx === 0 ? 'active' : '' }}" 
                                                    data-color="{{ $c }}"
                                                    onclick="selectColor('{{ $c }}', this)">
                                                <span class="karate-color-dot" style="background: {{ $bgHex }}; {{ $isWhite ? 'border: 1px solid #cbd5e1;' : '' }}"></span>
                                                <span>{{ $c }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Size Selector -->
                        @if($product->sizes)
                            @php
                                $sizeList = array_values(array_filter(array_map('trim', explode(',', $product->sizes))));
                                $defaultSize = $sizeList[0] ?? '';
                                $sizeStocks = $product->size_stocks ?? [];
                            @endphp
                            @if(count($sizeList) > 0)
                                <div class="karate-option-wrap">
                                    <label class="karate-option-label">
                                        <i class="fas fa-ruler-combined text-success"></i> Chọn Kích thước / Size:
                                        <span id="selectedSizeLabel" class="text-danger ml-1 font-weight-bold">{{ $defaultSize }}</span>
                                    </label>
                                    <input type="hidden" name="size" id="selectedSizeInput" value="{{ $defaultSize }}">
                                    <div class="d-flex flex-wrap" style="gap: 10px;">
                                        @foreach($sizeList as $idx => $s)
                                            <button type="button" 
                                                    class="karate-size-chip {{ $idx === 0 ? 'active' : '' }}" 
                                                    data-size="{{ $s }}"
                                                    data-stock="{{ array_key_exists($s, $sizeStocks) ? (int) $sizeStocks[$s] : $product->stock }}"
                                                    onclick="selectSize('{{ $s }}', this)">
                                                {{ $s }} <small class="d-block text-muted">({{ array_key_exists($s, $sizeStocks) ? (int) $sizeStocks[$s] : $product->stock }})</small>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- Quantity Selector -->
                        <div class="karate-qty-wrapper">
                            <label class="font-weight-bold mb-0 text-dark" style="font-size: 15px;">Số lượng:</label>
                            <div class="karate-qty-box">
                                <button type="button" class="karate-qty-btn" id="btnQtyMinus" onclick="decreaseQty()">-</button>
                                <input id="quantityInput" type="number" name="quantity" class="karate-qty-input" value="1" min="1" max="{{ $product->stock }}">
                                <button type="button" class="karate-qty-btn" id="btnQtyPlus" onclick="increaseQty()">+</button>
                            </div>
                            <span class="text-muted small" id="stockAvailability">({{ $product->stock }} {{ $product->unit }} tổng có sẵn)</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger btn-lg flex-grow-1 font-weight-bold mr-2" {{ $product->stock <= 0 ? 'disabled' : '' }} style="background: #d32f2f; border-radius: 8px; font-size: 15px; padding: 14px 20px;">
                                <i class="fa fa-shopping-cart mr-2"></i> THÊM VÀO GIỎ HÀNG
                            </button>

                            <button type="submit" formaction="{{ route('cart.store') }}" name="buy_now" value="1" class="btn btn-dark btn-lg font-weight-bold" {{ $product->stock <= 0 ? 'disabled' : '' }} style="border-radius: 8px; font-size: 15px; padding: 14px 24px;">
                                MUA NGAY
                            </button>
                        </div>
                    </form>

                    <!-- Commitments -->
                    <div class="border rounded p-3 bg-white">
                        <div class="row text-muted small">
                            <div class="col-6 mb-2"><i class="fa fa-shield-alt text-danger mr-1"></i> 100% Hàng chính hãng</div>
                            <div class="col-6 mb-2"><i class="fa fa-sync text-danger mr-1"></i> Đổi trả trong 7 ngày</div>
                            <div class="col-6"><i class="fa fa-truck text-danger mr-1"></i> Giao hàng hỏa tốc</div>
                            <div class="col-6"><i class="fa fa-phone text-danger mr-1"></i> Hỗ trợ tư vấn size 24/7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs: Size Chart & Guide -->
        <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2 mt-40">
            <div class="ltn__shop-details-tab-menu">
                <div class="nav">
                    <a class="active show" data-bs-toggle="tab" data-toggle="tab" href="#liton_tab_details_1_1">BẢNG HƯỚNG DẪN CHỌN SIZE VÕ PHỤC</a>
                    <a data-bs-toggle="tab" data-toggle="tab" href="#liton_tab_details_1_2" class="">CHÍNH SÁCH BẢO HÀNH & ĐỔI TRẢ</a>
                </div>
            </div>
            <div class="tab-content border p-4 bg-white rounded-bottom">
                <div class="tab-pane fade active show" id="liton_tab_details_1_1">
                    <h5 class="font-weight-bold mb-3">Bảng quy chuẩn kích thước võ phục Karate-Do theo chiều cao & cân nặng:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th>Size số</th>
                                    <th>Chiều cao võ sinh (cm)</th>
                                    <th>Cân nặng phù hợp (kg)</th>
                                    <th>Độ tuổi khuyến nghị</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><strong>Size 00 (Số 0)</strong></td><td>110cm - 120cm</td><td>18kg - 25kg</td><td>Thiếu nhi (5 - 7 tuổi)</td></tr>
                                <tr><td><strong>Size 01 (Số 1)</strong></td><td>120cm - 130cm</td><td>25kg - 32kg</td><td>Thiếu nhi (8 - 10 tuổi)</td></tr>
                                <tr><td><strong>Size 02 (Số 2)</strong></td><td>130cm - 140cm</td><td>32kg - 40kg</td><td>Thiếu niên (11 - 13 tuổi)</td></tr>
                                <tr><td><strong>Size 03 (Số 3)</strong></td><td>140cm - 150cm</td><td>40kg - 50kg</td><td>Học sinh / Người lớn form nhỏ</td></tr>
                                <tr><td><strong>Size 04 (Số 4)</strong></td><td>150cm - 160cm</td><td>50kg - 62kg</td><td>Người lớn chuẩn</td></tr>
                                <tr><td><strong>Size 05 (Số 5)</strong></td><td>160cm - 170cm</td><td>62kg - 75kg</td><td>Người lớn chuẩn</td></tr>
                                <tr><td><strong>Size 06 (Số 6)</strong></td><td>170cm - 180cm</td><td>75kg - 88kg</td><td>Người lớn cao to</td></tr>
                                <tr><td><strong>Size 07 (Số 7)</strong></td><td>180cm - 190cm</td><td>> 88kg</td><td>Võ sĩ ngoại cỡ</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="liton_tab_details_1_2">
                    <h5 class="font-weight-bold mb-2">Chính sách cam kết chất lượng từ Shop Karate-Do:</h5>
                    <ul class="text-muted pl-3">
                        <li class="mb-2">Hỗ trợ đổi size miễn phí trong vòng 7 ngày nếu võ phục hoặc đai, giáp không vừa vặn.</li>
                        <li class="mb-2">Bảo hành đường may và chất liệu vải 12 tháng với tất cả dòng võ phục cao cấp Shureido, Tokaido.</li>
                        <li class="mb-2">Đền bù 200% nếu phát hiện sản phẩm không đúng chất lượng cam kết.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SHOP DETAILS AREA END -->

@push('scripts')
<script>
    function selectColor(colorName, btn) {
        var input = document.getElementById('selectedColorInput');
        var label = document.getElementById('selectedColorLabel');
        if (input) input.value = colorName;
        if (label) label.innerText = colorName;

        var allChips = document.querySelectorAll('.karate-color-chip');
        allChips.forEach(function(chip) {
            chip.classList.remove('active');
        });
        if (btn) btn.classList.add('active');
    }

    function selectSize(sizeName, btn) {
        var input = document.getElementById('selectedSizeInput');
        var label = document.getElementById('selectedSizeLabel');
        if (input) input.value = sizeName;
        if (label) label.innerText = sizeName;

        var allChips = document.querySelectorAll('.karate-size-chip');
        allChips.forEach(function(chip) {
            chip.classList.remove('active');
        });
        if (btn) btn.classList.add('active');
        updateSizeStock(btn);
    }

    function updateSizeStock(btn) {
        var quantity = document.getElementById('quantityInput');
        var availability = document.getElementById('stockAvailability');
        var submitButtons = document.querySelectorAll('button[type="submit"]');
        var stock = btn ? parseInt(btn.getAttribute('data-stock'), 10) || 0 : parseInt(quantity.getAttribute('max'), 10) || 0;
        quantity.max = stock;
        if (parseInt(quantity.value, 10) > stock) quantity.value = Math.max(stock, 1);
        if (availability) availability.innerText = '(' + stock + ' {{ $product->unit }} size này có sẵn)';
        submitButtons.forEach(function (button) { button.disabled = stock < 1; });
    }

    function decreaseQty() {
        var q = document.getElementById('quantityInput');
        if (!q) return;
        var val = parseInt(q.value) || 1;
        if (val > 1) {
            q.value = val - 1;
        }
    }

    function increaseQty() {
        var q = document.getElementById('quantityInput');
        if (!q) return;
        var max = parseInt(q.getAttribute('max')) || 999;
        var val = parseInt(q.value) || 1;
        if (val < max) {
            q.value = val + 1;
        }
    }

    // Attach native DOM listeners when document is ready
    document.addEventListener('DOMContentLoaded', function () {
        var qtyInput = document.getElementById('quantityInput');
        if (qtyInput) {
            qtyInput.addEventListener('change', function () {
                var max = parseInt(this.getAttribute('max')) || 999;
                var val = parseInt(this.value) || 1;
                if (val < 1) this.value = 1;
                if (val > max) this.value = max;
            });
            var firstSize = document.querySelector('.karate-size-chip.active');
            if (firstSize) updateSizeStock(firstSize);
        }
    });
</script>
@endpush
@endsection