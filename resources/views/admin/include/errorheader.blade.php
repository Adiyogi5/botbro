 <!-- Navbar -->
  <nav class=" navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset($site_settings['logo']) }}" alt="{{ $site_settings['application_name'] }}" class="brand-image" style="opacity: .8"> 
    </a>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto"> 
      <li class="nav-item">
       <a class="nav-link" href="{{ route('admin.general_settings') }}"><i class="fas fa-cog"></i> General Settings</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user"></i> Profile
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ url('admin/profile') }}">Profile</a>
          <a class="dropdown-item" href="{{ url('admin/change-password') }}">Change Password</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
         Logout 
          <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
             @csrf
          </form>
        </a>
        </div>
      </li> 
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>



  </nav>
  <!-- /.navbar -->