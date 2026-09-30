@extends('layouts.quan_tri')

@section('title', 'Quản lý Ca làm việc & Chấm công')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3><i class="fa-solid fa-business-time text-danger mr-2"></i> QUẢN LÝ CA LÀM VIỆC & CHẤM CÔNG NHÂN VIÊN</h3>
    </div>
    <div class="title_right text-right">
        @if(auth('admin')->user()->isAdmin())
            <a href="{{ route('admin.shifts.payroll') }}" class="btn btn-primary">Ngày công & Lương tháng</a>
        @endif
        <a href="{{ route('admin.shifts.export', request()->query()) }}" class="btn btn-outline-success font-weight-bold">
            <i class="fa-solid fa-file-excel mr-1"></i> Xuất file Excel / CSV
        </a>
    </div>
</div>

<div class="clearfix"></div>

<!-- Row: My Current Shift Widget & Today's Shift Performance -->
<div class="row">
    <!-- Shift Check-In / Check-Out Widget -->
    <div class="col-lg-5 col-md-12">
        <div class="x_panel" style="border-top: 4px solid #d32f2f;">
            <div class="x_title">
                <h2><i class="fa-solid fa-user-clock text-danger mr-1"></i> Ca trực của tôi</h2>
                <span class="badge badge-dark p-2" id="liveClock"><i class="fa-regular fa-clock mr-1"></i> 00:00:00</span>
                <div class="clearfix"></div>
            </div>
            <div class="x_content text-center py-3">
                @if($currentWorkLog)
                    <div class="alert alert-success text-left mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-success"><i class="fa-solid fa-circle-dot fa-beat-fade mr-1"></i> Đang trong ca trực</strong>
                            <span class="badge badge-success font-weight-bold">{{ $currentWorkLog->shift_name }}</span>
                        </div>
                        <div class="small text-muted">Bắt đầu check-in lúc: <strong>{{ $currentWorkLog->check_in_at ? $currentWorkLog->check_in_at->format('H:i:s - d/m/Y') : 'N/A' }}</strong></div>
                        <div class="small text-muted">Thời gian đã trực: <strong class="text-dark">{{ $currentWorkLog->duration_formatted }}</strong></div>
                        <div class="small text-muted">Đơn hàng đã xử lý trong ca: <strong class="text-primary">{{ $currentWorkLog->orders_handled_count }} đơn</strong> ({{ number_format($currentWorkLog->total_revenue_handled, 0, ',', '.') }} ₫)</div>
                    </div>

                    <!-- Checkout Form Button -->
                    <button type="button" class="btn btn-danger btn-block font-weight-bold py-2 shadow-sm" data-toggle="modal" data-target="#checkOutModal">
                        <i class="fa-solid fa-right-from-bracket mr-1"></i> KẾT THÚC CA LÀM VIỆC (CHECK-OUT)
                    </button>
                @else
                    @php
                        $user = auth()->user();
                        $canCheckIn = $user ? $user->canOperateInCurrentShift() : false;
                        $curShiftName = \App\Models\WorkShift::getCurrentShiftName();
                        $myShiftName = $todayAssignedShift ? $todayAssignedShift->name : 'Chưa phân ca';
                    @endphp

                    @if($user && $user->isAdmin())
                        <div class="p-3 bg-light rounded border text-muted mb-3 text-left">
                            <i class="fa-solid fa-user-shield fa-2x mb-2 text-primary d-block"></i>
                            <div class="text-dark font-weight-bold mb-1">TÀI KHOẢN QUẢN TRỊ VIÊN (ADMIN)</div>
                            <div class="small text-muted">Admin quản lý hệ thống và theo dõi chấm công toàn bộ nhân viên. Chức năng chấm công và duyệt đơn hàng dành riêng cho Nhân viên trực ca.</div>
                        </div>
                    @elseif($canCheckIn)
                        <div class="p-3 bg-light rounded border text-muted mb-3">
                            <i class="fa-solid fa-mug-hot fa-2x mb-2 text-success d-block"></i>
                            <div class="text-success font-weight-bold mb-1">Đang trong thời gian ca trực của bạn: {{ $myShiftName }}</div>
                            <div class="small mt-1 text-dark">Hãy bấm Bắt đầu ca trực để hệ thống ghi nhận chấm công & tự động tính doanh số đơn hàng bạn xử lý.</div>
                        </div>

                        <!-- Checkin Form Button -->
                        <button type="button" class="btn btn-karate btn-block font-weight-bold py-2 shadow-sm" data-toggle="modal" data-target="#checkInModal">
                            <i class="fa-solid fa-fingerprint mr-1"></i> BẮT ĐẦU CA LÀM VIỆC (CHECK-IN)
                        </button>
                    @else
                        <div class="p-3 rounded border mb-3 text-left" style="background:#fffbeb; border-color:#fcd34d !important;">
                            <i class="fa-solid fa-lock fa-2x mb-2 text-warning d-block"></i>
                            <div class="text-dark font-weight-bold mb-1">CHẾ ĐỘ CHỈ XEM (NGOÀI CA TRỰC)</div>
                            <div class="small text-dark">
                                Hiện tại hệ thống đang trong <strong>{{ $curShiftName }}</strong>.<br>
                                Ca làm việc phân công của bạn là <strong>{{ $myShiftName }}</strong>.<br>
                                Bạn không thể chấm công vào thời điểm này.
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary btn-block font-weight-bold py-2 shadow-sm" disabled style="cursor: not-allowed;" title="Bạn chỉ có thể chấm công khi đúng ca trực phân công">
                            <i class="fa-solid fa-lock mr-1"></i> KHÔNG THỂ CHẤM CÔNG (NGOÀI CA PHÂN CÔNG)
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Today's Shift Performance Summary -->
    <div class="col-lg-7 col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa-solid fa-chart-column mr-1"></i> Hiệu suất xử lý đơn theo ca hôm nay ({{ now()->format('d/m/Y') }})</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    @forelse($shifts as $s)
                        @php
                            $stat = $todayShiftsStats->firstWhere('shift_name', $s->name);
                        @endphp
                        <div class="col-md-4 col-sm-12 mb-2">
                            <div class="card p-2 border text-center bg-light h-100 d-flex flex-column">
                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 13px;">{{ $s->name }}</h6>
                                <div class="small text-muted mb-2">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</div>
                                
                                @if($s->assignedStaff && $s->assignedStaff->count() > 0)
                                    <div class="mb-2 p-1 bg-white rounded border small text-left">
                                        <span class="text-muted d-block" style="font-size: 10px; font-weight: bold;">NHÂN VIÊN PHÂN CÔNG:</span>
                                        @foreach($s->assignedStaff as $st)
                                            <span class="badge badge-light border text-dark mr-1 mb-1" style="font-size: 11px;">
                                                <i class="fa-solid fa-user-check text-success mr-1"></i>{{ $st->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between px-2 pt-2 border-top small mt-auto">
                                    <span>Đơn xử lý:</span>
                                    <strong class="text-primary">{{ $stat->total_orders ?? 0 }} đơn</strong>
                                </div>
                                <div class="d-flex justify-content-between px-2 small">
                                    <span>Doanh số:</span>
                                    <strong class="text-danger">{{ number_format($stat->total_revenue ?? 0, 0, ',', '.') }} ₫</strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-3">Chưa có ca làm việc nào được cấu hình.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shift Change Requests -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-calendar-days text-danger mr-1"></i> Yêu cầu đổi ca trực</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @if(auth()->user() && auth()->user()->isStaff() && !auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.shifts.change-requests.store') }}" class="row align-items-end mb-4">
                @csrf
                <div class="col-md-3 form-group">
                    <label class="font-weight-bold">Ngày cần đổi ca:</label>
                    <input type="date" name="work_date" min="{{ now()->addDay()->format('Y-m-d') }}" value="{{ old('work_date') }}" class="form-control" required>
                </div>
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">Xin đổi sang:</label>
                    <select name="requested_shift_id" class="form-control" required>
                        <option value="">-- Chọn ca muốn đổi --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" @selected(old('requested_shift_id') == $shift->id)>{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 form-group">
                    <label class="font-weight-bold">Lý do bận:</label>
                    <div class="input-group">
                        <input type="text" name="reason" value="{{ old('reason') }}" class="form-control" maxlength="1000" placeholder="Ví dụ: Có việc gia đình..." required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-karate font-weight-bold"><i class="fa-solid fa-paper-plane mr-1"></i> Gửi admin</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        @if(auth()->user() && auth()->user()->isAdmin())<th>Nhân viên</th>@endif
                        <th>Ngày làm</th>
                        <th>Ca hiện tại</th>
                        <th>Xin đổi sang</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        @if(auth()->user() && auth()->user()->isAdmin())<th style="width: 230px;">Xử lý</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($changeRequests as $changeRequest)
                        <tr>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <td class="font-weight-bold">{{ $changeRequest->user->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $changeRequest->work_date->format('d/m/Y') }}</td>
                            <td>{{ $changeRequest->currentShift->name ?? 'Chưa phân ca' }}</td>
                            <td class="font-weight-bold text-primary">{{ $changeRequest->requestedShift->name ?? 'N/A' }}</td>
                            <td>{{ $changeRequest->reason }}</td>
                            <td>
                                @if($changeRequest->status === 'pending')
                                    <span class="badge badge-warning text-dark">Chờ duyệt</span>
                                @elseif($changeRequest->status === 'approved')
                                    <span class="badge badge-success">Đã duyệt</span>
                                @else
                                    <span class="badge badge-danger">Từ chối</span>
                                    @if($changeRequest->admin_note)<div class="small text-muted mt-1">{{ $changeRequest->admin_note }}</div>@endif
                                @endif
                            </td>
                            @if(auth()->user() && auth()->user()->isAdmin())
                                <td>
                                    @if($changeRequest->status === 'pending')
                                        <div class="d-flex">
                                            <form method="POST" action="{{ route('admin.shifts.change-requests.approve', $changeRequest) }}" class="mr-1">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-success" type="submit"><i class="fa-solid fa-check mr-1"></i>Duyệt</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.shifts.change-requests.reject', $changeRequest) }}" class="d-flex">
                                                @csrf @method('PATCH')
                                                <input type="text" name="admin_note" class="form-control form-control-sm mr-1" placeholder="Lý do từ chối">
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-xmark mr-1"></i>Từ chối</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="small text-muted">{{ $changeRequest->reviewer->name ?? 'Admin' }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ auth()->user() && auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center text-muted py-3">Chưa có yêu cầu đổi ca.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="x_panel">
    <div class="x_content">
        <form method="GET" action="{{ route('admin.shifts.index') }}" class="row align-items-end">
            <div class="col-md-4 col-sm-6 form-group">
                <label class="font-weight-500">Lọc theo nhân viên:</label>
                <select name="staff_id" class="form-control">
                    <option value="">-- Tất cả nhân viên --</option>
                    @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>
                            {{ $staff->name }} ({{ $staff->role->name ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Lọc theo ca:</label>
                <select name="shift_id" class="form-control">
                    <option value="">-- Tất cả các ca --</option>
                    @foreach($shifts as $sh)
                        <option value="{{ $sh->id }}" {{ request('shift_id') == $sh->id ? 'selected' : '' }}>
                            {{ $sh->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-sm-6 form-group">
                <label class="font-weight-500">Ngày làm việc:</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2 col-sm-6 form-group">
                <button type="submit" class="btn btn-secondary btn-block">
                    <i class="fa-solid fa-filter mr-1"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Work Logs Table -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa-solid fa-list-check mr-1"></i> Nhật ký ca làm việc & Chấm công ({{ $workLogs->total() }} lượt)</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>Nhân viên</th>
                    <th>Ca làm việc</th>
                    <th>Giờ Check-in</th>
                    <th>Giờ Check-out</th>
                    <th>Thời gian trực</th>
                    <th class="text-center">Đơn đã xử lý</th>
                    <th class="text-right">Doanh số ca</th>
                    <th class="text-right">Tiền mặt</th>
                    <th class="text-right">Tiền CK</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú / Bàn giao</th>
                </tr>
            </thead>
            <tbody>
                @forelse($workLogs as $log)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($log->user && $log->user->avatar)
                                <img src="{{ asset($log->user->avatar) }}" class="rounded-circle mr-2" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name ?? 'Staff') }}&background=2A3F54&color=fff&size=32" class="rounded-circle mr-2" style="width: 32px; height: 32px;">
                            @endif
                            <div>
                                <strong>{{ $log->user->name ?? 'Nhân viên' }}</strong>
                                <div class="small text-muted">{{ $log->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-light border font-weight-500">{{ $log->shift_name ?: 'Ca linh hoạt' }}</span>
                    </td>
                    <td>
                        <small class="text-muted"><i class="fa-regular fa-clock mr-1"></i> {{ $log->check_in_at ? $log->check_in_at->format('H:i - d/m/Y') : ($log->login_at ? $log->login_at->format('H:i - d/m/Y') : 'N/A') }}</small>
                    </td>
                    <td>
                        @if($log->check_out_at)
                            <small class="text-muted"><i class="fa-regular fa-clock mr-1"></i> {{ $log->check_out_at->format('H:i - d/m/Y') }}</small>
                        @else
                            <span class="badge badge-warning text-dark"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Đang trực</span>
                        @endif
                    </td>
                    <td class="font-weight-bold text-dark">
                        {{ $log->duration_formatted }}
                    </td>
                    <td class="text-center font-weight-bold text-primary">
                        {{ $log->orders_handled_count }} đơn
                    </td>
                    <td class="text-right font-weight-bold text-danger">
                        {{ number_format($log->total_revenue_handled, 0, ',', '.') }} ₫
                    </td>
                    @php
                        $logPayment = $paymentStats->get($log->id, ['cash' => 0, 'banking' => 0]);
                    @endphp
                    <td class="text-right font-weight-bold text-success">
                        {{ number_format($logPayment['cash'], 0, ',', '.') }} ₫
                    </td>
                    <td class="text-right font-weight-bold text-primary">
                        {{ number_format($logPayment['banking'], 0, ',', '.') }} ₫
                    </td>
                    <td>
                        @if($log->status === 'active')
                            <span class="badge badge-success px-2 py-1"><i class="fa-solid fa-circle-dot fa-beat mr-1"></i> Đang trực</span>
                        @else
                            <span class="badge badge-secondary px-2 py-1">Đã kết thúc</span>
                        @endif
                    </td>
                    <td class="small text-muted" style="max-width: 200px;">
                        {{ $log->note ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-clipboard-user fa-2x mb-2 d-block"></i>
                        Không có dữ liệu ca làm việc nào theo tiêu chí lọc.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Hiển thị {{ $workLogs->firstItem() ?? 0 }} đến {{ $workLogs->lastItem() ?? 0 }} trong tổng số {{ $workLogs->total() }} lượt ca
            </div>
            <div>
                {{ $workLogs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Check-In -->
<div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.shifts.checkin') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-fingerprint mr-1"></i> Bắt đầu ca làm việc (Check-in)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Ca làm việc của bạn:</label>
                        @if($todayAssignedShift)
                            <input type="hidden" name="shift_id" value="{{ $todayAssignedShift->id }}">
                            <div class="form-control bg-light text-primary font-weight-bold d-flex align-items-center">
                                <i class="fa-solid fa-user-clock text-danger mr-2"></i> {{ $todayAssignedShift->name }} (Được phân công hôm nay)
                            </div>
                        @else
                            <select name="shift_id" class="form-control font-weight-bold" required>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Ghi chú đầu ca:</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Nhận ca từ bạn Nam, kiểm tra tồn kho võ phục Kata size 4, kiểm tra 5 đơn chờ xác nhận..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-karate font-weight-bold">Xác nhận Bắt đầu ca</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Check-Out -->
<div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.shifts.checkout') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-right-from-bracket mr-1"></i> Kết thúc ca làm việc (Check-out)</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Hệ thống sẽ lưu trữ toàn bộ thời gian làm việc và thống kê số đơn hàng bạn đã xử lý trong ca này.</p>
                    <div class="form-group">
                        <label class="font-weight-bold">Nội dung bàn giao ca / Ghi chú kết ca:</label>
                        <textarea name="note" class="form-control" rows="4" placeholder="Ví dụ: Đã giao 8 đơn cho bưu cục Viettel Post, còn 2 đơn chờ khách đổi size võ phục bàn giao ca sau theo dõi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">Xác nhận Kết thúc ca</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateLiveClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.innerHTML = `<i class="fa-regular fa-clock mr-1"></i> ${hours}:${minutes}:${seconds}`;
        }
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();
</script>
@endpush
