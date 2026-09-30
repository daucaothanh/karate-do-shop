<!-- FOOTER AREA START -->
<footer class="ltn__footer-area">
    <div class="footer-top-area  section-bg-1 plr--5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-about-widget">
                        <div class="footer-logo">
                            <div class="site-logo">
                                <a href="{{ url('/') }}"><img src="{{ asset('assets/clients/img/logo.png') }}" alt="Logo" style="max-height: 70px;"></a>
                            </div>
                        </div>
                        <p>karate_do.shop chuyên cung cấp võ phục và dụng cụ Karate-Do chính hãng, hỗ trợ tư vấn tận tâm và giao hàng trên toàn quốc.</p>
                        <div class="footer-address">
                            <ul>
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-placeholder"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p>Ngũ Hành Sơn, Đà Nẵng, Việt Nam</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-call"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p><a href="tel:+84967137200">0967.137.200</a></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="footer-address-icon">
                                        <i class="icon-mail"></i>
                                    </div>
                                    <div class="footer-address-info">
                                        <p><a href="mailto:daucaothanh2004@gmail.com">daucaothanh2004@gmail.com</a></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="ltn__social-media mt-20">
                            <ul>
                                <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="https://www.instagram.com/vay_16th2" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i class="fas fa-comment-dots"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Khám phá</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ url('/') }}">Trang chủ</a></li>
                                <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                                <li><a href="{{ route('products.index') }}">Sản phẩm võ thuật</a></li>
                                <li><a href="{{ url('/service') }}">Dịch vụ & May đo</a></li>
                                <li><a href="{{ route('contact') }}">Liên hệ võ đường</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Tài khoản & Đơn hàng</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ route('account') }}">Tài khoản cá nhân</a></li>
                                <li><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                                <li><a href="{{ route('wishlist.index') }}">Danh sách yêu thích</a></li>
                                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a href="{{ route('register') }}">Đăng ký</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Hỗ trợ khách hàng</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ url('/faq') }}">Câu hỏi thường gặp</a></li>
                                <li><a href="{{ url('/about') }}">Chính sách đổi trả 7 ngày</a></li>
                                <li><a href="{{ url('/about') }}">Bảng hướng dẫn chọn size</a></li>
                                <li><a href="{{ route('contact') }}">Tư vấn trực tiếp</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-12 col-12">
                    <div class="footer-widget footer-newsletter-widget">
                        <h4 class="footer-title">Nhận ưu đãi Karate</h4>
                        <p>Đăng ký nhận tin tức các đợt khuyến mãi và sản phẩm mới từ karate_do.shop.</p>
                        <div class="footer-newsletter">
                            <form action="#">
                                <input type="email" name="email" placeholder="Email của bạn*">
                                <div class="btn-wrapper">
                                    <button class="theme-btn-1 btn" type="submit"><i class="fas fa-location-arrow"></i></button>
                                </div>
                            </form>
                        </div>
                        <h5 class="mt-30">Chấp nhận thanh toán</h5>
                        <img src="{{ asset('assets/clients/img/icons/payment-4.png') }}" alt="Payment Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ltn__copyright-area ltn__copyright-2 section-bg-2 ltn__border-top-2 plr--5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="ltn__copyright-design clearfix">
                        <p>© {{ date('Y') }} karate_do.shop - Bản quyền thuộc về Võ đường Karate-Do.</p>
                    </div>
                </div>
                <div class="col-md-6 col-12 align-self-center">
                    <div class="ltn__copyright-menu text-end">
                        <ul>
                            <li><a href="{{ url('/about') }}">Điều khoản</a></li>
                            <li><a href="{{ url('/about') }}">Bảo mật</a></li>
                            <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER AREA END -->