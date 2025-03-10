<header>
    <nav class="navbar navbar-expand-lg bg-white px-md-0 px-1">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ asset($site_settings['logo']) }}" class="img-fluid header-logo" alt="" />
            </a>
            <ul class="d-flex my-auto ms-auto" style="list-style:none;">
                @if (Auth::check() == null)
                            <li class="nav-item mx-lg-1 mx-1 text-nowrap my-auto d-lg-none d-block">
                                <a href="{{ route('frontend.joinus') }}"
                                    class="nav-link btn btn-upayliving">Investment</a>
                            </li>
                        @endif
                @if (Auth::guard('web')->check())
                    <li class="nav-item mx-lg-3 mx-md-3 mx-2 my-auto d-lg-none d-block">
                        <div class="dropdown user_dropdown">
                            <img role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"
                                src="{{ imageexist(Auth()->user()->image) }}" class="d-block profile-img"
                                alt="...">
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                        href="{{ route('frontend.dashboard') }}"><i
                                            class="fa-solid fa-dashboard me-2"></i>Dashboard</a></li>
                                <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                        href="{{ route('frontend.profile') }}"><i
                                            class="fa-solid fa-user me-2"></i>Profile</a></li>
                                <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                        href="{{ route('frontend.confirm_password') }}"><i
                                            class="fa-solid fa-lock fa-profile me-2"></i>Change Password</a></li>
                                <li class="d-flex">
                                    <form id="" method="post" action="{{ route('frontend.logout') }}"
                                        class="dropdown-item mb-0">
                                        @csrf
                                        <button type="submit" href=""
                                            class="nav-link nav-subtitle ps-0 bg-transparent">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item mx-lg-3 mx-1 my-auto d-lg-none d-block">
                        <a href="{{ route('frontend.login') }}" class="nav-link btn btn-custom-navbar"><i
                                class="fa-solid fa-right-from-bracket"></i> Login</a>
                    </li>
                @endif
            </ul>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <a href="{{ url('/') }}" class="navbar-brand">
                        <img src="{{ asset($site_settings['logo']) }}" class="img-fluid header-logo" alt="" />
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav ms-auto" style="list-style:none;">
                        <li class="nav-item mx-lg-3 mx-3">
                            <a href="{{ url('/') }}"
                                class="nav-link nav-title {{ request()->is('/') ? 'active' : '' }}">Home</a>
                        </li>

                        <li class="nav-item mx-lg-3 mx-3 dropdown">
                            <a href="#!"
                                class="nav-link nav-title dropdown-toggle {{ request()->routeIs('frontend.joinus') ? 'active' : '' }}"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Join Us
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="nav-link nav-subtitle border-bottom ps-2 {{ request()->routeIs('frontend.whyrobotrade') ? 'active' : '' }}"
                                        href="{{ route('frontend.whyrobotrade') }}">Why Join Robos trade</a></li>

                                @if (Auth::check() == null)
                                    <li><a class="nav-link nav-subtitle ps-2 {{ request()->routeIs('frontend.joinus') ? 'active' : '' }}"
                                            href="{{ route('frontend.joinus') }}">Join</a></li>
                                @endif
                            </ul>
                        </li>

                        <li class="nav-item mx-lg-3 mx-3 dropdown">
                            <a href="#!"
                                class="nav-link nav-title dropdown-toggle {{ request()->routeIs('frontend.aboutus') ? 'active' : '' }}"
                                data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="nav-link nav-subtitle border-bottom ps-2 {{ request()->routeIs('frontend.aboutus') ? 'active' : '' }}"
                                        href="{{ route('frontend.aboutus') }}">About Robos trade</a></li>
                                <li><a class="nav-link nav-subtitle ps-2 {{ request()->routeIs('frontend.ourleadership') ? 'active' : '' }}"
                                        href="{{ route('frontend.ourleadership') }}">Our Leadership</a></li>
                            </ul>
                        </li>

                        {{-- <li class="nav-item mx-lg-3 mx-3">
                            <a href="{{ route('frontend.blog') }}"
                                class="nav-link nav-title {{ request()->routeIs('frontend.blog') ? 'active' : '' }}">Blog</a>
                        </li> --}}

                        <li class="nav-item mx-lg-3 mx-3">
                            <a href="{{ route('frontend.contactus') }}"
                                class="nav-link nav-title {{ request()->routeIs('frontend.contactus') ? 'active' : '' }}">Contact Us</a>
                        </li>
                        @if (Auth::check() == null)
                            <li class="nav-item mx-lg-1 mx-3 text-nowrap ">
                                <a href="{{ route('frontend.joinus') }}"
                                    class="nav-link btn btn-upayliving px-xl-3 px-2 mt-1">Investment</a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->check())
                            <li class="nav-item mx-lg-3 mx-3 d-lg-block d-none">
                                <div class="dropdown user_dropdown">
                                    <img role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false" src="{{ imageexist(Auth()->user()->image) }}"
                                        class="d-block profile-img" alt="...">
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                                href="{{ route('frontend.dashboard') }}"><i
                                                    class="fa-solid fa-dashboard me-2"></i>Dashboard</a></li>
                                        <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                                href="{{ route('frontend.profile') }}"><i
                                                    class="fa-solid fa-user me-2"></i>Profile</a></li>
                                        <li class=""><a class="nav-link nav-subtitle border-bottom ps-3"
                                                href="{{ route('frontend.confirm_password') }}"><i
                                                    class="fa-solid fa-lock fa-profile me-2"></i>Change Password</a></li>
                                        <li class="d-flex">
                                            <form id="" method="post"
                                                action="{{ route('frontend.logout') }}"
                                                class="dropdown-item mb-0">
                                                @csrf
                                                <button type="submit" href=""
                                                    class="nav-link nav-subtitle ps-0 bg-transparent">
                                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @else
                            <li class="nav-item mx-lg-3 mx-3 my-auto d-lg-block d-none">
                                <a href="{{ route('frontend.login') }}" class="nav-link btn btn-custom-navbar"><i
                                        class="fa-solid fa-right-from-bracket"></i> Login</a>
                            </li>
                        @endif
                        {{-- @if (Auth::guard('web')->check() && $user_approved->is_approved == 1)
                            <li class="nav-item mx-lg-3 mx-3 mt-2 mt-md-0 d-lg-block d-none">
                                <a href="{{ url('cart') }}" class="nav-link position-relative">
                                    <img src="{{ asset('public/images/cart.png') }}" class="faa-bag" alt="">
                                    <span id="chatNotif"
                                        class="position-absolute cart_counter top-0 start-100 translate-bottom badge rounded-pill bg-danger">{{ $cart_count ? $cart_count : '0' }}
                                    </span></a>
                            </li>
                        @endif --}}
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>


@section('js')
    <!-- Popper.js -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script> --}}
@endsection
@section('header-js')
    <script type="text/javascript">
        function incrementValue1() {
            var value = parseInt(document.getElementById('number1').value, 10);
            value = isNaN(value) ? 0 : value;
            if (value < 100) {
                value++;
                document.getElementById('number1').value = value;
            }
        }

        function decrementValue1() {
            var value = parseInt(document.getElementById('number1').value, 10);
            value = isNaN(value) ? 0 : value;
            if (value > 1) {
                value--;
                document.getElementById('number1').value = value;
            }

        }
    </script>
    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 1500;
            init_like();
            init_cart();
            init_quantity();
            init_update_cart();
            init_quick_view_galley();
            init_attributes();

            $(".cart_counter").removeClass('transform-100');

            setTimeout(() => {
                $(".alert-dismissible").slideUp();
            }, 5000);
        })
    </script>
@endsection
