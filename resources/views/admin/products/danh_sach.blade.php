@extends('layouts.quan_tri')

@section('title', 'Quản lý Sản phẩm Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-shirt text-danger mr-2"></i> QUẢN LÝ SẢN PHẨM VÕ THUẬT</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.products.create') }}" class="btn btn-karate">
            <i class="fa-solid fa-plus mr-1"></i> Thêm sản phẩm mới
        </a>
    </div>
</div>

<div class="clearfix"></div>

<!-- Filter and Search Box -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row align-items-end">
            <div class="col-md-4 col-sm-12 form-group">
                <label class="font-weight-500">Từ khóa tìm kiếm:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm, slug..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Danh mục:</label>
                <select name="category_id" class="form-control">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Trạng thái tồn kho:</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                    <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>Ngừng kinh doanh</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12 form-group">
                <button type="submit" class="btn btn-secondary btn-block">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list mr-1"></i> Danh sách sản phẩm ({{ $products->total() }} sản phẩm)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th width="70">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th>Đơn vị</th>
                    <th>Trạng thái</th>
                    <th width="140" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                <tr>
                    <td>
                        @if($prod->images->count() > 0)
                            <img src="{{ asset($prod->images->first()->image) }}" class="product-thumb-sm" alt="{{ $prod->name }}">
                        @else
                            <div class="product-thumb-sm d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>
                            <a href="{{ route('admin.products.show', $prod->id) }}" class="text-dark">
                                {{ $prod->name }}
                            </a>
                        </strong>
                        <div class="mt-1">
                            @if($prod->colors)
                                <small class="text-muted mr-2"><i class="fa-solid fa-palette text-danger"></i> {{ \Illuminate\Support\Str::limit($prod->colors, 28) }}</small>
                            @endif
                            @if($prod->sizes)
                                <small class="text-muted"><i class="fa-solid fa-ruler text-success"></i> {{ \Illuminate\Support\Str::limit($prod->sizes, 22) }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-light border font-weight-500">
                            {{ $prod->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </td>
                    <td class="font-weight-bold text-danger">
                        {{ number_format($prod->price, 0, ',', '.') }} ₫
                    </td>
                    <td>
                        <div class="small text-muted">Đã nhập: <strong>{{ $prod->initial_stock ?? $prod->stock }}</strong></div>
                        <div>Còn lại:
                            @if($prod->stock <= 5)
                                <span class="badge badge-danger font-weight-bold">{{ $prod->stock }}</span>
                            @else
                                <span class="font-weight-bold text-dark">{{ $prod->stock }}</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $prod->unit }}</td>
                    <td>
                        @if($prod->status === 'in_stock')
                            <span class="badge-status badge-in_stock">Còn hàng</span>
                        @elseif($prod->status === 'out_of_stock')
                            <span class="badge-status badge-out_of_stock">Hết hàng</span>
                        @else
                            <span class="badge-status badge-discontinued">Ngừng bán</span>
                        @endif
                    </td>
                    <td class="text-center">
                            <a href="{{ route('admin.products.show', $prod->id) }}" class="btn btn-sm btn-info btn-sm-action" title="Xem chi tiết">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-sm btn-warning btn-sm-action" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-sm-action" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
                        Không tìm thấy sản phẩm võ thuật nào phù hợp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $products->firstItem() ?? 0 }} đến {{ $products->lastItem() ?? 0 }} trong tổng số {{ $products->total() }} sản phẩm
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
