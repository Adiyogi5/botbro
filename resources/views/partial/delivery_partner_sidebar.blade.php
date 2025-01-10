<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row text-center">

        <div class="nav-logo">
            @if (Auth::guard('delivery_partner')->check())
            <div class="nav-item theme-logo">
                <a href="{{route('delivery_partner.dashboard')}}">
                    <img src="{{ imageexist($site_settings['favicon']) }}" class="navbar-logo" alt="" width="50">
                </a>
            </div>
            <div class="nav-item theme-text">
                <a href="{{route('delivery_partner.dashboard')}}" class="nav-link"> {{ $site_settings['application_name'] }} </a>
            </div>
            @endif
        </div>
        <div class="nav-item sidebar-toggle">
            <div class="btn-toggle sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                    <polyline points="11 17 6 12 11 7"></polyline>
                    <polyline points="18 17 13 12 18 7"></polyline>
                </svg>
            </div>
        </div>
    </div>
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
        @if (Auth::guard('delivery_partner')->check())
        <li class="menu @routeis('delivery_partner.dashboard') active @endrouteis">
            <a href="{{route('delivery_partner.dashboard')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-house"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('delivery_partner.order_list') active @endrouteis">
            <a href="{{ route('delivery_partner.order_list') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Orders</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('delivery_partner.delivery_boys.index') active @endrouteis">
            <a href="{{ route('delivery_partner.delivery_boys.index') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Delivery Boys</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('delivery_partner.manage_wallet.index') active @endrouteis">
            <a href="{{ route('delivery_partner.manage_wallet.index') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Manage Wallet</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('delivery_partner.withdraw_request.index') active @endrouteis">
            <a href="{{ route('delivery_partner.withdraw_request.index') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Withdraw Request</span>
                </div>
            </a>
        </li>
        <li class="menu @routeis('delivery_partner.delivery_partner_areas.index') active @endrouteis">
            <a href="{{ route('delivery_partner.delivery_partner_areas.index') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Delivery Areas</span>
                </div>
            </a>
        </li>
        @endif
    </ul>
</nav>