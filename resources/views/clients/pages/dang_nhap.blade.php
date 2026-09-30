@extends('layouts.khach_hang')

@section('title', 'Đăng nhập')

@section('breadcrumb', 'Đăng nhập')

@section('content')
<!-- LOGIN AREA START -->
        <div class="ltn__login-area pb-65">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h1 class="section-title">Đăng nhập <br>tài khoản của bạn</h1>
                            <p>Đăng nhập để mua sắm võ phục và dụng cụ Karate-Do thuận tiện hơn.<br>
                                Theo dõi đơn hàng và nhận tư vấn nhanh chóng từ shop.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="account-login-inner">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('post-login') }}" class="ltn__form-box contact-form-box" method="POST" id="login-form">
                                @csrf

                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email*" required>
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror

                                <div class="password-field-wrapper">
                                    <input type="password" name="password" id="login-password" placeholder="Mật khẩu*" required>
                                    <button type="button" class="password-toggle" data-password-target="login-password" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="btn-wrapper mt-0">
                                    <button class="theme-btn-1 btn btn-block" type="submit">ĐĂNG NHẬP</button>
                                </div>
                                <div class="go-to-btn mt-20">
                                    <a href="{{route('password.request')}}"><small>BẠN QUÊN MẬT KHẨU?</small></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <h4>BẠN CHƯA CÓ TÀI KHOẢN?</h4>
                            <p>Tạo tài khoản để lưu sản phẩm yêu thích, nhận tư vấn phù hợp,<br>
                                theo dõi đơn hàng và thanh toán nhanh chóng hơn.</p>
                            <div class="btn-wrapper">
                                <a href="{{ route('register') }}" class="theme-btn-1 btn black-btn">TẠO TÀI KHOẢN</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- LOGIN AREA END -->

        <style>
            .password-field-wrapper {
                position: relative;
            }

            .password-field-wrapper input {
                padding-right: 48px;
            }

            .password-toggle {
                position: absolute;
                top: 50%;
                right: 12px;
                width: 32px;
                height: 32px;
                padding: 0;
                border: 0;
                background: transparent;
                color: #777;
                cursor: pointer;
                transform: translateY(-50%);
            }

            .password-toggle:hover,
            .password-toggle:focus {
                color: #c62828;
                outline: none;
            }
        </style>

        <script>
            document.querySelectorAll('.password-toggle').forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    var passwordInput = document.getElementById(toggle.dataset.passwordTarget);
                    var icon = toggle.querySelector('i');
                    var isHidden = passwordInput.type === 'password';

                    passwordInput.type = isHidden ? 'text' : 'password';
                    icon.classList.toggle('fa-eye', !isHidden);
                    icon.classList.toggle('fa-eye-slash', isHidden);
                    toggle.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
                    toggle.setAttribute('title', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
                });
            });
        </script>
   
@endsection