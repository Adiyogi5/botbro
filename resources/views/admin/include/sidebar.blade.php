<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset($site_settings['footer_logo']) }}" alt="{{ $site_settings['application_name'] }}" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">&nbsp;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (Auth::guard('admin')->check())
                <img src="{{ get_image(Auth::guard('admin')->user()->image, 'map') }}" class="img-circle elevation-2" alt="User Image">
                <span class="brand-text font-weight-light col-white">&nbsp;{{ Auth::guard('admin')->user()->name }}</span>
                @endif
            </div>
            <div class="info">
                <a href="#" class="d-block"></a>
            </div>
        </div>

        @php
        $active1 = $active2 = $active3 = $active4 = $active5 = $active6 = $active7 = $active8 = $active9 = $active10 = $active11 = $active12 = $active13 = $active14 = $active15 = $active16 = $active17 = $active18 = '';
        $fullpage = url()->current();
        $fullpage = explode('/', $fullpage);

        if (in_array('dashboard', $fullpage)) {
        $active1 = 'active';
        }

        $sidemenu = ['subadmin', 'banner', 'offer', 'role', 'badge_masters', 'reward_masters'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active2 = 'active';
        }
        if (in_array('subadmin', $fullpage)) {
        $a103 = 'active';
        }
        if (in_array('banner', $fullpage)) {
        $a102 = 'active';
        }
        if (in_array('offer', $fullpage)) {
        $a105 = 'active';
        }
        if (in_array('role', $fullpage)) {
        $a107 = 'active';
        }
        if (in_array('badge_masters', $fullpage)) {
        $a121 = 'active';
        }
        if (in_array('reward_masters', $fullpage)) {
        $a122 = 'active';
        }
        }

        $sidemenu = ['customer'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active3 = 'active';
        }
        if (in_array('customer', $fullpage)) {
        $a115 = 'active';
        }
        }

        $sidemenu = ['categories', 'products'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active4 = 'active';
        }
        if (in_array('categories', $fullpage)) {
        $a111 = 'active';
        }
        if (in_array('products', $fullpage)) {
        $a112 = 'active';
        }
        }

        $sidemenu = ['blog_categories', 'blog'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active5 = 'active';
        }
        if (in_array('blog_categories', $fullpage)) {
        $a109 = 'active';
        }
        if (in_array('blog', $fullpage)) {
        $a110 = 'active';
        }
        }

        if (in_array('testimonials', $fullpage)) {
        $active6 = 'active';
        }

        $sidemenu = ['countries', 'states', 'cities'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active7 = 'active';
        }
        if (in_array('countries', $fullpage)) {
        $a117 = 'active';
        }
        if (in_array('states', $fullpage)) {
        $a118 = 'active';
        }
        if (in_array('cities', $fullpage)) {
        $a119 = 'active';
        }
        }

        $sidemenu = ['faq_types', 'faqs'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active8 = 'active';
        }
        if (in_array('faq_types', $fullpage)) {
        $a113 = 'active';
        }
        if (in_array('faqs', $fullpage)) {
        $a106 = 'active';
        }
        }

        $sidemenu = ['cms','homecms'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active9 = 'active';
        }
        if (in_array('cms', $fullpage)) {
        $a201 = 'active';
        }
        if (in_array('homecms', $fullpage)) {
        $a202 = 'active';
        }
        }

        $sidemenu = ['orders'];
        foreach ($sidemenu as $menukey => $menuvalue) {
        if (in_array($menuvalue, $fullpage)) {
        $active11 = 'active';
        }
        if (in_array('orders', $fullpage)) {
        $a1101 = 'active';
        }
        
        }

        if (in_array('returns', $fullpage)) {
        $active12 = 'active';
        }

        if (in_array('rewards', $fullpage)) {
        $active13 = 'active';
        }

        if (in_array('admin_notifications', $fullpage)) {
        $active14 = 'active';
        }

        if (in_array('profit_shares', $fullpage)) {
        $active15 = 'active';
        }

        if (in_array('contact_inquires', $fullpage)) {
        $active16 = 'active';
        }
        
        @endphp

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar  flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (userCan(102))
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $active1 }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                @endif

                @if (userCan([103, 104, 105, 106, 107, 108]))
                <li class="nav-item {{ @$active2 }} {{ @$active2 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active2 }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p> Masters <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(103))
                        <li class="nav-item">
                            <a href="{{ url('admin/role') }}" class="nav-link {{ @$a107 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Role</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(104))
                        <li class="nav-item">
                            <a href="{{ url('admin/subadmin') }}" class="nav-link {{ @$a103 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sub Admin </p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(105))
                        <li class="nav-item">
                            <a href="{{ route('admin.banner.index') }}" class="nav-link {{ @$a102 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banners</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(106))
                        <li class="nav-item">
                            <a href="{{ route('admin.offer.index') }}" class="nav-link {{ @$a105 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Offers</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(107))
                        <li class="nav-item">
                            <a href="{{ route('admin.badge_masters.index') }}" class="nav-link {{ @$a121 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Badge Masters</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(108))
                        <li class="nav-item">
                            <a href="{{ route('admin.reward_masters.index') }}" class="nav-link {{ @$a122 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reward Masters</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (userCan([109, 110]))
                <li class="nav-item {{ @$active9 }} {{ @$active9 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active9 }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p> CMS <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(109))
                        <li class="nav-item">
                            <a href="{{ route('admin.cms.index') }}" class="nav-link {{ @$a201 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cms </p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(110))
                        <li class="nav-item">
                            <a href="{{ route('admin.homecms.index') }}" class="nav-link {{ @$a202 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Home Cms </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (userCan([111]))
                <li class="nav-item {{ @$active3 }} {{ @$active3 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active3 }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p> Users <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(111))
                        <li class="nav-item">
                            <a href="{{ route('admin.customer.index') }}" class="nav-link {{ @$a115 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customers </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (userCan([112, 113]))
                <li class="nav-item {{ @$active8 }} {{ @$active8 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active8 }}">
                        <i class="nav-icon fas fa-question"></i>
                        <p> Faqs <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(112))
                        <li class="nav-item">
                            <a href="{{ route('admin.faq_types.index') }}" class="nav-link {{ @$a113 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQ Types </p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(113))
                        <li class="nav-item">
                            <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ @$a106 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Faqs</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (userCan([114, 115]))
                {{-- <li class="nav-item {{ @$active4 }} {{ @$active4 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active4 }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <p> Products <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(114))
                        <li class="nav-item">
                            <a href="{{ url('admin/categories') }}" class="nav-link {{ @$a111 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product Category </p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(115))
                        <li class="nav-item">
                            <a href="{{ url('admin/products') }}" class="nav-link {{ @$a112 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> --}}
                @endif

                @if (userCan([116]))
                <li class="nav-item {{ @$active11 }} {{ @$active11 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active11 }}">
                        <i class="fa-solid fa-list nav-icon"></i>
                        <p>Orders <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(116))
                        <li class="nav-item">
                            <a href="{{ url('admin/orders') }}" class="nav-link {{ @$a1101 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Order List</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (userCan(120))
                <li class="nav-item <?=$active12?> <?=($active12)?" menu-open":""?>">
                    <a href="{{url('admin/returns')}}" class="nav-link <?=$active12?>">
                        <i class="nav-icon fa fa-undo"></i>
                        <p> Returns </p>
                    </a>
                </li>
                @endif

                @if (userCan(121))
                <li class="nav-item <?=$active13?> <?=($active13)?" menu-open":""?>">
                    <a href="{{url('admin/rewards')}}" class="nav-link <?=$active13?>">
                        <i class="nav-icon fa fa-award"></i>
                        <p> Reward Logs</p>
                    </a>
                </li>
                @endif

                @if (userCan(126))
                <li class="nav-item">
                    <a href="{{ route('admin.profit_shares.index') }}" class="nav-link {{ @$active15 }}">
                        <i class="fas fa-sack-dollar nav-icon"></i>
                        <p>Profit Sharing </p>
                    </a>
                </li>
                @endif

                @if (userCan([123, 124]))
                {{-- <li class="nav-item {{ @$active5 }} {{ @$active5 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active5 }}">
                        <i class="fa-solid fa-blog nav-icon"></i>
                        <p>Blogs <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(123))
                        <li class="nav-item">
                            <a href="{{ url('admin/blog_categories') }}" class="nav-link {{ @$a109 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blog Category</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(124))
                        <li class="nav-item">
                            <a href="{{ url('admin/blog') }}" class="nav-link {{ @$a110 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blog</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li> --}}
                @endif

                @if (userCan(122))
                <li class="nav-item">
                    <a href="{{ url('admin/testimonials') }}" class="nav-link {{ @$active6 }}">
                        <i class="fas fa-comments nav-icon"></i>
                        <p>Testimonials </p>
                    </a>
                </li>
                @endif

                @if (userCan(125))
                <li class="nav-item">
                    <a href="{{ route('admin.admin_notifications.index') }}" class="nav-link {{ @$active14 }}">
                        <i class="fas fa-bell nav-icon"></i>
                        <p>Admin Notifications </p>
                    </a>
                </li>
                @endif

                @if (userCan(127))
                <li class="nav-item">
                    <a href="{{ url('admin/contact_inquires') }}" class="nav-link {{ @$active16 }}">
                        <i class="fas fa-envelope nav-icon"></i>
                        <p>Contact Inquiry </p>
                    </a>
                </li>
                @endif
                
                @if (userCan([117, 118, 119]))
                <li class="nav-item {{ @$active7 }} {{ @$active7 ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ @$active7 }}">
                        <i class="fa fa-map-marker  nav-icon"></i>
                        <p>Location <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (userCan(117))
                        <li class="nav-item">
                            <a href="{{ url('admin/countries') }}" class="nav-link {{ @$a117 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Country</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(118))
                        <li class="nav-item">
                            <a href="{{ url('admin/states') }}" class="nav-link {{ @$a118 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>State</p>
                            </a>
                        </li>
                        @endif
                        @if (userCan(119))
                        <li class="nav-item">
                            <a href="{{ url('admin/cities') }}" class="nav-link {{ @$a119 }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>City</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>