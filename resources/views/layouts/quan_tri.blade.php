<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị') | KARATE-DO SHOP</title>

    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Modern Dashboard CSS -->
    <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('admin.partials.thanh_ben')

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Header -->
            @include('admin.partials.thanh_dieu_huong')

            <!-- Page Content -->
            <main class="admin-content">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #10b981 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-check-circle mr-2 text-success" style="font-size: 18px;"></i>
                            <div><strong>Thành công!</strong> {{ session('success') }}</div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #ef4444 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-exclamation-triangle mr-2 text-danger" style="font-size: 18px;"></i>
                            <div><strong>Lỗi!</strong> {{ session('error') }}</div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-left: 4px solid #f59e0b !important;">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-exclamation-circle mr-2 text-warning" style="font-size: 18px;"></i>
                            <strong>Vui lòng kiểm tra lại các mục sau:</strong>
                        </div>
                        <ul class="mb-0 pl-4 text-dark">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Banner Thông báo Đơn hàng theo ca trực cho Nhân viên -->
                @php
                    $bannerUser = auth()->user();
                    $bannerShift = $bannerUser ? $bannerUser->defaultShift : null;
                    $bannerPendingCount = 0;
                    if($bannerUser && $bannerUser->isStaff() && !$bannerUser->isAdmin()) {
                        try {
                            $bannerQuery = \App\Models\Order::where('status', 'pending');
                            if ($bannerUser->default_shift_id) {
                                $bannerQuery->where(function($q) use ($bannerUser, $bannerShift) {
                                    $q->where('shift_id', $bannerUser->default_shift_id);
                                    if ($bannerShift) {
                                        $q->orWhere('shift_name', 'like', '%' . $bannerShift->name . '%');
                                    }
                                });
                            }
                            $bannerPendingCount = $bannerQuery->count();
                        } catch (\Exception $e) {}
                    }
                @endphp

                @if($bannerUser && $bannerUser->isStaff() && !$bannerUser->isAdmin() && $bannerPendingCount > 0)
                    <div class="alert alert-danger shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between p-3 mb-4" 
                         style="background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%); border-left: 5px solid #dc2626 !important; border-radius: 8px;">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <div class="badge badge-danger p-2 mr-3" style="font-size: 18px; border-radius: 8px;">
                                <i class="fa-solid fa-bell fa-shake"></i>
                            </div>
                            <div>
                                <strong class="text-danger font-weight-bold d-block" style="font-size: 15px;">
                                    <i class="fa-solid fa-bullhorn mr-1"></i> THÔNG BÁO CA TRỰC: {{ $bannerShift ? $bannerShift->name : 'Ca làm việc' }}
                                </strong>
                                <span class="text-dark small">
                                    Hiện có <strong class="text-danger font-weight-bold" style="font-size: 15px;">{{ $bannerPendingCount }}</strong> đơn hàng mới đang chờ duyệt trong ca trực của bạn!
                                    @if($bannerUser->canApproveOrders())
                                        Hãy kiểm tra và duyệt đơn kịp thời.
                                    @else
                                        (Bạn đang ngoài ca trực phân công, chỉ có quyền xem).
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div>
                            @if($bannerUser->canApproveOrders())
                                <a href="{{ route('admin.orders.index', array_filter(['status' => 'pending', 'shift_name' => $bannerShift ? $bannerShift->name : null])) }}" 
                                   class="btn btn-danger font-weight-bold btn-sm shadow-sm px-3 py-2">
                                    <i class="fa-solid fa-bolt mr-1"></i> Xử lý & Duyệt ngay ({{ $bannerPendingCount }})
                                </a>
                            @else
                                <a href="{{ route('admin.orders.index', array_filter(['status' => 'pending', 'shift_name' => $bannerShift ? $bannerShift->name : null])) }}" 
                                   class="btn btn-outline-danger font-weight-bold btn-sm px-3 py-2">
                                    <i class="fa-solid fa-eye mr-1"></i> Xem danh sách ({{ $bannerPendingCount }})
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Banner yêu cầu đổi ca đang chờ admin xử lý -->
                @php
                    $pendingShiftChangeRequests = collect();
                    if ($bannerUser && $bannerUser->isAdmin()) {
                        try {
                            $pendingShiftChangeRequests = \App\Models\ShiftChangeRequest::with(['user', 'requestedShift'])
                                ->where('status', 'pending')
                                ->orderBy('work_date')
                                ->latest()
                                ->take(5)
                                ->get();
                        } catch (\Throwable $e) {
                            $pendingShiftChangeRequests = collect();
                        }
                    }
                @endphp

                @if($pendingShiftChangeRequests->isNotEmpty())
                    <div class="alert shadow-sm d-flex flex-wrap align-items-center justify-content-between p-3 mb-4" role="alert"
                         style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1px solid #fb923c; border-left: 5px solid #ea580c; border-radius: 8px;">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <div class="p-2 mr-3 text-white" style="background: #ea580c; border-radius: 8px; font-size: 20px;">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark font-weight-bold" style="font-size: 15px;">
                                    <i class="fa-solid fa-bell text-danger mr-1"></i>
                                    CÓ {{ $pendingShiftChangeRequests->count() }} YÊU CẦU ĐỔI CA ĐANG CHỜ DUYỆT
                                </strong>
                                <span class="small text-dark">
                                    {{ $pendingShiftChangeRequests->first()->user->name ?? 'Nhân viên' }} xin đổi ca ngày
                                    {{ $pendingShiftChangeRequests->first()->work_date->format('d/m/Y') }}.
                                    Vui lòng xử lý sớm để chốt lịch làm việc.
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('admin.shifts.index') }}" class="btn btn-warning font-weight-bold shadow-sm">
                            <i class="fa-solid fa-bolt mr-1"></i> XỬ LÝ NGAY
                        </a>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('admin.partials.chan_trang')
        </div>
    </div>

    <!-- jQuery and Bootstrap 4 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>

    <!-- Shift Order Notification Real-time Poller & Sound -->
    @if(auth()->check())
    <script>
        (function() {
            let lastNotifCount = {{ $bannerPendingCount ?? 0 }};
            let firstLoad = true;

            function playShiftOrderChime() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    const osc1 = ctx.createOscillator();
                    const osc2 = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                    osc1.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5

                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);

                    osc1.connect(gain);
                    gain.connect(ctx.destination);

                    osc1.start();
                    osc1.stop(ctx.currentTime + 0.5);
                } catch(e) {}
            }

            function checkShiftNotifications() {
                $.ajax({
                    url: '{{ route("admin.orders.notifications") }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        const count = res.count || 0;
                        const badge = $('#headerNotifBadge');
                        const countTag = $('#headerNotifCountTag');
                        const sidebarBadge = $('.sidebar-order-count');

                        if (count > 0) {
                            badge.removeClass('d-none').text(count);
                            countTag.text(count + ' đơn chờ');
                            sidebarBadge.removeClass('d-none').text(count);
                        } else {
                            badge.addClass('d-none');
                            countTag.text('0 đơn chờ');
                            sidebarBadge.addClass('d-none');
                        }

                        // Nếu có đơn mới xuất hiện so với lần kiểm tra trước
                        if (!firstLoad && count > lastNotifCount && res.orders && res.orders.length > 0) {
                            playShiftOrderChime();
                            const latestOrder = res.orders[0];
                            if (window.Swal) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'info',
                                    title: 'Đơn hàng mới trong ca trực!',
                                    html: `<strong>Đơn #${latestOrder.id}</strong> - ${latestOrder.customer}<br>Tổng: <span class="text-danger font-weight-bold">${latestOrder.total}</span><br><a href="${latestOrder.url}" class="btn btn-sm btn-danger mt-2 font-weight-bold">Xử lý duyệt ngay</a>`,
                                    showConfirmButton: false,
                                    timer: 10000,
                                    timerProgressBar: true
                                });
                            }
                        }

                        lastNotifCount = count;
                        firstLoad = false;
                    }
                });
            }

            // Tự động kiểm tra mỗi 25 giây
            setInterval(checkShiftNotifications, 25000);
        })();
    </script>
    @endif

    @stack('scripts')
</body>
</html>
