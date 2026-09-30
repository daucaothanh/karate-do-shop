@extends('layouts.quan_tri')

@section('title', 'Thêm mới Nhân viên / Tài khoản')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-user-plus text-danger mr-2"></i> THÊM MỚI NHÂN VIÊN / TÀI KHOẢN</h3>
    </div>
    <div class="title_right text-right">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <div class="x_title">
        <h2>Thông tin tài khoản</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Họ và tên <span class="text-danger">*</span>:</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required autofocus>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Địa chỉ Email <span class="text-danger">*</span>:</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="staff@gmail.com" value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Mật khẩu ban đầu <span class="text-danger">*</span>:</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự" required>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Số điện thoại liên hệ:</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="09xxxxxxxx" value="{{ old('phone_number') }}">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Địa chỉ cư trú:</label>
                        <input type="text" name="address" class="form-control" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố..." value="{{ old('address') }}">
                    </div>
                </div>

                <!-- Right Column: Role, Status, Avatar -->
                <div class="col-md-4 col-sm-12">
                    <div class="card p-3 bg-light border mb-3">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-user-shield text-danger mr-1"></i> Phân quyền & Trạng thái</h6>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Vai trò trong hệ thống <span class="text-danger">*</span>:</label>
                            <select name="role_id" class="form-control font-weight-bold" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', 2) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name === 'admin' ? '🛡️ Quản trị viên (Admin - Toàn quyền)' : ($role->name === 'staff' ? '👔 Nhân viên bán hàng (Staff)' : '👤 Khách hàng (Customer)') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Ca làm việc cố định (Dành cho nhân viên):</label>
                            <select name="default_shift_id" class="form-control">
                                <option value="">-- Không cố định / Admin --</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('default_shift_id') == $shift->id ? 'selected' : '' }}>
                                        ⏰ {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-info"></i> Nhân viên chỉ có quyền chấm công và duyệt đơn khi đúng ca này.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Trạng thái tài khoản:</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>✅ Hoạt động ngay</option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>⏳ Chờ kích hoạt</option>
                                <option value="banned" {{ old('status') === 'banned' ? 'selected' : '' }}>🚫 Tạm khóa</option>
                            </select>
                        </div>
                    </div>

                    <div class="card p-3 bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-image text-info mr-1"></i> Ảnh đại diện (Avatar)</h6>
                        <div class="custom-file mb-3">
                            <input type="file" name="avatar" class="custom-file-input" id="userAvatarInput" onchange="previewImages(this, 'user-avatar-preview')">
                            <label class="custom-file-label" for="userAvatarInput">Chọn ảnh...</label>
                        </div>
                        <div id="user-avatar-preview" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light border mr-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                    <i class="fa-solid fa-save mr-1"></i> TẠO TÀI KHOẢN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
