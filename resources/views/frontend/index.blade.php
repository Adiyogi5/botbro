@extends('frontend.layouts.app')

@section('content')
    {{-- ######################## Banner Start ############################# --}}
    <section id="Banner">
        <div id="robotcarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banners as $key => $row)
                <div class="carousel-item h-100 {{ $key == 0 ? 'active' : '' }}">
                    <img class="banner-img d-block w-100" src="{{ imageexist($row['image']) }}" alt="UPayLiving">
                    <div class="carousel-content absolute-text">
                        <h1 class="banner-title">{{ $row['name'] }}</h1>
                        <p class="banner-content">{{ $row['content'] }}</p>
                        <a href="{{ $row['url'] }}" class="btn btn-md btn-light btn-banner fw-bold">{{ $row['title'] }}</a>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#robotcarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#robotcarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    
    {{-- ######################## Banner End ############################# --}}


    {{-- ######################## Why Upayliving ############################# --}}
    @if (!empty($homecms[0]))
    <section id="Why-Upayliving" class="bg-why-upayliving">
        <div class="container py-3">
            <div class="row">
                <div class="col-md-5 col-12">
                    <img class="img-responsive why-upayliving-img w-100" src="{{ imageexist($homecms[0]['image']) }}"
                        alt="">
                </div>
                <div class="col-md-7 col-12 mt-xl-5 mt-3">
                    <h1 class="upayliving-heading">
                        {{ $homecms[0]['cms_title'] }}
                    </h1>
                    <p class="upayliving-content">
                        {!! str_replace('{site_image_url}', IMAGES, $homecms[0]['cms_contant']) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- ######################## Why Upayliving ############################# --}}



    {{-- ######################## Join Upayliving ############################# --}}
    @if (!empty($homecms[1]))
    <section id="Why-Upayliving" class="bg-join-upayliving">
        <div class="container py-3">
            <div class="row">
                <div class="col-md-7 col-12 mt-xl-5 mt-3 order-md-1 order-2">
                    <h1 class="join-upayliving-heading">
                        {{ $homecms[1]['cms_title'] }}
                    </h1>
                    <p class="join-upayliving-content">
                        {!! str_replace('{site_image_url}', IMAGES, $homecms[1]['cms_contant']) !!}
                    </p>
                    <a href="{{ $homecms[1]['url'] }}" class="btn btn-lg btn-custom-warning btn-upayliving">Join Now</a>
                </div>
                <div class="col-md-5 col-12 order-md-2 order-1">
                    <img class="img-responsive why-upayliving-img w-100"
                        src="{{ imageexist($homecms[1]['image']) }}" alt="">
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- ######################## Join Upayliving ############################# --}}



    {{-- ######################## Offer Banner start ######################## --}}
    @if (!empty($offers))
        @foreach ($offers as $key => $row)
            @if ($key == 0)
                <section class="my-3">
                    <div>
                        <div class="offer_banner">
                            <img src="{{ imageexist($row['image']) }}"  class="img-fluid offer_banner-img w-100">
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif
    {{-- ######################## Offer Banner end ######################## --}}



    {{-- ######################## Cards Start ############################# --}}
    @if (!empty($homecms[2]) && !empty($homecms[3]))
    <section id="Card-Upayliving" class="bg-white">
        <div class="container py-lg-4 py-3">
            <div class="row g-lg-3 g-3">
                <div class="col-md-6 col-12 upayliving-item h-100">
                    <img src="{{ imageexist($homecms[2]['image']) }}" class="img-fluid card-img-top d-block w-100" alt="Upay Living">
                    <div class="upayliving-caption margin-padding text-center">
                        <div class="overlay"></div>
                        <!-- Add your text here -->
                        <h1 class="upaycard-title">{{ $homecms[2]['cms_title'] }}</h1>
                        <p class="upaycard-content">
                            {{ mb_Strimwidth($homecms[2]['cms_contant'], 0, 350, '...') }}
                        </p>
                        <div class="position-absolute top-100 start-50 translate-middle">
                            <a href="{{ $homecms[2]['url'] }}" class="btn btn-lg btn-warning text-white btn-upaycard">Learn More</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12 upayliving-item h-100">
                    <img src="{{ imageexist($homecms[3]['image']) }}" class="img-fluid card-img-top d-block w-100" alt="Upay Living">
                    <div class="upayliving-caption margin-padding text-center">
                        <div class="overlay"></div>
                        <!-- Add your text here -->
                        <h1 class="upaycard-title">{{ $homecms[3]['cms_title'] }}</h1>
                        <p class="upaycard-content">
                            {{ mb_Strimwidth($homecms[3]['cms_contant'], 0, 350, '...') }}
                        </p>
                        <div class="position-absolute top-100 start-50 translate-middle">
                            <a href="{{ $homecms[3]['url'] }}" class="btn btn-lg btn-warning text-white btn-upaycard">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- ######################## Cards End ############################# --}}



     {{-- ######################## Offer Banner start ######################## --}}
     @if (!empty($offers))
     @foreach ($offers as $key => $row)
         @if ($key >= 1)
             <section class="my-3">
                     <div class="offer_banner" >
                         <img src="{{ imageexist($row['image']) }}" class="img-fluid img-responsive w-100">
                     </div>
             </section>
         @endif
     @endforeach
     @endif
     {{-- ######################## Offer Banner end ######################## --}}



    {{-- ######################## Testimonial Start ############################# --}}
    @if (!empty($testimonials) && count($testimonials) > 0)
    <section id="testimonial_area" class="py-lg-2 py-0 bg-testimonial">
        <div class="container ">
            <div class="row pt-xl-5 pt-3">
                <div class="col-10 mx-auto text-center justify-content-center ">
                    <h1 class="testimonial-heading">
                        What People Say!
                    </h1>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="testimonial_slider_area text-start owl-carousel">
                        @foreach ($testimonials as $testimonial)
                        <div class="h-100 mt-5 mb-4 testimonial-card box-area row mx-md-1 mx-3">
                            <div class="img-area col-xl-3 col-lg-4 col-md-4 col-4 d-flex">
                                <div class="front-image">
                                <img src="{{ imageexist($testimonial['image']) }}" alt=""
                                    class="testimonial-img bg-transparent">
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8 col-8">
                                <h5 class="testimonial-title">{{ $testimonial['name'] }}</h5>
                                <p class="testimonial-content">
                                    {{ $testimonial['designation'] }}
                                </p>
                            </div>
                            <div class="col-12 mt-3 d-flex">
                                <span>
                                    <img src="{{ asset('public/images/quote.png') }}" alt=""
                                        class="testimonial-quote-img bg-transparent pe-lg-3 pe-2">
                                </span>
                                <span>
                                    <p class="testimonial-des py-0 py-xl-1">
                                        {{ $testimonial['message'] }}
                                    </p>
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- ######################## Testimonial End ############################# --}}
@endsection



@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(".testimonial_slider_area").owlCarousel({
            autoplay: true,
            slideSpeed: 1000,
            items: 2,
            loop: true,
            nav: true,
            navText: [
                '<i class="fa-solid fa-chevron-left"></i>',
                '<i class="fa-solid fa-chevron-right"></i>'
            ],
            margin: 30,
            dots: false,
            responsive: {
                320: {
                    items: 1
                },
                767: {
                    items: 2
                },
                900: {
                    items: 2
                },
                1000: {
                    items: 2
                }
            }
        });
    </script>
@endsection
