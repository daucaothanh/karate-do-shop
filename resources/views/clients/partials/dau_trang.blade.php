@php
    $navCartCount = auth()->check() ? auth()->user()->cartItems()->sum('quantity') : (session('cart') ? count(session('cart')) : 0);
    $navWishlistCount = auth()->check() ? \App\Models\wishlist::where('user_id', auth()->id())->count() : 0;
    $navOrdersCount = auth()->check() ? auth()->user()->orders()->count() : 0;
    $navCartItems = auth()->check() ? auth()->user()->cartItems()->with('product')->get() : collect();
    $navCartSubtotal = $navCartItems->sum(fn ($ci) => $ci->quantity * ($ci->product->price ?? 0));
    $navNotifications = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get() : collect();
    $navUnreadNotifCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('is_read', 0)->count() : 0;
@endphp

<!-- HEADER AREA START (header-5) -->
<header class="ltn__header-area ltn__header-5 ltn__header-transparent-- gradient-color-4---">
    <!-- ltn__header-top-area start -->
    <div class="ltn__header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="ltn__top-bar-menu">
                        <ul>
                            <li><a href="{{ route('contact') }}"><i class="icon-placeholder"></i> Ngũ Hành Sơn, Đà Nẵng</a></li>
                            <li><a href="mailto:daucaothanh2004@gmail.com?Subject=Contact%20with%20to%20you"><i class="icon-mail"></i> daucaothanh2004@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="top-bar-right text-right text-end">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li>
                                    <!-- ltn__social-media -->
                                    <div class="ltn__social-media">
                                        <ul>
                                            <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="https://www.instagram.com/vay_16th2" title="Instagram" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i class="fas fa-comment-dots"></i></a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-top-area end -->

    <!-- ltn__header-middle-area start -->
    <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white sticky-active-into-mobile ltn__logo-right-menu-option plr--9---">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="site-logo-wrap">
                        <div class="site-logo site-logo-small">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/clients/img/logo.png') }}" alt="Logo">
                                <span>karate_do.shop</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col header-menu-column menu-color-white---">
                    <div class="header-menu d-none d-xl-block">
                        <nav>
                            <div class="ltn__main-menu">
                                <ul>
                                    <li class="menu-icon"><a href="{{ url('/') }}">Trang chủ</a></li>
                                    <li class="menu-icon"><a href="{{ url('/about') }}">Về chúng tôi</a>
                                        <ul>
                                            <li><a href="{{ url('/about') }}">Về chúng tôi</a></li>
                                            <li><a href="{{ url('/service') }}">Dịch vụ</a></li>
                                            <li><a href="{{ url('/team') }}">Đội ngũ</a></li>
                                            <li><a href="{{ url('/faq') }}">FAQ</a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-icon"><a href="{{ route('products.index') }}">Cửa hàng</a></li>
                                    <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                                    <li class="special-link"><a href="{{ route('contact') }}">LIÊN HỆ</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="ltn__header-options ltn__header-options-2 mb-sm-20">
                    <!-- header-search-1 -->
                    <div class="header-search-wrap">
                        <div class="header-search-1">
                            <div class="search-icon">
                                <i class="icon-search for-search-show"></i>
                                <i class="icon-cancel for-search-close"></i>
                            </div>
                        </div>
                        <div class="header-search-1-form">
                            <form method="get" action="{{ route('products.index') }}">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm..." />
                                <button type="submit">
                                    <span><i class="icon-search"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!-- user-menu -->
                    <div class="ltn__drop-menu user-menu">
                        <ul>
                            <li>
                                <a href="#"><i class="icon-user"></i></a>
                                <ul>
                                    @guest
                                        <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                        <li><a href="{{ route('register') }}">Đăng ký</a></li>
                                        <li><a href="{{ route('admin.login') }}">Đăng nhập Quản trị</a></li>
                                    @else
                                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                            <li><a href="{{ route('admin.dashboard') }}" style="color: #d32f2f; font-weight: 600;"><i class="fas fa-shield-alt"></i> Trang Quản trị</a></li>
                                        @endif
                                        <li><a href="{{ route('account') }}"><i class="fas fa-user mr-1"></i> Tài khoản: {{ auth()->user()->name }}</a></li>
                                        <li><a href="{{ route('orders.index') }}" style="color: #d32f2f; font-weight: 600;"><i class="fas fa-box-open mr-1"></i> Sản phẩm đã mua ({{ $navOrdersCount }})</a></li>
                                        <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart mr-1"></i> Yêu thích ({{ $navWishlistCount }})</a></li>
                                        <li><a href="{{ route('logout.get') }}"><i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất</a></li>
                                    @endguest
                                </ul>
                            </li>
                        </ul>
                    </div>

                    @auth
                    <!-- notification-bell -->
                    <div class="ltn__drop-menu user-menu mr-2" style="position: relative;">
                        <ul>
                            <li>
                                <a href="#" title="Thông báo của bạn" style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b;">
                                    <i class="fa-solid fa-bell" style="font-size: 16px;"></i>
                                    @if($navUnreadNotifCount > 0)
                                        <span class="badge rounded-circle" style="position: absolute; top: -4px; right: -4px; font-size: 10px; min-width: 18px; height: 18px; padding: 2px 4px; background: #dc2626; color: #fff;">{{ $navUnreadNotifCount }}</span>
                                    @endif
                                </a>
                                <ul style="width: 320px; right: 0; left: auto; padding: 0; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.12); border: 1px solid #e2e8f0;">
                                    <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center" style="background: #fafafa;">
                                        <strong class="text-dark" style="font-size: 13.5px;"><i class="fa-solid fa-bell text-danger mr-1"></i> Thông báo duyệt đơn</strong>
                                        <span class="badge badge-light border text-danger" style="font-size: 11px;">{{ $navUnreadNotifCount }} chưa đọc</span>
                                    </li>
                                    @forelse($navNotifications as $notif)
                                        <li class="border-bottom" style="margin: 0;">
                                            <a href="{{ $notif->link ?: route('orders.index') }}" class="px-3 py-2 d-block text-wrap" style="line-height: 1.4; background: {{ $notif->is_read ? '#ffffff' : '#fff5f5' }};">
                                                <div class="font-weight-bold text-danger mb-1" style="font-size: 12.5px;">
                                                    <i class="fa-solid fa-circle-check mr-1"></i> Thông báo từ Shop
                                                </div>
                                                <div class="text-dark small mb-1" style="font-size: 12px;">{{ $notif->message }}</div>
                                                <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}</small>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="px-3 py-4 text-center text-muted small" style="margin: 0;">
                                            <i class="fa-solid fa-bell-slash fa-2x mb-2 d-block text-muted"></i>
                                            Chưa có thông báo duyệt đơn mới.
                                        </li>
                                    @endforelse
                                </ul>
                            </li>
                        </ul>
                    </div>
                    @endauth

                    <!-- mini-cart -->
                    <div class="mini-cart-icon">
                        <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle" title="Giỏ hàng">
                            <i class="icon-shopping-cart"></i>
                            <sup id="headerCartCountBadge">{{ $navCartCount }}</sup>
                        </a>
                    </div>
                    <!-- mini-cart -->
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-middle-area end -->
</header>
<!-- HEADER AREA END -->

