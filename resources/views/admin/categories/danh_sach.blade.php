@extends('layouts.quan_tri')

@section('title', 'Quản lý Danh mục Sản phẩm Võ thuật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-layer-group text-danger mr-2"></i> QUẢN LÝ DANH MỤC VÕ THUẬT</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-karate">
            <i class="fa-solid fa-plus mr-1"></i> Thêm danh mục mới
        </a>
    </div>
</div>

<div class="clearfix"></div>

<!-- Filter and Search -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="row align-items-end">
            <div class="col-md-9 col-sm-8 form-group">
                <label class="font-weight-500">Tìm kiếm danh mục:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Nhập tên danh mục hoặc mô tả..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-3 col-sm-4 form-group">
                <button type="submit" class="btn btn-secondary btn-block">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list mr-1"></i> Danh sách danh mục ({{ $categories->total() }} danh mục)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th width="80">Hình ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Đường dẫn (Slug)</th>
                    <th>Mô tả</th>
                    <th class="text-center">Số sản phẩm</th>
                    <th width="140" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        @if($cat->image)
                            <img src="{{ asset($cat->image) }}" class="product-thumb-sm" alt="{{ $cat->name }}">
                        @else
                            <div class="product-thumb-sm d-flex align-items-center justify-content-center bg-light text-muted">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                        @endif
                    </td>
                    <td class="font-weight-bold text-dark">{{ $cat->name }}</td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td class="text-muted small">{{ Str::limit($cat->description, 60) ?: 'Chưa có mô tả' }}</td>
                    <td class="text-center">
                        <span class="badge badge-primary badge-pill font-weight-bold px-3 py-1">
                            {{ $cat->products_count }} sản phẩm
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-sm btn-warning btn-sm-action" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
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
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>
                        Chưa có danh mục võ thuật nào được tạo.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $categories->firstItem() ?? 0 }} đến {{ $categories->lastItem() ?? 0 }} trong tổng số {{ $categories->total() }} danh mục
            </div>
            <div>
                {{ $categories->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
