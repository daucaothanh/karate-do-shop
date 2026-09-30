@extends('layouts.khach_hang')

@section('title', 'Dịch vụ')
    
@section('breadcrumb', 'Dịch vụ')

@section('content')
   <!-- ABOUT US AREA START -->
        <div class="ltn__about-us-area pb-115">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 align-self-center">
                        <div class="about-us-img-wrap ltn__img-shape-left  about-img-left">
                            <img src="img/service/11.jpg" alt="Dịch vụ của karate_do.shop">
                        </div>
                    </div>
                    <div class="col-lg-7 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">// DỊCH VỤ CỦA KARATE_DO.SHOP //</h6>
                                <h1 class="section-title">Đồng hành cùng<br>
                                    người tập Karate-Do<span>.</span></h1>
                                <p>Chúng tôi cung cấp sản phẩm và dịch vụ phù hợp cho người mới tập,
                                    võ sinh và các câu lạc bộ Karate-Do.</p>
                            </div>
                            <div class="about-us-info-wrap-inner about-us-info-devide">
                                <p>Từ Ngũ Hành Sơn, Đà Nẵng, karate_do.shop hỗ trợ khách hàng trên toàn quốc
                                    với sản phẩm chính hãng, giá hợp lý, tư vấn tận tâm và giao hàng nhanh.</p>
                                <div class="list-item-with-icon">
                                    <ul>
                                        <li><a href="{{ url('/contact') }}">Tư vấn và giao hàng toàn quốc</a></li>
                                        <li><a href="{{ url('/about') }}">Tư vấn chọn size tận tâm</a></li>
                                        <li><a href="{{ url('/products') }}">Võ phục và dụng cụ chính hãng</a></li>
                                        <li><a href="{{ url('/products') }}">May võ phục theo yêu cầu</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOUT US AREA END -->

        <!-- SERVICE AREA START (Service 1) -->
        <div class="ltn__service-area section-bg-1 pt-115 pb-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area ltn__section-title-2 text-center">
                            <h1 class="section-title white-color---">Dịch vụ của karate_do.shop</h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/1.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/products') }}">Võ phục Karate-Do</a></h3>
                                <p>Võ phục bền đẹp, phù hợp cho luyện tập và thi đấu.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/2.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/products') }}">May theo yêu cầu</a></h3>
                                <p>Hỗ trợ tư vấn và may võ phục theo nhu cầu của khách hàng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/3.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/products') }}">Dụng cụ tập luyện</a></h3>
                                <p>Cung cấp phụ kiện hỗ trợ tập luyện Karate-Do an toàn và hiệu quả.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/4.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/products') }}">Tư vấn chọn sản phẩm</a></h3>
                                <p>Giúp bạn chọn đúng sản phẩm theo độ tuổi, cấp độ và mục đích sử dụng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/6.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/products') }}">Đơn hàng câu lạc bộ</a></h3>
                                <p>Hỗ trợ đơn hàng số lượng lớn cho câu lạc bộ và lớp học Karate-Do.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="ltn__service-item-1">
                            <div class="service-item-img">
                                <a href="service-details.html"><img src="img/service/5.jpg" alt="#"></a>
                            </div>
                            <div class="service-item-brief">
                                <h3><a href="{{ url('/contact') }}">Giao hàng toàn quốc</a></h3>
                                <p>Đóng gói cẩn thận và giao nhanh từ Ngũ Hành Sơn, Đà Nẵng.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SERVICE AREA END -->

        <!-- OUR JOURNEY AREA START -->
        <div class="ltn__our-journey-area bg-image bg-overlay-theme-90 pt-280 pb-350 mb-35 plr--9"
            data-bg="img/bg/8.jpg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__our-journey-wrap ">
                            <ul>
                                <li><span class="ltn__journey-icon">01</span>
                                    <ul>
                                        <li>
                                            <div class="ltn__journey-history-item-info clearfix">
                                                <div class="ltn__journey-history-img">
                                                    <img src="img/service/history-1.jpg" alt="#">
                                                </div>
                                                <div class="ltn__journey-history-info">
                                                    <h3>Tiếp nhận nhu cầu</h3>
                                                    <p>Lắng nghe nhu cầu luyện tập, thi đấu hoặc đặt may của khách hàng.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active"><span class="ltn__journey-icon">02</span>
                                    <ul>
                                        <li>
                                            <div class="ltn__journey-history-item-info clearfix">
                                                <div class="ltn__journey-history-img">
                                                    <img src="img/service/history-1.jpg" alt="#">
                                                </div>
                                                <div class="ltn__journey-history-info">
                                                    <h3>Tư vấn sản phẩm</h3>
                                                    <p>Tư vấn size, chất liệu và sản phẩm phù hợp với từng khách hàng.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li><span class="ltn__journey-icon">03</span>
                                    <ul>
                                        <li>
                                            <div class="ltn__journey-history-item-info clearfix">
                                                <div class="ltn__journey-history-img">
                                                    <img src="img/service/history-1.jpg" alt="#">
                                                </div>
                                                <div class="ltn__journey-history-info">
                                                    <h3>Chuẩn bị đơn hàng</h3>
                                                    <p>Kiểm tra sản phẩm, đóng gói cẩn thận trước khi bàn giao.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li><span class="ltn__journey-icon">04</span>
                                    <ul>
                                        <li>
                                            <div class="ltn__journey-history-item-info clearfix">
                                                <div class="ltn__journey-history-img">
                                                    <img src="img/service/history-1.jpg" alt="#">
                                                </div>
                                                <div class="ltn__journey-history-info">
                                                    <h3>Giao hàng nhanh</h3>
                                                    <p>Vận chuyển đơn hàng từ Ngũ Hành Sơn, Đà Nẵng đến toàn quốc.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li><span class="ltn__journey-icon">05</span>
                                    <ul>
                                        <li>
                                            <div class="ltn__journey-history-item-info clearfix">
                                                <div class="ltn__journey-history-img">
                                                    <img src="img/service/history-1.jpg" alt="#">
                                                </div>
                                                <div class="ltn__journey-history-info">
                                                    <h3>Hỗ trợ sau mua</h3>
                                                    <p>Sẵn sàng hỗ trợ khách hàng trong quá trình sử dụng sản phẩm.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- OUR JOURNEY AREA END -->
   
@endsection