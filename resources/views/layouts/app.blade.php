<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{CSS}}app.css">

    <title>{{config('app.name')}}</title>
</head>
<body>
    @include('inc.navbar')
   <main class="container mt-4">
        @yield('content')
    </main>

<script src="{{JS}}app.js"></script>  
<script src="{{JS}}all.min.js"></script> 

</body>
</html>