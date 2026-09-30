@php
    $currentUser = auth()->user();
    $activeShift = null;
    $shiftPendingCount = 0;
    $shiftPendingOrders = collect();
    $myShift = null;

    if($currentUser) {
        try {
            $activeShift = \App\Models\StaffWorkLog::where('user_id', $currentUser->id)->where('status', 'active')->latest()->first();
            $myShift = $currentUser->defaultShift;

            $notifQuery = \App\Models\Order::with(['shippingAddress', 'user'])->where('status', 'pending');
            if ($currentUser->isStaff() && !$currentUser->isAdmin()) {
                if ($currentUser->default_shift_id) {
                    $notifQuery->where(function($q) use ($currentUser, $myShift) {
                        $q->where('shift_id', $currentUser->default_shift_id);
                        if ($myShift) {
                            $q->orWhere('shift_name', 'like', '%' . $myShift->name . '%');
                        }
                    });
                }
            }
            $shiftPendingCount = $notifQuery->count();
            $shiftPendingOrders = $notifQuery->latest()->take(5)->get();
        } catch (\Exception $e) {}
    }
@endphp

<header class="admin-header">
    <div class="header-left">
        <span class="header-system-tag">
            <i class="fa-solid fa-shield-halved text-danger mr-2" aria-hidden="true"></i>
            Hệ thống Quản trị Karate-Do Shop
        </span>
    </div>

    <div class="header-right d-flex align-items-center">
        @if($currentUser && $currentUser->isStaff() && !$currentUser->isAdmin())
            <!-- Staff Assigned Shift & Permission Status Badge -->
            <div class="header-shift-context d-flex align-items-center">
                <span class="header-shift-label" title="{{ $myShift ? $myShift->name : 'Chưa phân ca' }}">
                    <i class="fa-solid fa-user-clock text-danger mr-1"></i> {{ $myShift ? $myShift->name : 'Chưa phân ca' }}
                </span>
                @if($currentUser->canApproveOrders())
                    <span class="header-permission header-permission-active" title="Bạn đang trong ca trực được phân công - Được phép duyệt đơn">
                        <i class="fa-solid fa-circle-check mr-1"></i> Đúng ca trực (Duyệt đơn)
                    </span>
                @else
                    <span class="header-permission header-permission-idle" title="Bạn đang vào ngoài ca làm việc - Chỉ có quyền xem">
                        <i class="fa-solid fa-eye mr-1"></i> Chế độ xem (Ngoài ca)
                    </span>
                @endif
            </div>
        @elseif($currentUser && $currentUser->isAdmin())
            <span class="header-permission header-admin-role" title="Tài khoản Admin quản trị, việc duyệt đơn do nhân viên trực ca thực hiện">
                <i class="fa-solid fa-user-shield mr-1"></i> Admin (Không duyệt đơn)
            </span>
        @endif

        <!-- Work Shift Status Pill Button -->
        <a href="{{ route('admin.shifts.index') }}" class="btn-shift-pill {{ $activeShift ? 'btn-shift-active' : 'btn-shift-idle' }}" title="{{ $activeShift ? 'Đang trực: '.$activeShift->shift_name : 'Chưa vào ca làm việc — Xem và quản lý ca trực' }}">
            <i class="fa-solid fa-business-time"></i>
            @if($activeShift)
                <span><strong>Đang trực:</strong> {{ $activeShift->shift_name }}</span>
            @else
                <span>Chưa vào ca làm việc</span>
            @endif
        </a>

        <!-- Shift Order Notification Dropdown -->
        <div class="dropdown" id="shiftNotifDropdownWrapper">
            <button class="btn btn-light position-relative border-0 shadow-none text-dark d-flex align-items-center justify-content-center" 
                    id="shiftNotifDropdown" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="false" 
                    style="width: 38px; height: 38px; border-radius: 50%; background: #f3f4f6;" 
                    title="Thông báo đơn hàng theo ca trực">
                <i class="fa-solid fa-bell text-secondary" style="font-size: 16px;"></i>
                <span class="badge badge-danger position-absolute badge-notif-count {{ $shiftPendingCount > 0 ? '' : 'd-none' }}" 
                      id="headerNotifBadge" 
                      style="top: -2px; right: -2px; font-size: 10px; padding: 3px 6px; border-radius: 10px;">
                    {{ $shiftPendingCount }}
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 rounded-lg p-0" 
                 aria-labelledby="shiftNotifDropdown" 
                 style="width: 350px; margin-top: 10px; max-height: 480px; overflow: hidden; z-index: 1050;">
                <!-- Dropdown Header -->
                <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);">
                    <div>
                        <h6 class="mb-0 font-weight-bold" style="font-size: 14px;">
                            <i class="fa-solid fa-bell mr-1"></i> Thông báo đơn ca trực
                        </h6>
                        <small class="text-white-50">
                            @if($currentUser && $currentUser->isStaff() && !$currentUser->isAdmin())
                                Ca của bạn: <strong>{{ $myShift ? $myShift->name : 'Chưa phân ca' }}</strong>
                            @else
                                Quản trị viên (Xem toàn hệ thống)
                            @endif
                        </small>
                    </div>
                    <span class="badge badge-light text-danger font-weight-bold px-2 py-1" id="headerNotifCountTag">
                        {{ $shiftPendingCount }} đơn chờ
                    </span>
                </div>

                <!-- Dropdown List Items -->
                <div class="p-0 overflow-auto" id="headerNotifList" style="max-height: 300px;">
                    @forelse($shiftPendingOrders as $item)
                        <a href="{{ route('admin.orders.show', $item->id) }}" class="dropdown-item d-flex align-items-start p-3 border-bottom text-wrap" style="white-space: normal;">
                            <div class="rounded-circle p-2 mr-3 bg-danger-light text-danger flex-shrink-0" style="background: #fee2e2; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-cart-arrow-down"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark font-size-13">Đơn #{{ $item->id }}</strong>
                                    <span class="badge badge-warning text-dark font-weight-normal" style="font-size: 10px;">Chờ duyệt</span>
                                </div>
                                <div class="text-dark small font-weight-500">{{ $item->shippingAddress->fullname ?? ($item->user->name ?? 'Khách đặt') }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-1 text-muted" style="font-size: 11px;">
                                    <span class="text-danger font-weight-bold">{{ number_format($item->total_price, 0, ',', '.') }} ₫</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i>{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted" id="headerNotifEmpty">
                            <i class="fa-solid fa-circle-check fa-2x text-success mb-2 d-block"></i>
                            <div class="small font-weight-bold text-dark">Tuyệt vời! Không có đơn chờ duyệt</div>
                            <div class="text-muted" style="font-size: 11px;">Mọi đơn hàng trong ca trực đã được xử lý.</div>
                        </div>
                    @endforelse
                </div>

                <!-- Dropdown Footer -->
                <div class="p-2 bg-light text-center border-top">
                    <a href="{{ route('admin.orders.index', array_filter(['status' => 'pending', 'shift_name' => ($currentUser && $currentUser->isStaff() && !$currentUser->isAdmin() && $myShift) ? $myShift->name : null])) }}" 
                       class="btn btn-sm btn-link text-danger font-weight-bold p-0" style="font-size: 12px; text-decoration: none;">
                        Xem danh sách đơn chờ duyệt <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Live Store Link -->
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="header-store-link d-inline-flex" title="Xem cửa hàng" aria-label="Xem cửa hàng">
            <i class="fa-solid fa-store" aria-hidden="true"></i><span>Xem Cửa hàng</span>
        </a>

        <!-- User Dropdown Menu -->
        <div class="dropdown">
            <button type="button" class="header-user-btn" id="adminUserDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ $currentUser->name ?? 'Quản trị viên' }}">
                @if($currentUser && $currentUser->avatar)
                    <img src="{{ asset($currentUser->avatar) }}" alt="{{ $currentUser->name }}" class="header-user-avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->name ?? 'Admin') }}&background=d32f2f&color=fff&size=80" alt="Avatar" class="header-user-avatar">
                @endif
                <span class="header-user-name">{{ $currentUser->name ?? 'Quản trị viên' }}</span>
                <i class="fa-solid fa-chevron-down text-muted" style="font-size: 11px;"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right shadow border-0 rounded-lg p-2" aria-labelledby="adminUserDropdown" style="min-width: 210px; margin-top: 10px;">
                <div class="px-3 py-2 border-bottom">
                    <strong class="d-block text-dark">{{ $currentUser->name ?? 'User' }}</strong>
                    <small class="text-muted">{{ $currentUser->email ?? '' }}</small>
                </div>
                <a class="dropdown-item py-2 mt-1 rounded" href="{{ route('admin.profile.index') }}">
                    <i class="fa-solid fa-user-circle mr-2 text-primary"></i> Thông tin cá nhân
                </a>
                <a class="dropdown-item py-2 rounded" href="{{ route('admin.profile.index') }}#password-section">
                    <i class="fa-solid fa-key mr-2 text-warning"></i> Đổi mật khẩu
                </a>
                <a class="dropdown-item py-2 rounded" href="{{ route('admin.open') }}" target="_blank" rel="noopener" title="Mở tài khoản khác trong tab mới">
                    <i class="fa-solid fa-user-plus mr-2 text-primary" aria-hidden="true"></i> Mở tài khoản khác
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item py-2 rounded text-danger" href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form-top').submit();">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Đăng xuất
                </a>
                <form id="admin-logout-form-top" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
