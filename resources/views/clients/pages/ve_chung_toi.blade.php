@extends('layouts.khach_hang')
@section('title', 'Về chúng tôi')  
@section('breadcrumb', 'Về chúng tôi')
@push('styles')
<style>
    .about-team .col-xl-3:not(:first-child) {
        display: none;
    }

    .about-features .ltn__feature-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
    }

    .about-features .ltn__feature-icon img {
        width: 38px;
        height: 38px;
        object-fit: contain;
    }

    .about-us-info-wrap .author-sign {
        margin-left: 40px;
    }

    .about-hero-slider {
        position: relative;
        width: 100%;
        min-height: 420px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.12);
        background: linear-gradient(135deg, #cfe3ff 0%, #f2f8ff 100%);
    }

    .about-slide {
        display: none;
        width: 100%;
        height: 100%;
        min-height: 420px;
    }

    .about-slide.active {
        display: block;
    }

    .about-slide img {
        display: block;
        width: 100%;
        height: 420px;
        object-fit: cover;
    }

    .about-slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.78);
        color: #111827;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .about-slider-btn:hover {
        background: #fff;
    }

    .about-slider-btn.prev {
        left: 16px;
    }

    .about-slider-btn.next {
        right: 16px;
    }

    @media (max-width: 767px) {
        .about-hero-slider,
        .about-slide,
        .about-slide img {
            min-height: 260px;
            height: 260px;
        }
    }
</style>
@endpush
@section('content')
   <!-- ABOUT US AREA START -->
        <div class="ltn__about-us-area pt-120--- pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="about-hero-slider" aria-label="Slider ảnh giới thiệu Karate-Do">
                            <button type="button" class="about-slider-btn prev" aria-label="Ảnh trước">&#8249;</button>
                            <div class="about-slide active" data-about-slide>
                                <img src="{{ asset('assets/clients/img/team/10.jpg') }}" alt="Đội ngũ Karate-Do của karate_do.shop">
                            </div>
                            <div class="about-slide" data-about-slide>
                                <img src="{{ asset('assets/clients/img/team/9.jpg') }}" alt="Lớp luyện tập Karate-Do">
                            </div>
                            <div class="about-slide" data-about-slide>
                                <img src="{{ asset('assets/clients/img/karate/vo-phuc-rikaido.png') }}" alt="Võ phục Karate-Do chính hãng">
                            </div>
                            <button type="button" class="about-slider-btn next" aria-label="Ảnh sau">&#8250;</button>
                        </div>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <div class="about-us-info-wrap">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">VỀ KARATE_DO.SHOP</h6>
                                <h1 class="section-title">Đồng hành cùng<br class="d-none d-md-block"> người tập Karate-Do</h1>
                                <p>karate_do.shop cung cấp võ phục và dụng cụ Karate-Do chính hãng,
                                    phù hợp cho luyện tập, thi đấu và sử dụng lâu dài.</p>
                            </div>
                            <p>Shop hoạt động tại Ngũ Hành Sơn, Đà Nẵng và giao hàng toàn quốc.
                                Chúng tôi hỗ trợ tư vấn size, may theo yêu cầu, giá hợp lý và giao hàng nhanh.</p>
                            <div class="about-author-info d-flex">
                                <div class="author-name-designation  align-self-center">
                                    <h4 class="mb-0">Đậu Cao Thanh</h4>
                                    <small>/ Người đại diện karate_do.shop</small>
                                </div>
                                <div class="author-sign">
                                    <img src="{{ asset('assets/clients/img/icons/icon-img/thanh.png') }}" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOUT US AREA END -->

        <!-- FEATURE AREA START ( Feature - 6) -->
        <div class="ltn__feature-area section-bg-1 pt-115 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area ltn__section-title-2 text-center">
                                <h6 class="section-subtitle ltn__secondary-color">// VÌ SAO CHỌN CHÚNG TÔI //</h6>
                            <h1 class="section-title">Lý do chọn karate_do.shop<span>.</span></h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center about-features">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/1.png') }}" alt="#"></span>
                                </div>
                                <h3><a href="{{ url('/products') }}">Võ phục chính hãng</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Sản phẩm phù hợp cho tập luyện và thi đấu Karate-Do.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/11.png') }}" alt="#"></span>
                                </div>
                                <h3><a href="{{ url('/products') }}">May theo yêu cầu</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Tư vấn size và hỗ trợ may theo nhu cầu của khách hàng.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="ltn__feature-item ltn__feature-item-7">
                            <div class="ltn__feature-icon-title">
                                <div class="ltn__feature-icon">
                                    <span><img src="{{ asset('assets/clients/img/icons/icon-img/5.jpg') }}" alt="#"></span>
                                </div>
                                <h3><a href="{{ url('/products') }}">Giao hàng toàn quốc</a></h3>
                            </div>
                            <div class="ltn__feature-info">
                                <p>Đóng gói cẩn thận, giao nhanh từ Ngũ Hành Sơn, Đà Nẵng.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FEATURE AREA END -->

        <!-- TEAM AREA START (Team - 3) -->
        <div class="ltn__team-area about-team pt-115 pb-90">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area ltn__section-title-2 text-center">
                            <h1 class="section-title white-color---">Người đại diện</h1>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ltn__team-item">
                            <div class="team-img">
                                <img src="{{ asset('assets/clients/img/team/9.jpg') }}" alt="Đậu Cao Thanh">
                            </div>
                            <div class="team-info">
                                <h6 class="ltn__secondary-color">// ĐẠI DIỆN //</h6>
                                <h4><a href="#">Đậu Cao Thanh</a></h4>
                                <div class="ltn__social-media">
                                    <ul>
                                        <li>
                                            <a href="https://www.facebook.com/Thanh686868686868" title="Facebook" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com/vay_16th2" title="Instagram" target="_blank" rel="noopener noreferrer">
                                                <i class="fab fa-instagram"></i>
                                            </a>
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
   
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = Array.from(document.querySelectorAll('[data-about-slide]'));
        const prevButton = document.querySelector('.about-slider-btn.prev');
        const nextButton = document.querySelector('.about-slider-btn.next');

        if (!slides.length) return;

        let currentIndex = 0;
        let autoSlideTimer = null;

        function showSlide(index) {
            currentIndex = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('active', slideIndex === currentIndex);
            });
        }

        function startAutoSlide() {
            clearInterval(autoSlideTimer);
            autoSlideTimer = setInterval(() => {
                showSlide(currentIndex + 1);
            }, 3000);
        }

        prevButton?.addEventListener('click', function () {
            showSlide(currentIndex - 1);
            startAutoSlide();
        });

        nextButton?.addEventListener('click', function () {
            showSlide(currentIndex + 1);
            startAutoSlide();
        });

        startAutoSlide();
    });
</script>
@endpush

@endsection