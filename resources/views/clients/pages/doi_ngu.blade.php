@extends('layouts.khach_hang')

@section('title', 'Đội ngũ')
    
@section('breadcrumb', 'Đội ngũ karate_do.shop')

@push('styles')
<style>
    .team-feature-image img {
        width: 70%;
        margin-left: 35%;
        margin-right: auto;
    }
</style>
@endpush

@section('content')
<!-- TEAM AREA START (Team - 3) -->
        <div class="ltn__team-area pt-110--- pb-90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ltn__team-item">
                            <div class="team-img">
                                <img src="img/team/9.jpg" alt="Đội ngũ karate_do.shop">
                            </div>
                            <div class="team-info">
                                <h6 class="ltn__secondary-color">Người sáng lập</h6>
                                <h4><a href="{{ url('/about') }}">Đậu Cao Thanh</a></h4>
                                <div class="ltn__social-media">
                                    <ul>
                                                
                                        <li>
                                            <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook"><i
                                                                    class="fab fa-facebook-f"></i></a></li>                                                                                             

                                            <li><a href="https://www.instagram.com/vay_16th2?fbclid=IwY2xjawTyRS9wZG9mBWV4dG4DYWVtAjEwAGJyaWQRMWlNZmJRdDV3a2p4OEVJaTZzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEewvmFk6hnmPuZrmVMnXeYN0r83S9MERxRNxmXKeVePEqt8TtQRMqUrjpUBfM_aem_XXH-wMnLoICo4U-6qxRRmQ" title="Instagram"><i
                                                                    class="fab fa-instagram"></i></a></li>                                             
                                            <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i
                                                                    class="fas fa-comment-dots"></i></a></li>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ltn__team-item">
                            <div class="team-img">
                                <img src="img/team/2.jpg" alt="Tư vấn võ phục Karate-Do">
                            </div>
                            <div class="team-info">
                                <h6 class="ltn__secondary-color">Tư vấn võ phục</h6>
                                <h4><a href="{{ url('/products') }}">Đậu Cao Thanh</a></h4>
                                <div class="ltn__social-media">
                                    <ul>
                                                
                                                <li>
                                                    <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook"><i
                                                                class="fab fa-facebook-f"></i></a></li>                                                                                             

                                                    <li><a href="https://www.instagram.com/vay_16th2?fbclid=IwY2xjawTyRS9wZG9mBWV4dG4DYWVtAjEwAGJyaWQRMWlNZmJRdDV3a2p4OEVJaTZzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEewvmFk6hnmPuZrmVMnXeYN0r83S9MERxRNxmXKeVePEqt8TtQRMqUrjpUBfM_aem_XXH-wMnLoICo4U-6qxRRmQ" title="Instagram"><i
                                                                class="fab fa-instagram"></i></a></li>                                               
                                                    <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i
                                                                class="fas fa-comment-dots"></i></a></li>
                                                </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ltn__team-item">
                            <div class="team-img">
                                <img src="img/team/9.jpg" alt="Chuyên viên dụng cụ Karate-Do">
                            </div>
                            <div class="team-info">
                                <h6 class="ltn__secondary-color">Chuyên viên dụng cụ</h6>
                                <h4><a href="{{ url('/products') }}">Đậu Cao Thanh</a></h4>
                                <div class="ltn__social-media">
                                    <ul>
                                                
                                                <li>
                                                    <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook"><i
                                                                class="fab fa-facebook-f"></i></a></li>                                                                                             

                                                    <li><a href="https://www.instagram.com/vay_16th2?fbclid=IwY2xjawTyRS9wZG9mBWV4dG4DYWVtAjEwAGJyaWQRMWlNZmJRdDV3a2p4OEVJaTZzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEewvmFk6hnmPuZrmVMnXeYN0r83S9MERxRNxmXKeVePEqt8TtQRMqUrjpUBfM_aem_XXH-wMnLoICo4U-6qxRRmQ" title="Instagram"><i
                                                                class="fab fa-instagram"></i></a></li>                                               
                                                    <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i
                                                                class="fas fa-comment-dots"></i></a></li>
                                                </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ltn__team-item">
                            <div class="team-img">
                                <img src="img/team/2.jpg" alt="Chuyên viên may đo võ phục">
                            </div>
                            <div class="team-info">
                                <h6 class="ltn__secondary-color">May đo theo yêu cầu</h6>
                                <h4><a href="{{ url('/service') }}">Đậu Cao Thanh</a></h4>
                                <div class="ltn__social-media">
                                    <ul>
                                                
                                                <li>
                                                    <li><a href="https://www.facebook.com/Thanh686868686868" title="Facebook"><i
                                                                class="fab fa-facebook-f"></i></a></li>                                                                                             

                                                    <li><a href="https://www.instagram.com/vay_16th2?fbclid=IwY2xjawTyRS9wZG9mBWV4dG4DYWVtAjEwAGJyaWQRMWlNZmJRdDV3a2p4OEVJaTZzcnRjBmFwcF9pZBAyMjIwMzkxNzg4MjAwODkyAAEewvmFk6hnmPuZrmVMnXeYN0r83S9MERxRNxmXKeVePEqt8TtQRMqUrjpUBfM_aem_XXH-wMnLoICo4U-6qxRRmQ" title="Instagram"><i
                                                                class="fab fa-instagram"></i></a></li>                                               
                                                    <li><a href="https://zalo.me/0967137200" title="Zalo" target="_blank" rel="noopener noreferrer"><i
                                                                class="fas fa-comment-dots"></i></a></li>
                                                </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
        <!-- TEAM AREA END -->
         <!-- PROGRESS BAR AREA START -->
        <div class="ltn__progress-bar-area pt-115 pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="ltn__progress-bar-wrap">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">// năng lực phục vụ</h6>
                                <h1 class="section-title">Đồng hành cùng võ sinh
                                    tận tâm<span>.</span></h1>
                                <p>Đội ngũ karate_do.shop luôn sẵn sàng tư vấn đúng nhu cầu, đúng cấp độ
                                    và giúp bạn chọn trang bị phù hợp cho mỗi buổi tập.</p>
                            </div>
                            <div class="ltn__progress-bar-inner">
                                <div class="ltn__progress-bar-item">
                                    <p>Tư vấn võ phục đúng size</p>
                                    <div class="progress">
                                        <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                            data-wow-delay=".5s" role="progressbar" style="width: 96%">
                                            <span>96%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ltn__progress-bar-item">
                                    <p>Kiến thức dụng cụ Karate-Do</p>
                                    <div class="progress">
                                        <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                            data-wow-delay=".5s" role="progressbar" style="width: 92%">
                                            <span>92%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ltn__progress-bar-item">
                                    <p>Hỗ trợ đơn hàng toàn quốc</p>
                                    <div class="progress">
                                        <div class="progress-bar wow fadeInLeft" data-wow-duration="0.5s"
                                            data-wow-delay=".5s" role="progressbar" style="width: 94%">
                                            <span>94%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-img-right team-feature-image">
                            <img src="img/team/9.jpg" alt="Karate-do shop hỗ trợ võ sinh">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PROGRESS BAR AREA END -->
   
@endsection