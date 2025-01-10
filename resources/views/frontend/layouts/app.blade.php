<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset($site_settings['favicon']) }}" />

    <meta name="site_url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    <link href="{{ASSETS}}css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ASSETS}}css/navbar-1.css" rel="stylesheet">
    <link href="{{ASSETS}}fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/brands.min.css" />
    <link href="{{ASSETS}}css/owl.carousel.min.css" rel="stylesheet">
    <link href="{{ASSETS}}css/owl.theme.default.css" rel="stylesheet">
    <link href="{{CSS}}app.css" rel="stylesheet">
    <link href="{{CSS}}common.css" rel="stylesheet">
    <link href="{{ASSETS}}css/style.css" rel="stylesheet">
    <link href="{{ASSETS}}css/responsive.css" rel="stylesheet">
    <link href="{{ASSETS}}css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ASSETS}}css/slick.min.css"/>
    <link rel="stylesheet" href="{{ASSETS}}css/slick-theme.min.css"/>
    
    
    @yield('header_scripts')
</head>

<body>

    <div class="nav-position sticky-top">
        @include('frontend.includes.header')
    </div>
    @yield('css')
    <div class="content-margin">
        @yield('content')
    </div>

    @include('frontend.includes.footer')

    <!-- Scripts -->
    <script src="{{ASSETS}}js/jquery-3.6.4.min.js"></script>
    <script src="{{ASSETS}}js/bootstrap.bundle.min.js"></script>
    <script src="{{ASSETS}}js/popper.min.js"></script>
    <script src="{{ASSETS}}js/toastr.min.js"></script>
    <script src="{{ASSETS}}js/sweetalert2.min.js"></script>
    <script src="{{JS}}cart.js"></script>
    <script src="{{JS}}custom.js"></script>
    

    @yield('header-js')
    @yield('js')
    @include('partial.toastr')

</body>

</html>