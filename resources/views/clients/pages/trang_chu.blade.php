@extends('layouts.khach_hang_trang_chu')

@section('title', 'Trang chủ | Cửa hàng Võ phục & Dụng cụ Karate-Do')

@push('styles')
<style>
    .home-call-to-action {
        overflow: hidden;
    }
    .home-call-to-action .ltn__call-to-4-img-1,
    .home-call-to-action .ltn__call-to-4-img-2 {
        max-width: none;
        z-index: 1;
    }
    .home-call-to-action .ltn__call-to-4-img-1 {
        width: min(18vw, 260px);
        left: 3%;
    }
    .home-call-to-action .ltn__call-to-4-img-2 {
        width: min(22vw, 320px);
        right: 3%;
    }
    .home-call-to-action .call-to-action-inner-4 {
        z-index: 2;
    }
    @media (max-width: 991px) {
        .home-call-to-action .ltn__call-to-4-img-1,
        .home-call-to-action .ltn__call-to-4-img-2 {
            display: none;
        }
    }

    /* Modern Karate Product Card */
    .karate-prod-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        position: relative;
    }
    .karate-prod-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.09);
        border-color: #cbd5e1;
    }
    .karate-img-container {
        position: relative;
        height: 230px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .karate-img-container img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }
    .karate-prod-card:hover .karate-img-container img {
        transform: scale(1.08);
    }
    .karate-cat-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .karate-stock-tag {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .karate-card-body {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }
    .karate-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.45;
        height: 42px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .karate-card-title a {
        color: #0f172a;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }
    .karate-card-title a:hover {
        color: #d32f2f;
    }
    .karate-price-wrap {
        margin-top: 10px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
    }
    .karate-price-left {
        display: flex;
        align-items: baseline;
        min-width: 0;
    }
    .karate-main-price {
        font-size: 18px;
        font-weight: 800;
        color: #d32f2f;
        display: inline-block;
        white-space: nowrap;
    }
    .karate-unit-text {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-left: 3px;
        white-space: nowrap;
    }
    .karate-stock-right {
        flex-shrink: 0;
    }
    .karate-stock-qty {
        display: inline-flex;
        align-items: center;
        font-size: 11.5px;
        color: #059669;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 2.5px 7px;
        border-radius: 6px;
        font-weight: 500;
        white-space: nowrap;
        line-height: 1.35;
    }
    .karate-stock-qty strong {
        color: #047857;
        font-weight: 800;
        margin-left: 2px;
    }
    .karate-stock-qty.out-of-stock {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fecaca;
        font-weight: 700;
    }
    .btn-add-cart-main {
        background: #d32f2f;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
        border: none;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .btn-add-cart-main:hover {
        background: #b71c1c;
        box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
    }
    .btn-wishlist-icon {
        width: 40px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .btn-wishlist-icon:hover {
        background: #fee2e2;
        color: #d32f2f;
        border-color: #fca5a5;
    }
</style>
@endpush

@section('content')

<!-- SLIDER AREA START (slider-3) -->
<div class="ltn__slider-area ltn__slider-3 section-bg-1">
    <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
        <!-- Slide 1 -->
        <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
            data-bg="{{ asset('assets/clients/img/slider/13.png') }}">
            <div class="ltn__slide-item-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info">
                                <div class="slide-item-info-inner ltn__slide-animation">
                                    <h6 class="slide-sub-title animated text-danger font-weight-bold">
                                        <i class="fa-solid fa-shield-halved mr-1"></i> Trang thiết bị võ thuật chính hãng 100%
                                    </h6>
                                    <h2 class="slide-title animated">“Karategi không chỉ là võ phục, mà là biểu tượng của kỷ luật và danh dự.”</h2>
                                    <div class="slide-brief animated">
                                        <p>Khoác lên mình bộ võ phục Karate-Do không chỉ là bắt đầu một buổi tập, mà là bước vào một hành trình rèn luyện tinh thần, thể chất và trí tuệ.</p>
                                    </div>
                                    <div class="btn-wrapper animated">
                                        <a href="{{ route('products.index') }}" class="theme-btn-1 btn btn-effect-1 text-uppercase">
                                            Khám phá Sản phẩm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-image"
            data-bg="{{ asset('assets/clients/img/slider/14.png') }}">
            <div class="ltn__slide-item-inner text-right text-end">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 align-self-center">
                            <div class="slide-item-info">
                                <div class="slide-item-info-inner ltn__slide-animation">
                                    <h6 class="slide-sub-title ltn__secondary-color animated">
                                        “Võ phục trắng không nhuộm màu chiến thắng,<br>
                                        mà thấm đẫm mồ hôi của người không bỏ cuộc.”
                                    </h6>
                                    <h2 class="slide-title animated">
                                        “Khoác lên mình võ phục <br>là khoác lên mình trách nhiệm với chính con đường đã chọn.”
                                    </h2>
                                    <div class="slide-brief animated">
                                        <p>Bộ võ phục tưởng chừng đơn giản ấy đã đồng hành cùng biết bao buổi tập, những lần thất bại, những khoảnh khắc mệt mỏi và cả những chiến thắng.</p>
                                    </div>
                                    <div class="btn-wrapper animated">
                                        <a href="{{ route('products.index') }}" class="theme-btn-1 btn btn-effect-1 text-uppercase">
                                            Khám phá Sản phẩm
                                        </a>
                                        <a href="{{ url('/about') }}" class="btn btn-transparent btn-effect-3">
                                            TÌM HIỂU THÊM
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- SLIDER AREA END -->

<!-- BANNER AREA START -->
<div class="ltn__banner-area mt-120 mb-90">
    <div class="container">
        <div class="row ltn__custom-gutter--- justify-content-center">
            <div class="col-lg-6 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img banner-square">
                        <a href="{{ route('products.index') }}"><img src="{{ asset('assets/clients/img/banner/1.png') }}" alt="Võ phục Rikaido"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="ltn__banner-item">
                            <div class="ltn__banner-img">
                                <a href="{{ route('products.index') }}"><img src="{{ asset('assets/clients/img/banner/2.png') }}" alt="Bộ đồ đấu Bushido"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="ltn__banner-item">
                            <div class="ltn__banner-img">
                                <a href="{{ route('products.index') }}"><img src="{{ asset('assets/clients/img/banner/3.png') }}" alt="Võ phục Fujido"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BANNER AREA END -->

<!-- CATEGORY AREA START -->
<div class="ltn__category-area section-bg-1-- ltn__primary-bg before-bg-1 bg-image bg-overlay-theme-black-5--0 pt-115 pb-90"
    data-bg="{{ asset('assets/clients/img/banner/11.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h6 class="section-subtitle ltn__secondary-color font-weight-bold">// TRANG THIẾT BỊ VÕ THUẬT //</h6>
                    <h1 class="section-title white-color font-weight-bold">Danh Mục Sản Phẩm</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($categories as $cat)
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 mb-4">
                    <div class="ltn__category-item ltn__category-item-3 text-center" style="background: #ffffff; border-radius: 12px; padding: 22px 14px; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.3s ease;">
                        <div class="ltn__category-item-img mb-3" style="height: 90px; display: flex; align-items: center; justify-content: center;">
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}">
                                <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" style="max-height: 80px; max-width: 100%; object-fit: contain;">
                            </a>
                        </div>
                        <div class="ltn__category-item-name">
                            <h5 class="mb-1" style="font-size: 14.5px; font-weight: 700;">
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-dark">
                                    {{ $cat->name }}
                                </a>
                            </h5>
                            <h6 class="text-muted small mb-0 font-weight-normal">({{ $cat->products_count ?? $cat->products()->count() }} sản phẩm)</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- CATEGORY AREA END -->

<!-- PRODUCT TAB AREA START (product-item-3) -->
<div class="ltn__product-tab-area ltn__product-gutter pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center mb-4">
                    <h6 class="section-subtitle ltn__secondary-color font-weight-bold">// ĐA DẠNG SẢN PHẨM //</h6>
                    <h1 class="section-title font-weight-bold">Sản Phẩm Võ Thuật</h1>
                </div>

                <!-- Tabs navigation -->
                <div class="ltn__tab-menu ltn__tab-menu-2 ltn__tab-menu-top-right-- text-uppercase text-center mb-40">
                    <div class="nav">
                        <a class="active show" data-bs-toggle="tab" href="#liton_tab_all">Tất Cả</a>
                        @foreach($categories as $cat)
                            <a data-bs-toggle="tab" href="#liton_tab_{{ $cat->id }}">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>

                <!-- Tabs content -->
                <div class="tab-content">
                    <!-- Tab Tất Cả -->
                    <div class="tab-pane fade active show" id="liton_tab_all">
                        <div class="ltn__product-tab-content-inner">
                            <div class="row">
                                @forelse($allProducts->take(8) as $product)
                                    @php
                                        $imgSrc = $product->image ?: ($product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
                                        <div class="karate-prod-card">
                                            <div class="karate-img-container">
                                                <a href="{{ route('products.show', $product->slug) }}">
                                                    <img src="{{ asset($imgSrc) }}" alt="{{ $product->name }}">
                                                </a>
                                                <span class="karate-cat-tag">{{ $product->category->name ?? 'Karate-Do' }}</span>
                                                @if($product->stock > 0)
                                                    <span class="karate-stock-tag"><i class="fa-solid fa-check mr-1"></i> Sẵn hàng</span>
                                                @else
                                                    <span class="karate-stock-tag" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;"><i class="fa-solid fa-ban mr-1"></i> Hết hàng</span>
                                                @endif
                                            </div>
                                            <div class="karate-card-body">
                                                <div>
                                                    <div class="text-warning small mb-1">
                                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                        <span class="text-muted ml-1" style="font-size: 11px;">(5.0)</span>
                                                    </div>
                                                    <div class="karate-card-title">
                                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="karate-price-wrap">
                                                        <div class="karate-price-left">
                                                            <span class="karate-main-price">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                                            <span class="karate-unit-text">/ {{ $product->unit ?? 'Bộ' }}</span>
                                                        </div>
                                                        <div class="karate-stock-right">
                                                            @if($product->stock > 0)
                                                                <span class="karate-stock-qty" title="Số lượng còn lại trong kho">
                                                                    Còn: <strong>{{ $product->stock }}</strong>
                                                                </span>
                                                            @else
                                                                <span class="karate-stock-qty out-of-stock" title="Sản phẩm tạm hết hàng">
                                                                    Hết hàng
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <form method="POST" action="{{ route('cart.store') }}" class="flex-grow-1 mr-2">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit" class="btn-add-cart-main w-100">
                                                                <i class="fa-solid fa-cart-plus"></i>
                                                                <span>Thêm vào giỏ</span>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                                            @csrf
                                                            <button type="submit" class="btn-wishlist-icon" title="Thêm vào yêu thích">
                                                                <i class="far fa-heart"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted">
                                        Đang cập nhật sản phẩm võ thuật...
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tabs theo danh mục -->
                    @foreach($categories as $cat)
                        <div class="tab-pane fade" id="liton_tab_{{ $cat->id }}">
                            <div class="ltn__product-tab-content-inner">
                                <div class="row">
                                    @forelse($allProducts->where('category_id', $cat->id) as $product)
                                        @php
                                            $imgSrc = $product->image ?: ($product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                                        @endphp
                                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
                                            <div class="karate-prod-card">
                                                <div class="karate-img-container">
                                                    <a href="{{ route('products.show', $product->slug) }}">
                                                        <img src="{{ asset($imgSrc) }}" alt="{{ $product->name }}">
                                                    </a>
                                                    <span class="karate-cat-tag">{{ $cat->name }}</span>
                                                    @if($product->stock > 0)
                                                        <span class="karate-stock-tag"><i class="fa-solid fa-check mr-1"></i> Sẵn hàng</span>
                                                    @else
                                                        <span class="karate-stock-tag" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;"><i class="fa-solid fa-ban mr-1"></i> Hết hàng</span>
                                                    @endif
                                                </div>
                                                <div class="karate-card-body">
                                                    <div>
                                                        <div class="text-warning small mb-1">
                                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                            <span class="text-muted ml-1" style="font-size: 11px;">(5.0)</span>
                                                        </div>
                                                        <div class="karate-card-title">
                                                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="karate-price-wrap">
                                                            <div class="karate-price-left">
                                                                <span class="karate-main-price">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                                                <span class="karate-unit-text">/ {{ $product->unit ?? 'Bộ' }}</span>
                                                            </div>
                                                            <div class="karate-stock-right">
                                                                @if($product->stock > 0)
                                                                    <span class="karate-stock-qty" title="Số lượng còn lại trong kho">
                                                                        Còn: <strong>{{ $product->stock }}</strong>
                                                                    </span>
                                                                @else
                                                                    <span class="karate-stock-qty out-of-stock" title="Sản phẩm tạm hết hàng">
                                                                        Hết hàng
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <form method="POST" action="{{ route('cart.store') }}" class="flex-grow-1 mr-2">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="quantity" value="1">
                                                                <button type="submit" class="btn-add-cart-main w-100">
                                                                    <i class="fa-solid fa-cart-plus"></i>
                                                                    <span>Thêm vào giỏ</span>
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                                                @csrf
                                                                <button type="submit" class="btn-wishlist-icon" title="Thêm vào yêu thích">
                                                                    <i class="far fa-heart"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-4 text-muted">
                                            Chưa có sản phẩm trong danh mục này.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PRODUCT TAB AREA END -->

<!-- COUNTER UP AREA START -->
<div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70" data-bg="{{ asset('assets/clients/img/banner/6.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <h1><span class="counter">733</span><span class="counterUp-icon">+</span></h1>
                    <h6>Khách hàng đã phục vụ</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <h1><span class="counter">33</span><span class="counterUp-letter">K</span><span class="counterUp-icon">+</span></h1>
                    <h6>Bộ võ phục đã bán</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <h1><span class="counter">100</span><span class="counterUp-icon">%</span></h1>
                    <h6>Sản phẩm Karate-Do chuẩn WKF</h6>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 align-self-center">
                <div class="ltn__counterup-item-3 text-color-white text-center">
                    <h1><span class="counter">63</span><span class="counterUp-icon">+</span></h1>
                    <h6>Tỉnh thành giao hàng</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- COUNTER UP AREA END -->

<!-- PRODUCT AREA START (New Products / Sản Phẩm Mới) -->
<div class="ltn__product-area ltn__product-gutter pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center mb-40">
                    <h6 class="section-subtitle ltn__secondary-color font-weight-bold">// BỘ SƯU TẬP MỚI //</h6>
                    <h1 class="section-title font-weight-bold">Sản Phẩm Mới Nhất</h1>
                    <p class="text-muted mx-auto" style="max-width: 620px; font-size: 15px;">
                        Khám phá các dòng võ phục Karate-Do chuẩn WKF, đai thi đấu và trang bị bảo hộ cao cấp vừa mới cập bến cửa hàng.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($newProducts as $product)
                @php
                    $imgSrc = $product->image ?: ($product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                @endphp
                <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-4">
                    <div class="karate-prod-card">
                        <div class="karate-img-container">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ asset($imgSrc) }}" alt="{{ $product->name }}">
                            </a>
                            <span class="karate-cat-tag">{{ $product->category->name ?? 'Karate-Do' }}</span>
                            <span class="karate-stock-tag" style="background: #e0f2fe; color: #0284c7; border: 1px solid #7dd3fc; font-weight: 700;">
                                <i class="fa-solid fa-sparkles mr-1"></i> Mới về
                            </span>
                        </div>
                        <div class="karate-card-body">
                            <div>
                                <div class="text-warning small mb-1">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <span class="text-muted ml-1" style="font-size: 11px;">(5.0)</span>
                                </div>
                                <div class="karate-card-title">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </div>
                            </div>
                            <div>
                                <div class="karate-price-wrap">
                                    <div class="karate-price-left">
                                        <span class="karate-main-price">{{ number_format($product->price, 0, ',', '.') }} ₫</span>
                                        <span class="karate-unit-text">/ {{ $product->unit ?? 'Bộ' }}</span>
                                    </div>
                                    <div class="karate-stock-right">
                                        @if($product->stock > 0)
                                            <span class="karate-stock-qty" title="Số lượng còn lại trong kho">
                                                Còn: <strong>{{ $product->stock }}</strong>
                                            </span>
                                        @else
                                            <span class="karate-stock-qty out-of-stock" title="Sản phẩm tạm hết hàng">
                                                Hết hàng
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <form method="POST" action="{{ route('cart.store') }}" class="flex-grow-1 mr-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart-main w-100">
                                            <i class="fa-solid fa-cart-plus"></i>
                                            <span>Thêm vào giỏ</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('wishlist.store', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn-wishlist-icon" title="Thêm vào yêu thích">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    Hiện chưa có sản phẩm mới nào được cập nhật.
                </div>
            @endforelse
            <div class="col-lg-12 text-center mt-30">
                <a href="{{ route('products.index') }}" class="theme-btn-1 btn btn-effect-1 text-uppercase font-weight-bold" style="border-radius: 8px; padding: 12px 30px;">
                    Xem Thêm Các Sản Phẩm Khác <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- PRODUCT AREA END -->

<!-- CALL TO ACTION START -->
<div class="ltn__call-to-action-area ltn__call-to-action-4 home-call-to-action bg-image pt-115 pb-120" data-bg="{{ asset('assets/clients/img/banner/5.jpg') }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="call-to-action-inner call-to-action-inner-4 text-center" style="background: rgba(15, 23, 42, 0.85); padding: 50px 30px; border-radius: 16px;">
                    <div class="section-title-area ltn__section-title-2">
                        <h6 class="section-subtitle ltn__secondary-color font-weight-bold text-warning">// TƯ VẤN TRANG THIẾT BỊ VÕ THUẬT MIỄN PHÍ //</h6>
                        <h1 class="section-title white-color mb-3" style="font-size: 38px;">HOTLINE: 0967.137.200</h1>
                        <p class="text-light mb-4">Hỗ trợ tư vấn chọn size võ phục, chọn đai thi đấu và đặt hàng theo yêu cầu câu lạc bộ.</p>
                    </div>
                    <div class="btn-wrapper">
                        <a href="tel:+84967137200" class="theme-btn-1 btn btn-effect-1 font-weight-bold mr-2">GỌI ĐIỆN NGAY</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light font-weight-bold px-4 py-2" style="border-radius: 8px;">LIÊN HỆ CHÚNG TÔI</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CALL TO ACTION END -->

<!-- FEATURE AREA START -->
<div class="ltn__feature-area before-bg-bottom-2-- mb--30--- plr--5 my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="ltn__feature-item-box-wrap ltn__border-between-column white-bg p-4 rounded shadow-sm">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-shield-halved text-danger fa-2x mr-3"></i>
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="font-size: 15px;">Võ phục chuẩn WKF</h5>
                                    <p class="small text-muted mb-0">Chất liệu bền chắc, đứng form</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-medal text-warning fa-2x mr-3"></i>
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="font-size: 15px;">Chất lượng đáng tin cậy</h5>
                                    <p class="small text-muted mb-0">Kiểm tra kỹ từng đường chỉ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-boxes-stacked text-primary fa-2x mr-3"></i>
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="font-size: 15px;">Đầy đủ dụng cụ tập</h5>
                                    <p class="small text-muted mb-0">Hỗ trợ mọi cấp độ luyện võ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-truck-fast text-success fa-2x mr-3"></i>
                                <div>
                                    <h5 class="font-weight-bold mb-1" style="font-size: 15px;">Giao hàng toàn quốc</h5>
                                    <p class="small text-muted mb-0">Đóng gói kỹ & nhận hàng nhanh</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FEATURE AREA END -->

<!-- MODALS -->
@include('clients.components.modals.xem_nhanh_modal')
@include('clients.components.modals.them_vao_gio_modal')
@include('clients.components.modals.danh_sach_yeu_thich_modal')

@endsection
