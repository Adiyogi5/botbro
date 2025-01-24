<div class="offcanvas-header bg-lime px-3 text-start justify-content-start py-2 rounded">
    <img src="{{ imageexist(Auth::user()->image) }}" class="d-block profile-img" alt="...">
    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
        {{ Auth::user()->name }}</h5>
    <a href="#" data-bs-target="#sidebar" data-bs-toggle="collapse"
        class="ms-auto text-white d-md-none d-block text-center justify-content-center border bg-lime rounded-3 p-1 text-decoration-none">
        <i class="fa fa-list fa-lg py-2 p-1 text-white"></i></a>
</div>

<div class="collapse collapse-vertical show border-end" id="sidebar">
    <div class="offcanvas-body mt-xl-3 mt-2" id="sidebar-nav">
        <div class="sidebar-content">
            <ul class="navbar-nav justify-content-start flex-grow-1 border bg-white">
                <li
                    class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.dashboard') ? 'text-white bg-lime' : '' }}">
                    <a class="nav-link sidebar-link {{ request()->routeIs('frontend.dashboard') ? 'text-white bg-lime' : '' }}"
                        aria-current="page"
                        style="{{ request()->routeIs('frontend.dashboard') ? 'color:white !important;;' : '' }}"
                        href="{{ route('frontend.dashboard') }}"> <i class="fa-solid fa-grip faa-profile"></i>
                        Dashboard</a>
                </li>

                <li
                    class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.profile') ? 'text-white bg-lime' : '' }}">
                    <a class="nav-link sidebar-link {{ request()->routeIs('frontend.profile') ? 'text-white bg-lime' : '' }}"
                        aria-current="page"
                        style="{{ request()->routeIs('frontend.profile') ? 'color:white !important;;' : '' }}"
                        href="{{ route('frontend.profile') }}"><i class="fa-solid fa-user faa-profile"></i> Profile</a>
                </li>

                <li
                    class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.confirm_password') ? 'text-white bg-lime' : '' }}">
                    <a class="nav-link sidebar-link {{ request()->routeIs('frontend.confirm_password') ? 'text-white bg-lime' : '' }}"
                        aria-current="page"
                        style="{{ request()->routeIs('frontend.confirm_password') ? 'color:white !important;' : '' }}"
                        href="{{ route('frontend.confirm_password') }}"><i class="fa-solid fa-lock faa-profile"></i>
                        Change Password</a>
                </li>
                @if ($user_approved->is_approved == 1)
                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.products') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.products') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.products') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.products') }}"><i class="fa-solid fa-globe faa-profile"></i>
                            Products</a>
                    </li> --}}

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.my_order') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.my_order') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.my_order') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.my_order') }}"><i
                                class="fa-solid fa-cart-shopping faa-profile"></i> My Order</a>
                    </li> --}}

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.my_return') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.my_return') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.my_return') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.my_return') }}"><i class="fa-solid fa-undo faa-profile"></i>
                            Returns/Replace Request</a>
                    </li> --}}
                    <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.investment') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.investment') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.investment') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.investment') }}"><i
                                class="fa-solid fa-money-bill-transfer faa-profile"></i> My Investment</a>
                    </li>

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.my_wallet') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.my_wallet') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.my_wallet') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.my_wallet') }}"><i class="fa-solid fa-wallet faa-profile"></i> My
                            Wallet</a>
                    </li> --}}

                    <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.my_address') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.my_address') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.my_address') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.my_address') }}"><i
                                class="fa-solid fa-location-dot faa-profile"></i> My Address</a>
                    </li>

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.badge_history') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.badge_history') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.badge_history') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.badge_history') }}"><i class="fa-solid fa-award faa-profile"></i>
                            Badge History</a>
                    </li> --}}

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.reward_history') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.reward_history') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.reward_history') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.reward_history') }}"><i
                                class="fa-solid fa-trophy faa-profile"></i> Reward History</a>
                    </li> --}}

                    <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.reffer_history') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.reffer_history') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.reffer_history') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.reffer_history') }}"><i
                                class="fa-solid fa-network-wired faa-profile"></i> Reffer History</a>
                    </li>

                    <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.commission_history') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.commission_history') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.commission_history') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.commission_history') }}"><i
                                class="fa-solid fa-hand-holding-dollar faa-profile"></i> Commission History</a>
                    </li>

                    {{-- <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.profit_history') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.profit_history') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.profit_history') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.profit_history') }}"><i
                                class="fa-solid fa-sack-dollar faa-profile"></i> Profit History</a>
                    </li>

                    <li
                        class="nav-item px-3 text-center justify-content-start d-flex border-bottom {{ request()->routeIs('frontend.withdrow_request') ? 'text-white bg-lime' : '' }}">
                        <a class="nav-link sidebar-link {{ request()->routeIs('frontend.withdrow_request') ? 'text-white bg-lime' : '' }}"
                            aria-current="page"
                            style="{{ request()->routeIs('frontend.withdrow_request') ? 'color:white !important;' : '' }}"
                            href="{{ route('frontend.withdrow_request') }}"><i
                                class="fa-solid fa-money-bill-transfer faa-profile"></i> Withdrow Request</a>
                    </li> --}}
                @endif
            </ul>
        </div>
    </div>
</div>
