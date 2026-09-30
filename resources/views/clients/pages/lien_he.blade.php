@extends('layouts.khach_hang')

@section('title', 'Liên hệ')

@section('breadcrumb', 'Liên hệ')

@section('content')
	<div class="container py-5"><div class="row justify-content-center"><div class="col-lg-8"><h2>Liên hệ với chúng tôi</h2><p class="mb-4">Hãy gửi câu hỏi, shop sẽ phản hồi sớm nhất.</p><form method="POST" action="{{ route('contact.send') }}">@csrf<input class="form-control mb-3" name="full_name" placeholder="Họ và tên" required><input class="form-control mb-3" name="email" type="email" placeholder="Email"><input class="form-control mb-3" name="phone_number" placeholder="Số điện thoại"><textarea class="form-control mb-3" name="message" rows="6" placeholder="Nội dung cần hỗ trợ" required></textarea><button class="btn btn-danger">Gửi liên hệ</button></form></div></div></div>
@endsection