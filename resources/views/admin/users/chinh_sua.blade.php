@extends('layouts.quan_tri')

@section('title', 'Chỉnh sửa Tài khoản / Nhân viên')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-user-pen text-warning mr-2"></i> CHỈNH SỬA TÀI KHOẢN: <small class="text-dark">{{ $user->name }}</small></h3>
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
        <h2>Cập nhật thông tin tài khoản</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8 col-sm-12">
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
                            <label class="font-weight-bold">Mật khẩu mới (Để trống nếu không đổi):</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Số điện thoại:</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Địa chỉ:</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                    </div>
                </div>

                <!-- Right Column: Role, Status, Avatar -->
                <div class="col-md-4 col-sm-12">
                    <div class="card p-3 bg-light border mb-3">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-user-shield text-danger mr-1"></i> Phân quyền & Trạng thái</h6>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Vai trò <span class="text-danger">*</span>:</label>
                            <select name="role_id" class="form-control font-weight-bold" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                                    <option value="{{ $shift->id }}" {{ old('default_shift_id', $user->default_shift_id) == $shift->id ? 'selected' : '' }}>
                                        ⏰ {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-info"></i> Nhân viên chỉ có quyền chấm công và duyệt đơn khi đúng ca này.</small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Trạng thái:</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>✅ Hoạt động</option>
                                <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>⏳ Chờ kích hoạt</option>
                                <option value="banned" {{ old('status', $user->status) === 'banned' ? 'selected' : '' }}>🚫 Bị khóa</option>
                            </select>
                        </div>
                    </div>

                    <div class="card p-3 bg-light border">
                        <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-image text-info mr-1"></i> Avatar</h6>
                        @if($user->avatar)
                            <div class="mb-3">
                                <img src="{{ asset($user->avatar) }}" class="rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">
                            </div>
                        @endif

                        <div class="custom-file mb-3">
                            <input type="file" name="avatar" class="custom-file-input" id="userEditAvatarInput" onchange="previewImages(this, 'user-edit-avatar-preview')">
                            <label class="custom-file-label" for="userEditAvatarInput">Đổi ảnh...</label>
                        </div>
                        <div id="user-edit-avatar-preview" class="d-flex flex-wrap mt-2"></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light border mr-2">Hủy bỏ</a>
                <button type="submit" class="btn btn-karate px-4 font-weight-bold">
                    <i class="fa-solid fa-save mr-1"></i> CẬP NHẬT TÀI KHOẢN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
