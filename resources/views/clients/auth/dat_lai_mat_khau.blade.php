@extends('layouts.khach_hang')

@section('title', 'Đặt lại mật khẩu')

@section('breadcrumb', 'Đặt lại mật khẩu')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="ltn__form-box">
                    <h4 class="mb-4">Đặt lại mật khẩu</h4>
                    <form action="{{ route('password.update') }}" method="POST" id="reset-password-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu mới" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required>
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger w-100 text-uppercase">Đặt lại mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection