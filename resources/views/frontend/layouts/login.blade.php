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
    <link href="{{CSS}}app.css" rel="stylesheet">
    <link href="{{CSS}}common.css" rel="stylesheet">
    <link href="{{ASSETS}}css/style.css" rel="stylesheet"> 

    @yield('header_scripts')
</head>

<body> 

    <div class="content-margin">
        @yield('content')
    </div>

   
    <!-- Scripts -->
    <script src="{{ASSETS}}js/jquery-3.6.4.min.js"></script>
    
    @yield('js')

</body>

</html>