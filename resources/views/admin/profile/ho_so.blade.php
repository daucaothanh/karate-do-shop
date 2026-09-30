@extends('layouts.quan_tri')

@section('title', 'Hồ sơ cá nhân & Bảo mật')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-id-card text-danger mr-2"></i> HỒ SƠ CÁ NHÂN & BẢO MẬT</h3>
    </div>
</div>

<div class="clearfix"></div>

<div class="row">
    <!-- Left Column: User Summary Card -->
    <div class="col-md-4 col-sm-12">
        <div class="x_panel text-center">
            <div class="x_content py-3">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}" class="rounded-circle img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=d32f2f&color=fff&size=120" class="rounded-circle img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                    @endif
                </div>

                <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                <div class="text-muted mb-2">{{ $user->email }}</div>

                <div class="mb-3">
                    @if($user->isAdmin())
                        <span class="badge badge-danger px-3 py-2 font-size-14"><i class="fa-solid fa-shield mr-1"></i> Quản trị viên (Admin)</span>
                    @elseif($user->isStaff())
                        <span class="badge badge-primary px-3 py-2 font-size-14"><i class="fa-solid fa-user-tie mr-1"></i> Nhân viên bán hàng (Staff)</span>
                    @else
                        <span class="badge badge-secondary px-3 py-2 font-size-14">Khách hàng</span>
                    @endif
                </div>

                <ul class="list-group list-group-flush text-left border-top pt-2">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Số điện thoại:</span>
                        <strong class="text-dark">{{ $user->phone_number ?: 'Chưa cập nhật' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Trạng thái:</span>
                        <span class="badge badge-success">Đang hoạt động</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Ngày tham gia:</span>
                        <strong class="text-dark">{{ $user->created_at->format('d/m/Y') }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Update Forms -->
    <div class="col-md-8 col-sm-12">
        <!-- Form 1: General Info -->
        <div class="x_panel mb-4">
            <div class="x_title">
                <h2><i class="fa-solid fa-user-pen mr-1"></i> Cập nhật thông tin tài khoản</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span>:</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Địa chỉ Email <span class="text-danger">*</span>:</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Số điện thoại:</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Đổi ảnh đại diện:</label>
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input" id="profileAvatarInput">
                                <label class="custom-file-label" for="profileAvatarInput">Chọn tệp...</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Địa chỉ:</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                            <i class="fa-solid fa-save mr-1"></i> Lưu thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form 2: Change Password -->
        <div class="x_panel" id="password-section">
            <div class="x_title">
                <h2><i class="fa-solid fa-key mr-1"></i> Đổi mật khẩu đăng nhập</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Mật khẩu hiện tại <span class="text-danger">*</span>:</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Mật khẩu mới <span class="text-danger">*</span>:</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Nhập lại mật khẩu mới <span class="text-danger">*</span>:</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-warning px-4 font-weight-bold">
                            <i class="fa-solid fa-lock mr-1"></i> Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