<!-- Utilize Cart Menu Start -->
<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title font-weight-bold">Giỏ hàng của bạn ({{ $navCartCount }})</span>
            <button class="ltn__utilize-close">×</button>
        </div>

        @if($navCartItems->isNotEmpty())
            <div class="ltn__utilize-menu-cart-wrap" style="max-height: 380px; overflow-y: auto; margin-bottom: 20px;">
                @foreach($navCartItems as $ci)
                    @php
                        $ciImg = $ci->product->image ?: ($ci->product->images->first()->image ?? 'assets/clients/img/karate/vo-phuc-rikaido.png');
                    @endphp
                    <div class="d-flex align-items-center py-2 border-bottom">
                        <img src="{{ asset($ciImg) }}" alt="{{ $ci->product->name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; border: 1px solid #e2e8f0; margin-right: 12px;">
                        <div class="flex-grow-1" style="line-height: 1.3;">
                            <a href="{{ route('products.show', $ci->product->slug) }}" class="font-weight-bold text-dark d-block text-truncate" style="max-width: 170px; font-size: 13px;">
                                {{ $ci->product->name }}
                            </a>
                            <div style="font-size: 11px; color: #64748b;">
                                @if($ci->color)<span class="text-danger">Màu: {{ $ci->color }}</span>@endif
                                @if($ci->color && $ci->size) | @endif
                                @if($ci->size)<span>Size: {{ $ci->size }}</span>@endif
                            </div>
                            <div class="font-weight-bold text-danger" style="font-size: 12.5px;">
                                {{ $ci->quantity }} × {{ number_format($ci->product->price, 0, ',', '.') }} ₫
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between font-weight-bold mb-3" style="font-size: 15px;">
                <span>Tổng tạm tính:</span>
                <span id="miniCartSubtotal" class="text-danger">{{ number_format($navCartSubtotal, 0, ',', '.') }} ₫</span>
            </div>

            <div class="btn-wrapper">
                <a href="{{ route('cart.index') }}" class="theme-btn-1 btn btn-effect-1 d-block text-center mb-2">Xem giỏ hàng</a>
                <a href="{{ route('checkout.create') }}" class="theme-btn-2 btn btn-effect-2 d-block text-center">Tiến hành thanh toán</a>
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="fa fa-shopping-cart fa-3x mb-2 d-block text-muted"></i>
                <p class="mb-3">Giỏ hàng của bạn đang trống.</p>
                <a href="{{ route('products.index') }}" class="theme-btn-1 btn btn-effect-1 btn-sm">Mua sắm ngay</a>
            </div>
        @endif

        <p class="small text-muted mt-3 text-center">Miễn phí vận chuyển cho tất cả đơn hàng từ 500.000 VNĐ</p>
    </div>
</div>
<!-- Utilize Cart Menu End -->

@include('clients.partials.tien_ich_di_dong')

<div class="ltn__utilize-overlay"></div>