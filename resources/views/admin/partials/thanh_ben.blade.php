@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && $currentUser->isAdmin();
@endphp

<aside class="admin-sidebar">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <img src="{{ asset('assets/admin/images/logo.png') }}" alt="Karate-Do Logo">
        <div class="sidebar-brand-text">
            KARATE-DO <span>ADMIN</span>
        </div>
    </a>

    <!-- User Profile Widget -->
    <div class="sidebar-user">
        @if($currentUser && $currentUser->avatar)
            <img src="{{ asset($currentUser->avatar) }}" alt="{{ $currentUser->name }}">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->name ?? 'Admin') }}&background=d32f2f&color=fff&size=100" alt="Avatar">
        @endif
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ $currentUser->name ?? 'Quản trị viên' }}</div>
            @if($isAdmin)
                <span class="sidebar-user-role role-admin"><i class="fa-solid fa-shield-halved mr-1"></i> Quản trị viên</span>
            @else
                <span class="sidebar-user-role role-staff"><i class="fa-solid fa-user-tie mr-1"></i> Nhân viên</span>
            @endif
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <div class="sidebar-heading">TỔNG QUAN & KINH DOANH</div>
        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Bảng điều khiển</span>
                </a>
            </li>

            <!-- Shifts & Work Logs -->
            <li class="{{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                <a href="{{ route('admin.shifts.index') }}">
                    <i class="fa-solid fa-business-time text-warning"></i>
                    <span>Ca trực & Chấm công</span>
                </a>
            </li>

            <!-- Reports & Analytics -->
            <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.index') }}">
                    <i class="fa-solid fa-chart-pie text-info"></i>
                    <span>Báo cáo kinh doanh</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-heading mt-2">QUẢN LÝ BÁN HÀNG</div>
        <ul class="sidebar-menu">
            <!-- Orders -->
            <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}" class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Quản lý đơn hàng</span>
                    </div>
                    @if($currentUser && $currentUser->isStaff() && !$currentUser->isAdmin())
                        @php
                            $sidebarShiftCount = 0;
                            try {
                                $sidebarQuery = \App\Models\Order::where('status', 'pending');
                                if ($currentUser->default_shift_id) {
                                    $sidebarQuery->where(function($q) use ($currentUser) {
                                        $q->where('shift_id', $currentUser->default_shift_id);
                                        if ($currentUser->defaultShift) {
                                            $q->orWhere('shift_name', 'like', '%' . $currentUser->defaultShift->name . '%');
                                        }
                                    });
                                }
                                $sidebarShiftCount = $sidebarQuery->count();
                            } catch(\Exception $e) {}
                        @endphp
                        <span class="badge badge-danger sidebar-badge sidebar-order-count {{ $sidebarShiftCount > 0 ? '' : 'd-none' }}" title="{{ $sidebarShiftCount }} đơn chờ duyệt trong ca">
                            {{ $sidebarShiftCount }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Products -->
            <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}">
                    <i class="fa-solid fa-shirt"></i>
                    <span>Sản phẩm võ thuật</span>
                </a>
            </li>

            <!-- Categories -->
            <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Danh mục sản phẩm</span>
                </a>
            </li>

            <!-- Reviews -->
            <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}">
                    <i class="fa-solid fa-star"></i>
                    <span>Đánh giá sản phẩm</span>
                </a>
            </li>

            <!-- Contacts -->
            <li class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <a href="{{ route('admin.contacts.index') }}">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    <span>Liên hệ & Tư vấn</span>
                </a>
            </li>
        </ul>

        @if($isAdmin)
        <div class="sidebar-heading mt-2">HỆ THỐNG</div>
        <ul class="sidebar-menu">
            <!-- Users & Staff (Admin only) -->
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class="fa-solid fa-users-gear text-danger"></i>
                    <span>Tài khoản & Nhân viên</span>
                    <span class="badge badge-danger sidebar-badge">Admin</span>
                </a>
            </li>
        </ul>
        @endif

        <div class="sidebar-heading mt-2">CÁ NHÂN</div>
        <ul class="sidebar-menu">
            <!-- Profile -->
            <li class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <a href="{{ route('admin.profile.index') }}">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Hồ sơ của tôi</span>
                </a>
            </li>

            <!-- Live Store -->
            <li>
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Xem Cửa hàng Live</span>
                </a>
            </li>

            <!-- Logout -->
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="text-danger">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                    <span>Đăng xuất</span>
                </a>
                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</aside>
