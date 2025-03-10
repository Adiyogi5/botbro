    <!-- Grid container -->
    <hr class="bg-white text-white" style="margin: 0.2rem 0;">
    <footer class="bg-footer">
        <div class="container d-flex flex-column mx-auto py-lg-5 py-md-4 py-3">
            <div class="row d-flex flex-wrap justify-content-between">
                <div
                    class="col-lg-3 col-md-4 col-sm-4 col-12 text-sm-start justify-content-sm-start text-center justify-content-center mt-md-0 mt-3">
                    <p class="h5 mb-4 footer-heading">Main Links</p>
                    <ul class="p-0" style="list-style: none; cursor: pointer">
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white" href="{{ url('/') }}">Home</a>
                        </li>
                        @if (Auth::check() == null)
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.joinus') }}">Join Us</a>
                        </li>
                        @endif
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.aboutus') }}">About Us</a>
                        </li>
                        {{-- <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.blog') }}">Blog</a>
                        </li> --}}
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.contactus') }}">Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div
                    class="col-lg-3 col-md-4 col-sm-4 col-12 text-sm-start justify-content-sm-start text-center justify-content-center mt-md-0 mt-3">
                    <p class="h5 mb-4 footer-heading">Policy</p>
                    <ul class="p-0" style="list-style: none; cursor: pointer">
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.privacy') }}">Privacy Policy</a>
                        </li>
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.terms') }}">Terms and Conditions</a>
                        </li>
                        <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.shipandcancel') }}">Cancellation and Refund Policy</a>
                        </li>
                        {{-- <li class="my-2">
                            <a class="footer-title text-decoration-none text-white"
                                href="{{ route('frontend.shipandcancel') }}">Shipping and Delivery</a>
                        </li> --}}
                    </ul>
                </div>

                {{-- <div
                    class="col-lg-2 col-md-4 col-sm-4 col-12 text-sm-start justify-content-sm-start text-center justify-content-center mt-md-0 mt-3">
                    <p class="h5 mb-4 footer-heading">Social</p>
                    <span class="mt-4">
                        @if (!empty($site_settings['twitter_link']))
                            <a href="{{ $site_settings['twitter_link'] }}" class="btn btn-icon rounded-5 me-lg-2 me-1">
                                <i class="fa-brands fa-x-twitter faa-footer"></i>
                            </a>
                        @endif
                        @if (!empty($site_settings['facebook_link']))
                            <a href="{{ $site_settings['facebook_link'] }}" class="btn btn-icon rounded-5 me-lg-2 me-1">
                                <i class="fa-brands fa-facebook-f faa-footer"></i>
                            </a>
                        @endif
                        @if (!empty($site_settings['linkedin_link']))
                            <a href="{{ $site_settings['linkedin_link'] }}" class="btn btn-icon rounded-5 me-lg-2 me-1">
                                <i class="fa-brands fa-linkedin-in faa-footer"></i>
                            </a>
                        @endif
						
						@if (!empty($site_settings['youtube_link']))
                            <a href="{{ $site_settings['youtube_link'] }}" class="btn btn-icon rounded-5 me-lg-2 me-1">
                                <i class="fa-brands fa-youtube faa-footer"></i>
                            </a>
                        @endif
						
						@if (!empty($site_settings['instagram_link']))
                            <a href="{{ $site_settings['instagram_link'] }}" class="btn btn-icon rounded-5 me-lg-2 me-1">
                                <i class="fa-brands fa-instagram faa-footer"></i>
                            </a>
                        @endif
                    </span>
                </div> --}}

                <div
                    class="col-lg-4 col-md-12 col-12 text-lg-end justify-content-lg-end text-center justify-content-center mt-md-0 mt-3">
                    <a href="{{ url('/') }}" class=" p-0 text-dark">
                        <img class="footer-logo" alt="footer-logo" src="{{ asset($site_settings['footer_logo']) }}" />
                    </a>
                    @if (!empty($site_settings['address']))
                        <p class="my-3 footer-content">
                            <span>{{ $site_settings['address'] }}</span><br>
                            <span><strong>Phone : </strong><a href="tel:{{ $site_settings['phone'] }}" class="text-white"> {{ $site_settings['phone'] }} </a></span> <br>
                            <span><strong> E-Mail : </strong><a href="mailto:{{$site_settings['email']}}" class="text-white"> {{ $site_settings['email'] }}</a></span>
                        </p>
                    @endif
                </div>
            </div>
            <!-- Grid container -->
        </div>
    </footer>

    <!-- Copyright -->
    <div class="container text-center justify-content-center py-2 px-2 bg-light copyright d-md-flex d-grid">
        <span> {{ $site_settings['copyright'] }} </span> 
    </div>
    <!-- Copyright -->
