@extends('layouts.khach_hang')

@section('title', 'Cửa hàng Võ phục & Dụng cụ Karate-Do Chính Hãng')

@section('breadcrumb', 'Cửa hàng võ thuật')

@push('styles')
<style>
    .karate-shop-sidebar {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .karate-cat-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .karate-cat-list li {
        margin-bottom: 6px;
    }
    .karate-cat-list li a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        border-radius: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .karate-cat-list li a:hover {
        background: #fee2e2;
        color: #d32f2f;
        font-weight: 600;
    }
    .karate-cat-list li.active a {
        background: #d32f2f;
        color: #ffffff;
        font-weight: 700;
    }
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
        height: 240px;
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
<div class="ltn__product-area ltn__product-gutter mb-120 mt-40">
    <div class="container">
        <div class="row">
            <!-- Sidebar: Categories & Filters -->
            <div class="col-lg-3 col-md-12 mb-40">
                <div class="karate-shop-sidebar">
                    <!-- Search Widget -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fa-solid fa-magnifying-glass text-danger mr-2"></i> Tìm kiếm
                        </h5>
                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control rounded-left" placeholder="Tên võ phục, đai, giáp..." value="{{ request('q') }}" style="font-size: 13.5px; border-color: #e2e8f0;">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-danger font-weight-bold px-3">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr class="my-4" style="border-color: #f1f5f9;">

                    <!-- Category Widget -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fa-solid fa-layer-group text-danger mr-2"></i> Danh mục võ thuật
                        </h5>
                        <ul class="karate-cat-list">
                            <li class="{{ !request('category') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}">
                                    <span><i class="fa-solid fa-bars-staggered mr-2"></i> Tất cả sản phẩm</span>
                                    <i class="fa-solid fa-chevron-right small"></i>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li class="{{ request('category') === $cat->slug ? 'active' : '' }}">
                                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}">
                                        <span>{{ $cat->name }}</span>
                                        <i class="fa-solid fa-chevron-right small"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Commitment Banner -->
                    <div class="p-3 bg-danger text-white rounded-lg text-center" style="background: linear-gradient(135deg, #d32f2f, #991b1b) !important;">
                        <i class="fa-solid fa-shield-halved fa-2x mb-2 d-block"></i>
                        <h6 class="text-white font-weight-bold mb-1">100% HÀNG CHÍNH HÃNG</h6>
                        <p class="small mb-0 text-light" style="font-size: 11.5px; line-height: 1.4;">
                            Đạt chuẩn thi đấu WKF Quốc tế & Miễn phí đổi trả trong 7 ngày
                        </p>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9 col-md-12">
                <!-- Shop Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap">
                    <div class="text-muted" style="font-size: 13.5px;">
                        Hiển thị <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> trong tổng số <strong>{{ $products->total() }}</strong> sản phẩm võ thuật
                    </div>
                    <div>
                        @if(request('category'))
                            <span class="badge badge-danger px-3 py-2" style="font-size: 12px;">
                                Lọc: {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}
                            </span>
                            <a href="{{ route('products.index') }}" class="text-danger small ml-2 font-weight-bold">
                                <i class="fa fa-times"></i> Xóa lọc
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Products Row -->
                <div class="row">
                    @forelse($products as $product)
                        @php
                            $imgSrc = $product->image ?: ($product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                        @endphp
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="karate-prod-card">
                                <!-- Image Container -->
                                <div class="karate-img-container">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ asset($imgSrc) }}" alt="{{ $product->name }}">
                                    </a>
                                    <span class="karate-cat-tag">
                                        {{ $product->category->name ?? 'Karate-Do' }}
                                    </span>
                                    @if($product->stock > 0)
                                        <span class="karate-stock-tag">
                                            <i class="fa-solid fa-check mr-1"></i> Sẵn hàng
                                        </span>
                                    @else
                                        <span class="karate-stock-tag" style="background: #fef2f2; color: #dc2626; border-color: #fecaca;">
                                            <i class="fa-solid fa-ban mr-1"></i> Hết hàng
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="karate-card-body">
                                    <div>
                                        <!-- Rating -->
                                        <div class="text-warning small mb-1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <span class="text-muted ml-1" style="font-size: 11px;">(5.0)</span>
                                        </div>

                                        <!-- Title -->
                                        <div class="karate-card-title">
                                            <a href="{{ route('products.show', $product->slug) }}" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </a>
                                        </div>
                                    </div>

                                    <div>
                                        <!-- Price & Stock Remaining -->
                                        <div class="karate-price-wrap">
                                            <div class="karate-price-left">
                                                <span class="karate-main-price">
                                                    {{ number_format($product->price, 0, ',', '.') }} ₫
                                                </span>
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

                                        <!-- Action Buttons -->
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
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="font-weight-bold">Không tìm thấy sản phẩm võ thuật nào phù hợp.</h5>
                            <p class="text-muted">Vui lòng thử lại với từ khóa hoặc danh mục khác.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-danger mt-2 font-weight-bold px-4 py-2" style="border-radius: 8px;">
                                Xem tất cả sản phẩm
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection