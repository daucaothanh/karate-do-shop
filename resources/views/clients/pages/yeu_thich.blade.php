@extends('layouts.khach_hang')

@section('title', 'Sản phẩm yêu thích | Karate-Do Shop')

@section('breadcrumb', 'Danh sách yêu thích')

@section('content')
<!-- WISHLIST AREA START -->
<div class="liton__wishlist-area mb-105 mt-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping-cart-inner border rounded p-4 bg-white shadow-sm">
                    <h4 class="font-weight-bold mb-4 pb-2 border-bottom">
                        <i class="fa fa-heart text-danger mr-2"></i> Sản phẩm võ thuật bạn yêu thích ({{ $items->count() }} sản phẩm)
                    </h4>

                    @if($items->isNotEmpty())
                        <div class="shoping-cart-table table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-center">Tình trạng kho</th>
                                        <th class="text-center" width="160">Hành động</th>
                                        <th class="text-center" width="50">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->images->count() > 0)
                                                    <img src="{{ asset($item->product->images->first()->image) }}" alt="{{ $item->product->name }}" class="border rounded mr-3" style="width: 60px; height: 60px; object-fit: contain;">
                                                @else
                                                    <img src="{{ asset('assets/clients/img/product/1.png') }}" class="border rounded mr-3" style="width: 60px; height: 60px; object-fit: contain;">
                                                @endif
                                                <div>
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="font-weight-bold text-dark d-block">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <small class="text-muted">Đơn vị: {{ $item->product->unit }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right font-weight-bold align-middle text-danger font-size-16">
                                            {{ number_format($item->product->price, 0, ',', '.') }} ₫
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($item->product->stock > 0)
                                                <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i> Còn {{ $item->product->stock }} {{ $item->product->unit }}</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Tạm hết hàng</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <form method="POST" action="{{ route('cart.store') }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-danger font-weight-bold" {{ $item->product->stock <= 0 ? 'disabled' : '' }}>
                                                    <i class="fa fa-cart-plus mr-1"></i> Thêm giỏ
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center align-middle">
                                            <form method="POST" action="{{ route('wishlist.destroy', $item->product) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa khỏi yêu thích">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="far fa-heart fa-4x text-muted mb-3 d-block"></i>
                            <h5>Danh sách yêu thích của bạn đang trống.</h5>
                            <p class="text-muted">Hãy đánh dấu những sản phẩm bạn quan tâm để dễ dàng tìm kiếm và mua sau.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-danger mt-2 font-weight-bold">Khám phá sản phẩm ngay</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- WISHLIST AREA END -->
@endsection