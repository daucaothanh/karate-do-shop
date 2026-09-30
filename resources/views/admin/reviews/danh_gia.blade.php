@extends('layouts.quan_tri')

@section('title', 'Quản lý Đánh giá Sản phẩm')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-star text-warning mr-2"></i> QUẢN LÝ ĐÁNH GIÁ SẢN PHẨM</h3>
    </div>
</div>

<div class="clearfix"></div>

<!-- Filter and Search -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row align-items-end">
            <div class="col-md-6 col-sm-12 form-group">
                <label class="font-weight-500">Tìm kiếm nội dung / khách hàng / sản phẩm:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-4 col-sm-6 form-group">
                <label class="font-weight-500">Số sao đánh giá:</label>
                <select name="rating" class="form-control">
                    <option value="">-- Tất cả số sao (1-5 sao) --</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Sao</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Sao</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Sao</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ 2 Sao</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ 1 Sao</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <button type="submit" class="btn btn-secondary btn-block">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list mr-1"></i> Danh sách đánh giá ({{ $reviews->total() }} đánh giá)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th width="150">Khách hàng</th>
                    <th width="200">Sản phẩm võ thuật</th>
                    <th width="120">Đánh giá</th>
                    <th>Nội dung bình luận</th>
                    <th width="120">Thời gian</th>
                    <th width="90" class="text-center">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $rev)
                <tr>
                    <td>
                        <strong>{{ $rev->user->name ?? 'Người dùng' }}</strong>
                        <div class="small text-muted">{{ $rev->user->email ?? '' }}</div>
                    </td>
                    <td>
                        @if($rev->product)
                            <a href="{{ route('admin.products.show', $rev->product->id) }}" class="font-weight-bold text-dark">
                                {{ Str::limit($rev->product->name, 35) }}
                            </a>
                        @else
                            <span class="text-muted">Sản phẩm đã bị xóa</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-warning text-nowrap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        <div class="text-dark">{{ $rev->comment ?: '(Không có nhận xét)' }}</div>
                    </td>
                    <td><small class="text-muted">{{ $rev->created_at->format('d/m/Y H:i') }}</small></td>
                    <td class="text-center">
                        <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-sm-action" title="Xóa đánh giá">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-comments fa-2x mb-2 d-block"></i>
                        Không có đánh giá nào phù hợp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $reviews->firstItem() ?? 0 }} đến {{ $reviews->lastItem() ?? 0 }} trong tổng số {{ $reviews->total() }} đánh giá
            </div>
            <div>
                {{ $reviews->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
