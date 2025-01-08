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
                         <a href="feature.html" class="dropdown-item">Features</a>
                         <a href="team.html" class="dropdown-item">Our Team</a>
                         <a href="faq.html" class="dropdown-item">FAQs</a>
                         <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                         <a href="404.html" class="dropdown-item">404 Page</a>
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