@extends('layouts.khach_hang')

@section('title', 'Đăng ký')

@section('breadcrumb', 'Đăng ký')

@section('content')
 <div class="ltn__login-area pb-110">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h1 class="section-title">Đăng ký <br>Tài khoản của bạn</h1>
                            <p>Tạo tài khoản để mua sắm võ phục và dụng cụ Karate-Do thuận tiện hơn.<br>
                                Shop sẽ hỗ trợ bạn theo dõi đơn hàng và nhận tư vấn nhanh chóng.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="account-login-inner">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" class="ltn__form-box contact-form-box" method="POST" id="register-form">
                                @csrf
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Họ và tên*" required>

                                <input type="email" name="email" value="{{ old('email') }}" placeholder="abc@gmail.com*" required>

                                <div class="password-field-wrapper">
                                    <input type="password" name="password" id="register-password" placeholder="Mật khẩu*" required>
                                    <button type="button" class="password-toggle" data-password-target="register-password" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <div class="password-field-wrapper">
                                    <input type="password" name="confirmpassword" id="register-password-confirmation" placeholder="Xác nhận mật khẩu*" required>
                                    <button type="button" class="password-toggle" data-password-target="register-password-confirmation" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <label class="checkbox-inline">
                                    <input type="checkbox" name="checkbox1" value="1" required>
                                    Tôi đồng ý cho karate_do.shop sử dụng thông tin cá nhân để tư vấn sản phẩm,
                                    xác nhận và xử lý đơn hàng theo chính sách bảo mật.
                                </label>

                                <label class="checkbox-inline">
                                    <input type="checkbox" name="checkbox2" value="1" required>
                                    Khi nhấn “Tạo tài khoản”, tôi đồng ý với chính sách bảo mật của shop.
                                </label>

                                <div class="btn-wrapper">
                                    <button class="theme-btn-1 btn reverse-color btn-block" type="submit">TẠO
                                        TÀI KHOẢN</button>
                                </div>
                            </form>
                            <div class="by-agree text-center">
                                <p>Bằng việc tạo tài khoản, bạn đồng ý với:</p>
                                <p><a href="#">ĐIỀU KHOẢN SỬ DỤNG &nbsp; &nbsp; | &nbsp; &nbsp; CHÍNH SÁCH BẢO MẬT</a></p>
                                <div class="go-to-btn mt-50">
                                    <a href="{{ route('login') }}">BẠN ĐÃ CÓ TÀI KHOẢN?</a>
                                </div>
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