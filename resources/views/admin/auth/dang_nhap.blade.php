<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Quản trị | KARATE-DO SHOP</title>

    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e2c3b 0%, #2A3F54 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .login-header {
            background: #2A3F54;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #d32f2f;
        }
        .login-header .logo-icon {
            width: 60px;
            height: 60px;
            background: #d32f2f;
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(211, 47, 47, 0.4);
        }
        .login-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .login-header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #BAB8B8;
        }
        .login-body {
            padding: 30px;
        }
        .form-control {
            border-radius: 6px;
            height: 46px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.2);
        }
        .btn-login {
            background: #d32f2f;
            color: #fff;
            font-weight: 600;
            height: 46px;
            border-radius: 6px;
            border: none;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        .btn-login:hover {
            background: #b71c1c;
            color: #fff;
            transform: translateY(-1px);
        }
        .input-group-text {
            background: #f8f9fa;
            border-radius: 6px 0 0 6px;
            color: #73879C;
        }
        .demo-accounts {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 12px;
            border-left: 3px solid #3498db;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="mb-2">
            <img src="{{ asset('assets/admin/images/logo.png') }}" alt="Karate-Do Logo" style="width: 90px; height: 90px; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));">
        </div>
        <h3>KARATE-DO ADMIN</h3>
        <p>Hệ thống Quản lý & Nhân viên Shop Võ Thuật</p>
    </div>

    <div class="login-body">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-left" role="alert">
                <i class="fa fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-left" role="alert">
                <i class="fa fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="font-weight-500 text-dark">Địa chỉ Email:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Nhập địa chỉ email..." value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-500 text-dark">Mật khẩu:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu..." required>
                </div>
                @error('password')
                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember" checked>
                    <label class="custom-control-label text-muted small" for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <a href="{{ route('home') }}" class="small text-muted"><i class="fa-solid fa-house mr-1"></i> Trang chủ shop</a>
            </div>

            <button type="submit" class="btn btn-login btn-block font-weight-bold">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> ĐĂNG NHẬP HỆ THỐNG
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
