@extends('layouts.khach_hang')

@section('title', 'Câu hỏi thường gặp')

@section('breadcrumb', 'Câu hỏi thường gặp')

@push('styles')
<style>
    .faq-banner-image {
        height: 490px;
        overflow: hidden;
    }

    .faq-banner-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

        <!-- FAQ AREA START (faq-2) (ID > accordion_2) -->
        <div class="ltn__faq-area mb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="ltn__faq-inner ltn__faq-inner-2">
                            <div id="accordion_2">
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-1" aria-expanded="false">
                                        Làm thế nào để mua sản phẩm?
                                    </h6>
                                    <div id="faq-item-2-1" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Bạn chọn sản phẩm tại trang cửa hàng, xem thông tin và thêm sản phẩm vào giỏ hàng.
                                                Sau đó điền thông tin nhận hàng, kiểm tra đơn và xác nhận đặt hàng.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="ltn__card-title" data-bs-toggle="collapse" data-bs-target="#faq-item-2-2"
                                        aria-expanded="true">
                                        Shop có hỗ trợ đổi trả không?
                                    </h6>
                                    <div id="faq-item-2-2" class="collapse show" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Shop hỗ trợ đổi sản phẩm khi sản phẩm bị lỗi hoặc không đúng thông tin đã xác nhận.
                                                Vui lòng liên hệ sớm sau khi nhận hàng để được hướng dẫn kiểm tra và xử lý.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-3" aria-expanded="false">
                                        Người mới tập Karate-Do nên chọn sản phẩm nào?
                                    </h6>
                                    <div id="faq-item-2-3" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Người mới nên bắt đầu với một bộ võ phục vừa vặn và các dụng cụ cơ bản theo yêu cầu
                                                của câu lạc bộ. Shop có thể tư vấn size, chất liệu và sản phẩm phù hợp với cấp độ tập luyện.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-4" aria-expanded="false">
                                        Thời gian giao hàng bao lâu?
                                    </h6>
                                    <div id="faq-item-2-4" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Shop giao hàng từ Ngũ Hành Sơn, Đà Nẵng đến toàn quốc. Thời gian nhận hàng phụ thuộc
                                                vào khu vực và đơn vị vận chuyển; shop sẽ gửi thông tin vận đơn sau khi đơn được xác nhận.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-5" aria-expanded="false">
                                        Thông tin đặt hàng của tôi có được bảo mật không?
                                    </h6>
                                    <div id="faq-item-2-5" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Shop chỉ sử dụng thông tin bạn cung cấp để tư vấn, xác nhận và giao đơn hàng.
                                                Thông tin cá nhân của khách hàng được bảo mật và không dùng cho mục đích khác.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-6" aria-expanded="false">
                                        Mã giảm giá không áp dụng được thì phải làm sao?
                                    </h6>
                                    <div id="faq-item-2-6" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Bạn hãy kiểm tra thời hạn, điều kiện áp dụng và trạng thái của mã giảm giá.
                                                Nếu mã vẫn không hoạt động, hãy gửi mã cho shop qua Zalo để được kiểm tra.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- card -->
                                <div class="card">
                                    <h6 class="collapsed ltn__card-title" data-bs-toggle="collapse"
                                        data-bs-target="#faq-item-2-7" aria-expanded="false">
                                        Shop hỗ trợ những hình thức thanh toán nào?
                                    </h6>
                                    <div id="faq-item-2-7" class="collapse" data-parent="#accordion_2">
                                        <div class="card-body">
                                            <p>Shop hỗ trợ thanh toán theo phương thức được hiển thị khi xác nhận đơn hàng.
                                                Bạn có thể liên hệ shop trước khi đặt để được hướng dẫn phương thức phù hợp.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="need-support text-center mt-100">
                                <h2>Cần được hỗ trợ thêm?</h2>
                                <div class="btn-wrapper mb-30">
                                    <a href="https://zalo.me/0967137200" target="_blank" rel="noopener noreferrer"
                                        class="theme-btn-1 btn">Nhắn Zalo cho shop</a>
                                </div>
                                <h3><a href="tel:+84967137200"><i class="fas fa-phone"></i> 0967.137.200</a></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <aside class="sidebar-area ltn__right-sidebar">
                            <!-- Newsletter Widget -->
                            <div class="widget ltn__search-widget ltn__newsletter-widget">
                                <h6 class="ltn__widget-sub-title">// tìm kiếm</h6>
                                <h4 class="ltn__widget-title">Tìm sản phẩm</h4>
                                <form action="#">
                                    <input type="text" name="search" placeholder="Nhập từ khóa">
                                    <button type="submit"><i class="fas fa-search"></i></button>
                                </form>
                                <div class="ltn__newsletter-bg-icon">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                            </div>
                            <!-- Banner Widget -->
                            <div class="widget ltn__banner-widget faq-banner-image">
                                <a href="{{ url('/products') }}"><img src="img/banner/4.png" alt="Sản phẩm Karate-Do"></a>
                            </div>

                        </aside>
                    </div>
                </div>
            </div>
        </div>
        <!-- FAQ AREA START -->

        <!-- COUNTER UP AREA START -->
        <div class="ltn__counterup-area bg-image bg-overlay-theme-black-80 pt-115 pb-70" data-bg="img/bg/5.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="img/icons/icon-img/3.jpg" alt="#"> </div>
                            <h1><span class="counter">100</span><span class="counterUp-icon">+</span> </h1>
                            <h6>Sản phẩm Karate-Do</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="img/icons/icon-img/5.jpg" alt="#"> </div>
                                <h1><span class="counter">63</span><span class="counterUp-icon">+</span> </h1>
                                <h6>Tỉnh thành giao hàng</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="img/icons/icon-img/2.jpg" alt="#"> </div>
                            <h1><span class="counter">100</span><span class="counterUp-icon">%</span> </h1>
                            <h6>Tư vấn tận tâm</h6>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 align-self-center">
                        <div class="ltn__counterup-item-3 text-color-white text-center">
                            <div class="counter-icon"> <img src="img/icons/icon-img/1.jpg" alt="#"> </div>
                            <h1><span class="counter">24</span><span class="counterUp-letter">/7</span> </h1>
                            <h6>Hỗ trợ trực tuyến</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- COUNTER UP AREA END -->

   
@endsection