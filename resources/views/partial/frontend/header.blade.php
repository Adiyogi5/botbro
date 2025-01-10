<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>{{ $site_settings['application_name'] }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/' . $site_settings['favicon']) }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $site_settings['favicon']) }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/' . $site_settings['favicon']) }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/' . $site_settings['favicon']) }}" />

    <!-- ===============================================-->
     <!-- Google Web Fonts -->
     <!-- ===============================================-->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Ubuntu:wght@500;700&display=swap"
         rel="stylesheet">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/frontend/lib/animate/animate.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/frontend/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/frontend/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/frontend/fontawesome-pro/css/all.min.css') }}" rel="stylesheet" />
    @yield('css')
</head>
