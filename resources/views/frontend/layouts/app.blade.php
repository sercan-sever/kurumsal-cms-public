<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $activeLanguage?->code ?? '') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="@yield('meta_descriptions', $setting?->general?->content?->meta_descriptions)">
    <meta name="keywords" content="@yield('meta_keywords', $setting?->general?->content?->meta_keywords)">
    <meta name="author" content="Localkod">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="@yield('title', $setting?->general?->content?->title)">
    <meta property="og:description" content="@yield('meta_descriptions', $setting?->general?->content?->meta_descriptions)">
    <meta property="og:image" content="@yield('meta_image', $setting?->logo?->getBackendHeaderDark())">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $setting?->general?->content?->title)">
    <meta name="twitter:description" content="@yield('meta_descriptions', $setting?->general?->content?->meta_descriptions)">
    <meta name="twitter:image" content="@yield('meta_image', $setting?->logo?->getBackendHeaderDark())">

    <link rel="canonical" href="{{ request()->url() }}">


    <title>@yield('title', $setting?->general?->content?->title)</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $setting?->logo?->getBackendFavicon() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('frontend/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/mousecursor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/sweetalert.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/toastify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}" media="screen">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @yield('css')
</head>

<body>

    <div class="preloader">
        <div class="loading-container">
            <div class="loading"></div>
            <div id="loading-icon">
                <img src="{{ $setting?->logo?->getBackendHeaderDark() }}" alt="preloader icon">
            </div>
        </div>
    </div>

    @include('frontend.includes.header')


    @yield('content')

    <x-wait.wait-modal />

    <script src="{{ asset('frontend/js/axios.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/validator.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('frontend/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/isotope.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/js/SmoothScroll.js') }}"></script>
    <script src="{{ asset('frontend/js/parallaxie.js') }}"></script>
    <script src="{{ asset('frontend/js/gsap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/magiccursor.js') }}"></script>
    <script src="{{ asset('frontend/js/SplitText.js') }}"></script>
    <script src="{{ asset('frontend/js/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.mb.YTPlayer.min.js') }}"></script>
    <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/js/function.js') }}"></script>
    <script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('frontend/js/toastify.min.js') }}"></script>
    <script src="{{ asset('frontend/js/admin.js') }}"></script>
    <script src="{{ asset('frontend/js/form.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap-maxlength.min.js') }}"></script>

    @yield('js')
</body>

</html>
