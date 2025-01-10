<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row text-center">
        <div class="nav-logo">
            @if (Auth::guard('admin')->check())
            <div class="nav-item theme-logo">
                <a href="{{route('admin.dashboard')}}">
                    <img src="{{ imageexist($site_settings['favicon']) }}" class="navbar-logo" alt="" width="50">
                </a>
            </div>
            <div class="nav-item theme-text">
                <a href="{{route('admin.dashboard')}}" class="nav-link"> {{ $site_settings['application_name'] }} </a>
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
        @if (Auth::guard('admin')->check())
        <li class="menu @routeis('admin.dashboard') active @endrouteis">
            <a href="{{route('admin.dashboard')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-house"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        @endif

        @if (Auth::guard('admin')->check())
        @if(userCan([101,102,103,104]))
        <li class="menu @routeis('admin.roles,admin.sub_admin,admin.banners,admin.cms.list,admin.homecms.list') active @endrouteis">
            <a href="#master" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.roles,admin.sub_admin,admin.banners,admin.cms.list,admin.homecms.list') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-solid fa-sparkles" aria-hidden="{{ routeis('admin.roles,admin.sub_admin,admin.banners, admin.cms.list,admin.homecms.list') }}"></i>
                    <span>Master</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.roles,admin.sub_admin,admin.banners,admin.cms.list,admin.homecms.list') show @endrouteis" id="master" data-bs-parent="#accordionExample">
                @if(userCan(101))
                <li class="@routeis('admin.roles') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.roles') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Roles</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(102))
                <li class="@routeis('admin.sub_admin') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.sub_admin') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Sub Admin</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(103))
                <li class="nav-link @routeis('admin.banners') active @endrouteis">
                    <a href="#banners" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.banners') }}" class="dropdown-toggle">
                        <div class="">
                            <span>Banners</span>
                        </div>
                        <div> <i class="fa-solid fa-chevron-right"></i> </div>
                    </a>
                    <ul class="collapse submenu @routeis('admin.banners') show @endrouteis" id="banners" data-bs-parent="#master">
                        @foreach(config('constant.banner_array', []) as $key => $banner)
                        <li class="@if(request()->path() == 'admin/banners/'.$key) active @endif">
                            <a class="nav-link" href="{{ url('admin/banners', ['id' => $key]) }}" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">{{ $banner }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
                @if(userCan(104))
                <li class="nav-link @routeis('admin.cms.list,admin.homecms.list') active @endrouteis">
                    <a href="#cms" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.cms.list') }}" class="dropdown-toggle">
                        <div class="">
                            <span>CMS</span>
                        </div>
                        <div> <i class="fa-solid fa-chevron-right"></i> </div>
                    </a>
                    <ul class="collapse submenu @routeis('admin.cms.list,admin.homecms.list') show @endrouteis" id="cms" data-bs-parent="#master">
                        <li class="@routeis('admin.homecms.list,admin.homecms.list') active @endrouteis">
                            <a class="nav-link" href="{{ route('admin.homecms.list')}}" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Home CMS</span>
                                </div>
                            </a>
                        </li>
                        @foreach(config('constant.cms_array', []) as $key => $banner)
                        <li class="@if(request()->path() == 'admin/cms/'.$key) active @endif">
                            <a class="nav-link" href="{{ url('admin/cms', ['id' => $key]) }}" data-bs-toggle="" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">{{ $banner }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(userCan(105))
        <li class="menu @routeis('admin.coupons.index') active @endrouteis">
            <a href="{{route('admin.coupons.index')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Coupons</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(122))
        <li class="menu @routeis('admin.offer') active @endrouteis">
            <a href="{{route('admin.offer')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Offers</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(125))
        <li class="menu @routeis('admin.orders') active @endrouteis">
            <a href="{{route('admin.orders')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Orders</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(119))
        <li class="menu @routeis('admin.products.index') active @endrouteis">
            <a href="{{route('admin.products.index')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Products</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(106))
        <li class="menu @routeis('admin.categories.index') active @endrouteis">
            <a href="{{route('admin.categories.index')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Categories</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(107))
        <li class="menu @routeis('admin.users') active @endrouteis">
            <a href="{{route('admin.users')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Users</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(119))
        <li class="menu @routeis('admin.distributor') active @endrouteis">
            <a href="{{route('admin.distributor')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Distributor</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan([108,121,123]))
        <li class="menu @routeis('admin.vendors,admin.vendor.products,admin.vendor.products.request,admin.vendor.withdraw.request') active @endrouteis">
            <a href="#vendor_withdraw" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.vendors,admin.vendor.products,admin.vendor.products.request,admin.vendor.withdraw.request') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Vendor</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.vendors,admin.vendor.products,admin.vendor.products.request,admin.vendor.withdraw.request') show @endrouteis" id="vendor_withdraw" data-bs-parent="#accordionExample">
                @if(userCan(108))
                <li class="@routeis('admin.vendors') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.vendors') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Vendors</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(121))
                <li class="@routeis('admin.vendor.products') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.vendor.products') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Vendor Products</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(121))
                <li class="@routeis('admin.vendor.products.request') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.vendor.products.request') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Vendor Product Requests</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(123))
                <li class="@routeis('admin.vendor.withdraw.request') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.vendor.withdraw.request') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Vendor Withdraw Requests</span></div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(userCan([109,124]))
        <li class="menu @routeis('admin.delivery_partner,admin.delivery_partner.withdraw.request') active @endrouteis">
            <a href="#delivery_partner_withdraw" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.delivery_partner,admin.delivery_partner.withdraw.request') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Delivery Partner</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.delivery_partner,admin.delivery_partner.withdraw.request') show @endrouteis" id="delivery_partner_withdraw" data-bs-parent="#accordionExample">
                @if(userCan(109))
                <li class="@routeis('admin.delivery_partner') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.delivery_partner') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Delivery Partner</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(124))
                <li class="@routeis('admin.delivery_partner.withdraw.request') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.delivery_partner.withdraw.request') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Delivery Partner Withdraw Request</span></div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(userCan(113))
        <li class="menu @routeis('admin.notifications') active @endrouteis">
            <a href="{{url('admin/notifications')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Notification</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(120))
        <li class="menu @routeis('admin.users_wishlist') active @endrouteis">
            <a href="{{route('admin.users_wishlist')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Users Wishlist</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan(124))
        <li class="menu @routeis('admin.testimonials') active @endrouteis">
            <a href="{{route('admin.testimonials')}}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Testimonials</span>
                </div>
            </a>
        </li>
        @endif
        @if(userCan([111,112]))
        <li class="menu @routeis('admin.attribute_master,admin.attribute') active @endrouteis">
            <a href="#attribute_content" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.attribute_master,admin.attribute') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-photo-film"></i>
                    <span>Attributes</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.attribute_master,admin.attribute') show @endrouteis" id="attribute_content" data-bs-parent="#accordionExample">
                @if(userCan(111))
                <li class="@routeis('admin.attribute_master') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.attribute_master') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Attribute Master</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(112))
                <li class="@routeis('admin.attribute') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.attribute') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Attribute</span></div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(userCan([114,115,116,117]))
        <li class="menu @routeis('admin.countries,admin.states,admin.cities,admin.areas') active @endrouteis">
            <a href="#location_content" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.countries,admin.states,admin.cities,admin.areas') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa-duotone fa-location-dot"></i>
                    <span>Location</span>
                </div>
                <div> <i class="fa-solid fa-chevron-right"></i> </div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.countries,admin.states,admin.cities,admin.areas') show @endrouteis" id="location_content" data-bs-parent="#accordionExample">
                @if(userCan(114))
                <li class="@routeis('admin.countries') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.countries') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Countries</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(115))
                <li class="@routeis('admin.states') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.states') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">States</span></div>
                    </a>
                </li>
                @endif
                @if(userCan(116))
                <li class="@routeis('admin.cities') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.cities') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Cities</span>
                        </div>
                    </a>
                </li>
                @endif
                @if(userCan(117))
                <li class="@routeis('admin.areas') active @endrouteis">
                    <a class="nav-link" href="{{ route('admin.areas') }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Areas</span>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if(userCan(118))
        <li class="menu @routeis('admin.setting') active @endrouteis">
            <a href="#setting" data-bs-toggle="collapse" aria-expanded="{{ routeis('admin.setting') }}" class="dropdown-toggle">
                <div class="">
                    <i class="fa fa-cog my-auto" aria-hidden="{{ routeis('admin.setting') }}"></i>
                    <span>App Setting</span>
                </div>
                <div><i class="fa-solid fa-chevron-right"></i></div>
            </a>
            <ul class="collapse submenu list-unstyled @routeis('admin.setting') show @endrouteis" id="setting" data-bs-parent="#accordionExample">
                @foreach(config('constant.setting_array', []) as $key => $setting)
                <li class="@if(request()->path() == 'admin/setting/'.$key) active @endif">
                    <a class="nav-link" href="{{ route('admin.setting', ['id' => $key]) }}" data-bs-toggle="" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-text ps-1">{{ $setting }}</span>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        @endif
        @endif
    </ul>
</nav>