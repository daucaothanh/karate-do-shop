@extends('layouts.quan_tri')

@section('title', 'Quản lý Liên hệ & Tư vấn')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-envelope-open-text text-danger mr-2"></i> QUẢN LÝ LIÊN HỆ & TƯ VẤN KHÁCH HÀNG</h3>
    </div>
</div>

<div class="clearfix"></div>

<!-- Filter and Search -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="row align-items-end">
            <div class="col-md-6 col-sm-12 form-group">
                <label class="font-weight-500">Tìm kiếm theo họ tên, email, sđt hoặc nội dung:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-4 col-sm-6 form-group">
                <label class="font-weight-500">Trạng thái phản hồi:</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="unreplied" {{ request('status') === 'unreplied' ? 'selected' : '' }}>⏳ Chưa phản hồi</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>✅ Đã phản hồi</option>
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

<!-- Contacts Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list mr-1"></i> Danh sách tin nhắn tư vấn ({{ $contacts->total() }} yêu cầu)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th width="180">Họ và tên</th>
                    <th width="160">Liên hệ</th>
                    <th>Nội dung tin nhắn / Yêu cầu tư vấn</th>
                    <th width="150">Trạng thái</th>
                    <th width="120">Thời gian gửi</th>
                    <th width="120" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                <tr>
                    <td>
                        <strong class="text-dark">{{ $c->full_name }}</strong>
                    </td>
                    <td>
                        <div><i class="fa-solid fa-envelope text-muted mr-1"></i> {{ $c->email ?: 'Chưa có email' }}</div>
                        <div class="small"><i class="fa-solid fa-phone text-muted mr-1"></i> {{ $c->phone_number ?: 'Chưa có SĐT' }}</div>
                    </td>
                    <td>
                        <div class="text-dark p-2 bg-light rounded border">{{ $c->message }}</div>
                    </td>
                    <td>
                        @if($c->is_replied)
                            <span class="badge badge-success px-2 py-1"><i class="fa-solid fa-check mr-1"></i> Đã phản hồi</span>
                        @else
                            <span class="badge badge-warning text-dark px-2 py-1"><i class="fa-solid fa-clock mr-1"></i> Chưa phản hồi</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $c->created_at->format('d/m/Y H:i') }}</small></td>
                    <td class="text-center">
                        <!-- Toggle Replied -->
                        <form action="{{ route('admin.contacts.toggle', $c->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $c->is_replied ? 'btn-outline-secondary' : 'btn-outline-success' }} btn-sm-action" title="{{ $c->is_replied ? 'Đánh dấu chưa phản hồi' : 'Đánh dấu đã phản hồi' }}">
                                <i class="fa-solid {{ $c->is_replied ? 'fa-rotate-left' : 'fa-check' }}"></i>
                            </button>
                        </form>

                        <!-- Delete -->
                        <form action="{{ route('admin.contacts.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?');">
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
                        <i class="fa-solid fa-envelope-circle-check fa-2x mb-2 d-block"></i>
                        Không có tin nhắn liên hệ nào cần xử lý.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $contacts->firstItem() ?? 0 }} đến {{ $contacts->lastItem() ?? 0 }} trong tổng số {{ $contacts->total() }} tin nhắn
            </div>
            <div>
                {{ $contacts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
