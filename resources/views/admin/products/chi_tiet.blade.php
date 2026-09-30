@extends('layouts.quan_tri')

@section('title', 'Chi tiết Sản phẩm Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-eye text-info mr-2"></i> CHI TIẾT SẢN PHẨM: <small class="text-dark">{{ $product->name }}</small></h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning mr-1">
            <i class="fa-solid fa-pen-to-square mr-1"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Danh sách
        </a>
    </div>
</div>

<div class="clearfix"></div>

<div class="row">
    <!-- Left: Image Gallery & Quick Stats -->
    <div class="col-md-5 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-images mr-1"></i> Thư viện hình ảnh</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if($product->images->count() > 0)
                    <!-- Primary Main Image -->
                    <div class="text-center p-2 mb-3 bg-light rounded border">
                        <img src="{{ asset($product->images->first()->image) }}" id="mainProductView" class="img-fluid rounded" style="max-height: 320px; object-fit: contain;">
                    </div>
                    <!-- Thumbnails -->
                    <div class="d-flex flex-wrap justify-content-center">
                        @foreach($product->images as $img)
                            <img src="{{ asset($img->image) }}" class="img-thumbnail mr-2 mb-2" style="width: 65px; height: 65px; object-fit: cover; cursor: pointer;" onclick="document.getElementById('mainProductView').src = this.src;">
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 bg-light rounded text-muted">
                        <i class="fa-solid fa-image fa-3x mb-2 d-block"></i>
                        Chưa có hình ảnh nào được tải lên cho sản phẩm này.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Detailed Specifications -->
    <div class="col-md-7 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-circle-info mr-1"></i> Thông số & Tình trạng</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%" class="bg-light">Tên sản phẩm:</th>
                            <td class="font-weight-bold text-dark">{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Mã Slug:</th>
                            <td><code>{{ $product->slug }}</code></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Danh mục võ thuật:</th>
                            <td><span class="badge badge-info">{{ $product->category->name ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Giá bán:</th>
                            <td class="font-weight-bold text-danger font-size-18" style="font-size: 18px;">
                                {{ number_format($product->price, 0, ',', '.') }} VNĐ
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Số lượng tồn kho:</th>
                            <td>
                                <div><span class="text-muted">Tổng đã nhập:</span> <strong>{{ $product->initial_stock ?? $product->stock }} {{ $product->unit }}</strong></div>
                                <div><span class="text-muted">Còn lại:</span> <span class="badge {{ $product->stock > 5 ? 'badge-success' : 'badge-danger' }} p-2">{{ $product->stock }} {{ $product->unit }}</span></div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Trạng thái:</th>
                            <td>
                                @if($product->status === 'in_stock')
                                    <span class="badge-status badge-in_stock">Đang kinh doanh / Còn hàng</span>
                                @elseif($product->status === 'out_of_stock')
                                    <span class="badge-status badge-out_of_stock">Tạm hết hàng</span>
                                @else
                                    <span class="badge-status badge-discontinued">Ngừng kinh doanh</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Màu sắc phân loại:</th>
                            <td>
                                @if($product->colors)
                                    @foreach(array_filter(array_map('trim', explode(',', $product->colors))) as $c)
                                        <span class="badge badge-light border text-danger mr-1"><i class="fa-solid fa-palette mr-1"></i>{{ $c }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Mặc định</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Kích cỡ / Size:</th>
                            <td>
                                @if($product->sizes)
                                    @foreach(array_filter(array_map('trim', explode(',', $product->sizes))) as $s)
                                        <span class="badge badge-light border text-dark mr-1"><i class="fa-solid fa-ruler mr-1 text-success"></i>{{ $s }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tiêu chuẩn</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-light">Ngày tạo:</th>
                            <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h6 class="font-weight-bold text-dark"><i class="fa-solid fa-align-left mr-1"></i> Mô tả sản phẩm:</h6>
                    <div class="p-3 bg-light rounded border text-secondary" style="white-space: pre-line;">
                        {{ $product->description ?: 'Chưa có mô tả chi tiết cho sản phẩm này.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Section -->
<div class="row">
    <div class="col-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-star text-warning mr-1"></i> Đánh giá từ võ sinh / khách hàng ({{ $product->reviews->count() }})</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @forelse($product->reviews as $review)
                    <div class="media mb-3 p-3 bg-light rounded border">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'User') }}&background=2A3F54&color=fff&size=50" class="mr-3 rounded-circle" style="width: 45px; height: 45px;">
                        <div class="media-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mt-0 mb-1 font-weight-bold">{{ $review->user->name ?? 'Khách hàng' }}</h6>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="text-warning mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-0 text-secondary">{{ $review->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">Sản phẩm này chưa có đánh giá nào.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
