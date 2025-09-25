<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Home - ' . config('app.name'))</title>

    <meta name="author" content="{{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="@yield('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/fonts/font-icons.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/ecomus/css/styles.css') }}"/>
    
    <!-- Custom Laravel Integration CSS -->
    <link rel="stylesheet" href="{{ asset('assets/ecomus/css/custom-laravel.css') }}">
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="apple-touch-icon-precomposed" href="{{ siteFavicon() }}">

    <!-- Additional CSS -->
    @stack('styles')

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="preload-wrapper popup-loader">
    <!-- RTL Toggle -->
    <a href="#" id="toggle-rtl" class="tf-btn animate-hover-btn btn-fill">RTL</a>
    <!-- /RTL Toggle -->

    <!-- Preload -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /Preload -->

    <div id="wrapper">
        @yield('content')
        
        @include('layouts.ecomus.footer')
    </div>

    <!-- Mobile Menu -->
    @include('layouts.ecomus.mobile-menu')

    <!-- Modal Search -->
    @include('layouts.ecomus.search-modal')

    <!-- Modal Login -->
    @include('layouts.ecomus.login-modal')

    <!-- Modal Shopping Cart -->
    @include('layouts.ecomus.cart-modal')

    <!-- JavaScript -->
    <script src="{{ asset('assets/ecomus/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/lazysize.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/carousel.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/count-down.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/multiple-modal.js') }}"></script>
    <script src="{{ asset('assets/ecomus/js/main.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @stack('scripts')

    <!-- Show toastr messages -->
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    @if(session('info'))
        <script>
            toastr.info("{{ session('info') }}");
        </script>
    @endif

    @if(session('warning'))
        <script>
            toastr.warning("{{ session('warning') }}");
        </script>
    @endif
</body>
</html>