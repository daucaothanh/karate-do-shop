@extends('layouts.quan_tri')

@section('title', 'Quản lý Tài khoản & Nhân viên')

@section('content')
{{-- Trang này hiển thị danh sách khách hàng, nhân viên và quản trị viên. --}}
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-users-gear text-danger mr-2"></i> QUẢN LÝ TÀI KHOẢN & NHÂN VIÊN</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.users.create') }}" class="btn btn-karate">
            <i class="fa-solid fa-user-plus mr-1"></i> Thêm nhân viên / tài khoản
        </a>
    </div>
</div>

<div class="clearfix"></div>

<!-- Khu vực tìm kiếm và lọc danh sách theo từ khóa, vai trò, trạng thái. -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row align-items-end">
            <div class="col-md-4 col-sm-12 form-group">
                <label class="font-weight-500">Tìm kiếm theo từ khóa:</label>
                <input type="text" name="keyword" class="form-control" placeholder="Tên, email hoặc số điện thoại..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Vai trò tài khoản:</label>
                <select name="role" class="form-control">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ request('role') == $r->name ? 'selected' : '' }}>
                            {{ ucfirst($r->name) }} ({{ $r->name === 'admin' ? 'Quản trị viên' : ($r->name === 'staff' ? 'Nhân viên' : 'Khách hàng') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Trạng thái hoạt động:</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động bình thường</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ kích hoạt</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Đang bị khóa</option>
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

<!-- Bảng thông tin tài khoản sau khi áp dụng bộ lọc. -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list mr-1"></i> Danh sách người dùng & nhân viên ({{ $users->total() }} tài khoản)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th width="70">Avatar</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="160" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                {{-- Duyệt từng tài khoản; nếu không có dữ liệu thì hiển thị thông báo. --}}
                @forelse($users as $u)
                <tr>
                    <td>
                        {{-- Ưu tiên ảnh đại diện đã lưu; nếu chưa có thì dùng ảnh chữ cái tự động. --}}
                        @if($u->avatar)
                            <img src="{{ asset($u->avatar) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="{{ $u->name }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=2A3F54&color=fff&size=50" class="rounded-circle" style="width: 40px; height: 40px;" alt="{{ $u->name }}">
                        @endif
                    </td>
                    <td>
                        <strong class="text-dark">{{ $u->name }}</strong>
                        @if($u->id === auth()->id())
                            <span class="badge badge-light border text-danger ml-1">(Bạn)</span>
                        @endif
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone_number ?: 'Chưa cập nhật' }}</td>
                    <td>
                        {{-- Hiển thị vai trò dựa trên tên role trong cơ sở dữ liệu. --}}
                        @if($u->role && $u->role->name === 'admin')
                            <span class="badge badge-danger px-2 py-1"><i class="fa-solid fa-shield mr-1"></i> Quản trị viên</span>
                        @elseif($u->role && $u->role->name === 'staff')
                            <span class="badge badge-primary px-2 py-1"><i class="fa-solid fa-user-tie mr-1"></i> Nhân viên</span>
                            @if($u->defaultShift)
                                <div class="mt-1">
                                    <span class="badge badge-info" style="font-size: 11px; background-color: #17a2b8; color: #fff;">
                                        <i class="fa-regular fa-clock mr-1"></i> {{ $u->defaultShift->name }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <span class="badge badge-secondary px-2 py-1"><i class="fa-solid fa-user mr-1"></i> Khách hàng</span>
                        @endif
                    </td>
                    <td>
                        {{-- Chuyển mã trạng thái trong database thành nhãn tiếng Việt. --}}
                        @if($u->status === 'active')
                            <span class="badge-status badge-active">Hoạt động</span>
                        @elseif($u->status === 'banned')
                            <span class="badge-status badge-banned">Đã bị khóa</span>
                        @else
                            <span class="badge-status badge-pending">Chờ kích hoạt</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $u->created_at->format('d/m/Y') }}</small></td>
                    <td class="text-center">
                        <!-- Nút khóa hoặc mở khóa nhanh; không cho tự khóa tài khoản đang đăng nhập. -->
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $u->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }} btn-sm-action" title="{{ $u->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa' }}">
                                <i class="fa-solid {{ $u->status === 'active' ? 'fa-lock' : 'fa-lock-open' }}"></i>
                            </button>
                        </form>
                        @endif

                        <!-- Mở trang chỉnh sửa thông tin tài khoản. -->
                        <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-warning btn-sm-action" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <!-- Xóa tài khoản; yêu cầu xác nhận trước khi gửi request DELETE. -->
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-sm-action" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-user-slash fa-2x mb-2 d-block"></i>
                        Không tìm thấy tài khoản nào theo tiêu chí tìm kiếm.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Phân trang và thông tin vị trí các bản ghi đang hiển thị. -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} trong tổng số {{ $users->total() }} tài khoản
            </div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
