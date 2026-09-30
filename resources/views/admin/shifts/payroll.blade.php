@extends('layouts.quan_tri')
@section('title', 'Ngày công & Lương tháng')
@section('content')
<div class="page-title">
    <h3>Ngày công & Lương tháng</h3>
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-default">Về trang chấm công</a>
</div>
<div class="clearfix"></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())
    <div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
@endif
<div class="x_panel">
    <form method="GET" action="{{ route('admin.shifts.payroll') }}" class="row align-items-end">
        <div class="col-md-3 form-group"><label for="month">Tháng làm việc</label><input id="month" type="month" name="month" value="{{ $month }}" required class="form-control"></div>
        <div class="col-md-5 form-group"><label for="staff_id">Nhân viên</label><select id="staff_id" name="staff_id" class="form-control">
            <option value="">Tất cả nhân viên</option>
            @foreach($staffMembers as $staff)<option value="{{ $staff->id }}" @selected(request('staff_id') == $staff->id)>{{ $staff->name }} — {{ $staff->email }}</option>@endforeach
        </select></div>
        <div class="col-md-3 form-group"><button class="btn btn-primary" type="submit">Xem thống kê</button></div>
    </form>
    <p>Lương ca = đơn giá theo giờ × số phút làm trong ca / 60. Mặc định 20.000đ/giờ, có thể chỉnh riêng từng ca của nhân viên. Để trống và lưu để trở về mức mặc định. Đi muộn hoặc về sớm tính theo thời gian thực tế. Các khoảng chấm công trùng được gộp.</p>
    <p>Một ngày làm nhiều ca vẫn tính 1 ngày làm việc. Chỉ tính bản ghi đã check-out; không tính ngoài giờ ca. Ca qua đêm thuộc ngày bắt đầu ca. Đơn giá được lưu riêng theo nhân viên và tháng, không tự áp dụng sang tháng khác.</p>
    <div class="alert alert-info">Tổng lương tạm tính tháng {{ \Carbon\Carbon::parse($month.'-01')->format('m/Y') }}: <strong>{{ number_format($rows->sum(fn($row) => $row['report']['salary']), 0, ',', '.') }} đ</strong>. Chưa bao gồm thưởng, khấu trừ hoặc các ca chưa đủ dữ liệu.</div>
</div>
@forelse($rows as $row)
    @php($report = $row['report'])
    <div class="x_panel">
        <div class="x_title"><h2>{{ $row['staff']->name }} <small>{{ $row['staff']->email }}</small></h2><div class="clearfix"></div></div>
        <p><strong>{{ $report['days'] }} ngày làm việc</strong> · {{ $report['pending'] }} bản ghi thiếu check-out · {{ $report['invalid'] }} bản ghi không hợp lệ/ngoài ca</p>
        @if($report['missing_rates'])<div class="alert alert-warning">Có {{ $report['missing_rates'] }} ca đã làm chưa nhập đơn giá. Tổng lương chưa đầy đủ.</div>@endif
        <form method="POST" action="{{ route('admin.shifts.payroll.rates') }}">
            @csrf @method('PUT')
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="staff_id" value="{{ $row['staff']->id }}">
            <div class="table-responsive"><table class="table table-bordered">
                <thead><tr><th>Ca làm việc</th><th>Ngày có làm</th><th>Giờ thực tính</th><th>Công ca quy đổi</th><th>Đơn giá / giờ (đ)</th><th>Lương ca (đ)</th></tr></thead>
                <tbody>
                @foreach($shifts as $shift)
                    @php($item = $report['shifts'][$shift->id])
                    <tr>
                        <td>{{ $shift->name }}</td><td>{{ $item['days'] }}</td>
                        <td>{{ number_format($item['minutes'] / 60, 2, ',', '.') }}</td><td>{{ number_format($item['units'], 3, ',', '.') }}</td>
                        <td><input aria-label="Đơn giá mỗi giờ {{ $shift->name }} cho {{ $row['staff']->name }}" type="number" class="form-control" name="rates[{{ $shift->id }}]" min="0" max="100000000" step="1" placeholder="20.000đ/giờ" value="{{ old('staff_id') == $row['staff']->id ? old('rates.'.$shift->id, round($item['rate'])) : round($item['rate']) }}"></td>
                        <td>{{ $item['rate'] === null ? 'Chưa có đơn giá' : number_format($item['salary'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot><tr><th colspan="5">Tổng lương tạm tính (làm tròn đến đồng theo từng ca/ngày)</th><th>{{ number_format($report['salary'], 0, ',', '.') }} đ</th></tr></tfoot>
            </table></div>
            <button type="submit" class="btn btn-success">Lưu đơn giá tháng {{ $month }}</button>
        </form>
        <details class="mt-3"><summary>Chi tiết ngày công</summary>
            <div class="table-responsive"><table class="table table-striped">
                <thead><tr><th>Ngày</th><th>Ca</th><th>Phút thực tính</th><th>Công ca</th><th>Tiền lương (đ)</th></tr></thead>
                <tbody>@forelse($report['details'] as $detail)
                    <tr><td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td><td>{{ $shifts->firstWhere('id', $detail['shift_id'])->name }}</td><td>{{ $detail['minutes'] }}</td><td>{{ number_format($detail['units'], 3, ',', '.') }}</td><td>{{ $detail['rate'] === null ? 'Chưa có đơn giá' : number_format($detail['salary'], 0, ',', '.') }}</td></tr>
                @empty<tr><td colspan="5">Chưa có ngày công đủ điều kiện tính lương.</td></tr>@endforelse</tbody>
            </table></div>
        </details>
    </div>
@empty
    <div class="alert alert-info">Không có nhân viên phù hợp.</div>
@endforelse
@endsection
