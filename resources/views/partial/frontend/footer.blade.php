<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                <a href="{{ route('front.home') }}" class="d-inline-block mb-3">
                    {{-- <h1 class="text-white">AI<span class="text-primary">.</span>Tech</h1> --}}
                    <img src="{{ asset('storage/' . $site_settings['logo']) }}" class="footer-logo" alt="logo" />
                </a>
                <p class="mb-0">
                    {{-- {{ $site_settings['site_description'] }} --}}
                    Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                    amet diam et eos labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus
                    clita duo justo et tempor</p>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                <h5 class="text-white mb-4">Get In Touch</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i>{{ $site_settings['address'] }}</p>
                <p><i class="fa fa-phone-alt me-3"></i>{{ $site_settings['phone'] }}</p>
                <p><i class="fa fa-envelope me-3"></i>{{ $site_settings['email'] }}</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href="{{ $site_settings['twitter'] }}"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href="{{ $site_settings['facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                    {{-- <a class="btn btn-outline-light btn-social" href="{{ $site_settings['youtube'] }}"><i class="fab fa-youtube"></i></a> --}}
                    <a class="btn btn-outline-light btn-social" href="{{ $site_settings['instagram'] }}"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-light btn-social" href="{{ $site_settings['linkdin'] }}"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                <h5 class="text-white mb-4">Popular Link</h5>
                <a class="btn btn-link" href="{{ route('front.show-cms', 'about-us') }}">About Us</a>
                <a class="btn btn-link" href="{{ route('front.contact-us') }}">Contact Us</a>
                <a class="btn btn-link" href="{{ route('front.show-cms', 'privacy-policy') }}">Privacy Policy</a>
                <a class="btn btn-link" href="{{ route('front.show-cms', 'terms-and-conditions') }}">Terms & Condition</a>
                <a class="btn btn-link" href="{{ route('front.show-cms', 'career') }}">Career</a>
            </div>
            <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                <h5 class="text-white mb-4">Our Services</h5>
                <a class="btn btn-link" href="">Robotic Automation</a>
                <a class="btn btn-link" href="">Machine learning</a>
                <a class="btn btn-link" href="">Predictive Analysis</a>
                <a class="btn btn-link" href="">Data Science</a>
                <a class="btn btn-link" href="">Robot Technology</a>
            </div>
        </div>
    </div>
    <div class="container wow fadeIn" data-wow-delay="0.1s">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="https://adiyogitechnosoft.com">Adiyogi Technosoft</a>, {{ $site_settings['copyright'] }}.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="{{ route('front.home') }}">Home</a>
                        <a href="">Cookies</a>
                        <a href="">Help</a>
                        <a href="">FAQs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top pt-2"><i class="bi bi-arrow-up"></i></a>



<!-- ===============================================-->
<!--    JavaScripts Libraries-->
<!-- ===============================================-->
<script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/frontend/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/jquery.validate.js') }}"></script>
<script src="{{ asset('assets/frontend/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/frontend/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('assets/frontend/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('assets/frontend/lib/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('assets/frontend/lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ asset('assets/frontend/js/main.js') }}"></script>

@yield('js')
@include('partial.toastr')
