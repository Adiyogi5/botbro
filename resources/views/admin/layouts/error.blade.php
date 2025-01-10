<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name', 'Hotelsuliya') }}</title>

<!-- Styles -->
<link href="{{CSS}}app.css" rel="stylesheet">
<link href="{{CSS}}common.css" rel="stylesheet">
<style>
  .error-justifed{
    font-size: 16px;
    text-align: center;
    margin-top: 10px;
  }
</style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Main Header --> 
   @include('admin/include/errorheader') 
 
 <!-- Main Content --> 
  <div class="content"> 
    <!-- Content --> 
    @yield('content')
  </div>   
</div>  
<script src="{{ JS }}app.js"></script>
<script src="{{ JS }}all.min.js"></script>
</body>
</html>
