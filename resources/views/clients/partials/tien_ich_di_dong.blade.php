<!-- Utilize Mobile Menu Start -->
        <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
            <div class="ltn__utilize-menu-inner ltn__scrollbar">
                <div class="ltn__utilize-menu-head">
                    <div class="site-logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('assets/clients/img/logo.png') }}" alt="Logo"></a>
                    </div>
                    <button class="ltn__utilize-close">×</button>
                </div>
                <div class="ltn__utilize-menu-search-form">
                    <form action="{{ route('products.index') }}" method="GET">
                        <input type="text" name="q" placeholder="Tìm sản phẩm...">
                        <button><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="ltn__utilize-menu">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a> </li>
                        <li><a href="#">Về chúng tôi</a>
                            <ul class="sub-menu">
                                <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                                <li><a href="{{ url('/service') }}">Dịch vụ</a></li>
                                <li><a href="{{ url('/team') }}">Đội ngũ</a></li>
                                <li><a href="{{ url('/faq') }}">FAQ</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('products.index') }}">Cửa hàng</a></li>
                        <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="ltn__utilize-buttons ltn__utilize-buttons-2">
                    <ul>
                        <li>
                            <a href="{{ route('account') }}" title="Tài khoản">
                                <span class="utilize-btn-icon">
                                    <i class="far fa-user"></i>
                                </span>
                                Tài khoản
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.index') }}" title="Đơn hàng đã mua">
                                <span class="utilize-btn-icon">
                                    <i class="fas fa-box-open"></i>
                                    <sup>{{ $navOrdersCount ?? 0 }}</sup>
                                </span>
                                Đơn mua
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wishlist.index') }}" title="Yêu thích">
                                <span class="utilize-btn-icon">
                                    <i class="far fa-heart"></i>
                                    <sup>{{ $navWishlistCount ?? 0 }}</sup>
                                </span>
                                Yêu thích
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('cart.index') }}" title="Giỏ hàng">
                                <span class="utilize-btn-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                    <sup id="mobileCartCountBadge">{{ $navCartCount ?? 0 }}</sup>
                                </span>
                                Giỏ hàng
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="ltn__social-media-2">
                    <ul>
                        <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
                        <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Utilize Mobile Menu End -->