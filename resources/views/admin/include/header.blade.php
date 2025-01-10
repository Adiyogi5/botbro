<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{route('admin.dashboard')}}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell"></i> Notifications
        @if(count($header_info['admin_notifications']) > 0)
        <small class="badge position-absolute top-0 end-0 mt-1 me-3 translate-middle-y px-1 text-white rounded-pill bg-primary">{{ $header_info['total_notifications'] }}</small>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @if(count($header_info['admin_notifications']) > 0)
        @foreach($header_info['admin_notifications'] as $key => $notification)
        {{ session()->flash('is_read', $notification->is_read) }}
        <a class="dropdown-item" href="{{ route('admin.admin_notifications.index', ['id' => $notification->id]) }}" title="{{ $notification->title }}">
          <h6 class="mb-0 fw-bold">{{ $notification->title }}</h6>
          <p style="margin: 3px 0px;">{!! $notification->message !!}</p>
          <small class="">{{ $notification->created_at->diffForHumans() }}</small>
        </a>
        @endforeach
        @else
        <div class="drodpown-title mb-0">
          <h6 class="d-flex justify-content-center mb-0">
            <span class="align-self-center">No New Notifications</span>
          </h6>
        </div>
        @endif        
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('admin.admin_notifications.index') }}">View All</a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.general_settings') }}"><i class="fas fa-cog"></i> Gernal Settings</a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i> Profile
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ url('admin/profile') }}">Profile</a>
        <a class="dropdown-item" href="{{ url('admin/change-password') }}">Change Password</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout
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