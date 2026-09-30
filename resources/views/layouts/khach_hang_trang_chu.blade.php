<!doctype html>
<html class="no-js" lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{ asset('assets/clients/img/logo.png') }}" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/font-icons.css') }}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/plugins.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/style.css') }}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{ asset('assets/clients/css/responsive.css') }}">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Toastr notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('styles')
    <style>
        .site-logo-small {
            min-width: 190px;
        }

        .site-logo-small img {
            width: 60px;
            height: auto;
        }

        .site-logo-small a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 21px;
            font-weight: 700;
            white-space: nowrap;
        }

        .slide-sub-title-icon {
            width: 80px;
            height: 80px;
            object-fit: contain;
            vertical-align: middle;
            margin-right: 2px;
        }

        .banner-square {
            aspect-ratio: 1 / 1;
            background-color: #fff;
        }

        .banner-square a,
        .banner-square img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .banner-square img {
            object-fit: contain;
        }

        .ltn__header-area a:hover,
        .ltn__header-area a:hover i,
        .ltn__header-area a:hover span {
            color: #e53935;
        }

        .ltn__header-area .special-link a:hover {
            color: #fff;
            background-color: #e53935;
        }

        /* Badge status chữ đỏ theo yêu cầu */
        .badge-order-status-red {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #d32f2f !important;
            background-color: #fef2f2 !important;
            border: 1.5px solid #fca5a5 !important;
            border-radius: 20px;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(211, 47, 47, 0.08);
        }
        .badge-order-status-red i {
            color: #d32f2f !important;
        }

        /* Fallback Badge CSS cho Bootstrap 5 để tránh lỗi chữ trắng */
        .badge.badge-primary, .badge-primary { color: #fff !important; background-color: #0d6efd !important; }
        .badge.badge-success, .badge-success { color: #fff !important; background-color: #16a34a !important; }
        .badge.badge-danger, .badge-danger { color: #fff !important; background-color: #dc2626 !important; }
        .badge.badge-warning, .badge-warning { color: #1f2937 !important; background-color: #f59e0b !important; }
        .badge.badge-info, .badge-info { color: #fff !important; background-color: #0284c7 !important; }
        .badge.badge-secondary, .badge-secondary { color: #fff !important; background-color: #64748b !important; }
        .badge.badge-dark, .badge-dark { color: #fff !important; background-color: #1e293b !important; }
        .badge.badge-light, .badge-light { color: #1e293b !important; background-color: #f1f5f9 !important; border: 1px solid #cbd5e1; }
    </style>
</head>

<body>
   
    <div class="body-wrapper">
        @include('clients.partials.dau_trang_trang_chu')
        <main>
            @yield('content')
        </main>
        @include('clients.partials.chan_trang_trang_chu')
        @include('clients.partials.chat_bot_ai')
    </div>
    
    <!-- preloader area start -->
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <script>
        document.querySelectorAll('[src^="img/"], [data-bg^="img/"]').forEach((element) => {
            const attribute = element.hasAttribute('src') ? 'src' : 'data-bg';
            element.setAttribute(attribute, '{{ asset('assets/clients/img') }}/' + element.getAttribute(attribute).substring(4));
        });
    </script>

    <!--Jquery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- All JS Plugins -->
    <script src="{{ asset('assets/clients/js/plugins.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/clients/js/main.js') }}"></script>
    <!-- Toastr notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="{{ asset('assets/clients/js/custom.js') }}?v={{ filemtime(public_path('assets/clients/js/custom.js')) }}"></script>
    @stack('scripts')
</body>

</html>