<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name', 'UPayLiving') }}</title>
<link rel="icon" type="image/x-icon" href="{{ asset($site_settings['favicon']) }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Styles -->
<link href="{{CSS}}app.css" rel="stylesheet">
<link href="{{CSS}}all.min.css" rel="stylesheet">
<link href="{{CSS}}common.css" rel="stylesheet">
@yield('header_scripts')

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Main Header --> 
   @include('admin/include/header') 

  <!-- Control Sidebar -->
  @include('admin/include/sidebar') 

  <!-- Main Content --> 
  <div class="content-wrapper">
    <!-- Control Breadcrumbs -->
    @include('admin/include/breadcrumb') 

    <!-- flash-message-blade -->
    @include('admin/include/flash-message') 

    <!-- Content --> 
    @yield('content')
  </div>
  
  <!-- Main Footer --> 
  @include('admin/include/footer')
</div> 
<!-- Scripts -->
<script src="{{JS}}app.js"></script>
<script src="{{JS}}all.min.js"></script>   
<script type="text/javascript">
  window.setTimeout(function() {
      $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
          $(this).remove(); 
      });
  }, 5000); 
</script>
@yield('footer_scripts') 
</body>
</html>
