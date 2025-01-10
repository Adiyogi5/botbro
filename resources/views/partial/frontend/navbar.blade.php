 <!-- Spinner Start -->
 <div id="spinner"
 class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
 <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
     <span class="sr-only">Loading...</span>
 </div>
</div>
<!-- Spinner End -->


<!-- Navbar Start -->
<div class="container-fluid sticky-top">
 <div class="container">
     <nav class="navbar navbar-expand-lg navbar-dark p-0">
         <a href="{{ route('front.home') }}" class="navbar-brand">
             {{-- <h1 class="text-white">AI<span class="text-dark">.</span>Tech</h1> --}}
             <img src="{{ asset('storage/' . $site_settings['logo']) }}" class="navbar-logo" alt="logo" />
         </a>
         <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
             data-bs-target="#navbarCollapse">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarCollapse">
             <div class="navbar-nav ms-auto">
                 <a href="{{ route('front.home') }}" class="nav-item nav-link {{ request()->route()->getName() === 'front.home' ? 'active' : '' }}">Home</a>
                 <a href="{{ route('front.show-cms', 'about-us') }}" class="nav-item nav-link {{ request()->route()->getName() === 'front.show-cms' && request()->route('cms') === 'about-us' ? 'active' : '' }}">About</a>
                 <a href="service.html" class="nav-item nav-link">Services</a>
                 <a href="project.html" class="nav-item nav-link">Projects</a>
                 <div class="nav-item dropdown">
                     <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                     <div class="dropdown-menu bg-light mt-2">
                         <a href="{{ route('front.features') }}" class="dropdown-item">Features</a>
                         <a href="{{ route('front.teams') }}" class="dropdown-item">Our Team</a>
                         <a href="{{ route('front.faqs') }}" class="dropdown-item">FAQs</a>
                         <a href="{{ route('front.testimonials') }}" class="dropdown-item">Testimonial</a>
                     </div>
                 </div>
                 <a href="{{ route('front.contact-us') }}" class="nav-item nav-link {{ request()->route()->getName() === 'front.contact' ? 'active' : '' }}">Contact</a>
             </div>
             <butaton type="button" class="btn text-white p-0 d-none d-lg-block" data-bs-toggle="modal"
                 data-bs-target="#searchModal"><i class="fa fa-search"></i></butaton>
         </div>
     </nav>
 </div>
</div>
<!-- Navbar End -->

 <!-- Full Screen Search Start -->
 <div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background: rgba(20, 24, 62, 0.7);">
            <div class="modal-header border-0">
                <button type="button" class="btn btn-square bg-white btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center">
                <div class="input-group" style="max-width: 600px;">
                    <input type="text" class="form-control bg-transparent border-light p-3"
                        placeholder="Type search keyword">
                    <button class="btn btn-light px-4"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Full Screen Search End -->