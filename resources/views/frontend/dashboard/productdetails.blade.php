@extends('frontend.layouts.app')

@section('content')
    <main>
        @php
            $routeurl = $slug;
        @endphp

        <section id="about us" class="bg-profile-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 py-lg-4 py-md-3 py-2">
                        <p class="bread-heading float-md-start float-none text-md-start text-center">{{ $title }}
                        <p class="bread-subheading float-md-end float-none text-md-start text-center mt-md-3 mt-0">
                            <a href="{{ url('/') }}" class="text-decoration-none text-white">Home </a> / <a href="{{ url('/products') }}" class="text-decoration-none text-white">Products </a> / {{ $title }}</p>
                        </p>
                    </div>
                </div>
            </div>
        </section>
         
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-md-12 col-12">

                        @if (!$productdetails->isEmpty())
                            @foreach ($productdetails as $key => $productval)
                                @php
                                    $user = auth()
                                        ->guard('web')
                                        ->user();
                                    $user_id = !empty($user) ? $user->id : '';
                                    $product_id = $productval['id'];

                                @endphp
                                <div class="row px-lg-3 px-md-3 px-1 mt-md-1 mt-3 cart-class">
                                    <input type="hidden" class="product_id" value="{{ $product_id }}">
                                    <input type="hidden" class="user_id" value="{{ $user_id }}">
                                    <div class="col-lg-6 col-12">
                                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="border: 1px solid #ff647d;">
                                            <div class="carousel-inner">
                                                @foreach ($productdetails as $key => $product)
                                                    @foreach ($product->product_image as $imageKey => $image)
                                                        <div
                                                            class="carousel-item{{ $key == 0 && $imageKey == 0 ? ' active' : '' }}">
                                                            <img src="{{ imageexist($image->attachment) }}"
                                                                class="img-fluid d-block w-100 card-img-top" alt="...">
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>

                                            <div class="carousel-indicators">
                                                @foreach ($productdetails as $key => $product)
                                                    @foreach ($product->product_image as $imageKey => $image)
                                                        <img src="{{ imageexist($image->attachment) }}"
                                                            class="card-img-top img-thumbnail thumbnail{{ $key == 0 && $imageKey == 0 ? ' active' : '' }}"
                                                            data-bs-target="#carouselExampleIndicators"
                                                            data-bs-slide-to="{{ $key * count($product->product_image) + $imageKey }}"
                                                            onclick="changeMainImage(this)" alt="...">
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-12 mt-md-0 mt-2">

                                        <h1 class="product-name">{{ $productval->name }}</h1>
                                        <h3 class="product-model">Model : {{ $productval->model }}</h3>
                                        <p class="product-price-name">Price : $<span
                                                class="product-page-price">{{ $productval->price }}</span>
                                        </p>
                                        <p class="product-desc mt-2">{{ $productval->description }}</p>

                                        <span class="btn quant-order-btn">
                                            <span onclick="decrementValue1()" class="bg-order-pm  bg-white"
                                                type="button"><i
                                                    class="fa-solid fa-minus fw-quant-order text-white"></i></span>
                                            <input data-id="{{ $productval->id }}" value="1"
                                                class="bg-white cart_quantity border-0 text-center fw-quant-order-value p-0 m-0"
                                                type="text" name="quantity" maxlength="3" max="999" size="4"
                                                id="number1">
                                            <span onclick="incrementValue1()" class="bg-order-pm  bg-white"
                                                type="button"><i
                                                    class="fa-solid fa-plus fw-quant-order text-white"></i></span>
                                        </span>
                                        <br>
                                        <div class="mt-md-3 mt-2">
                                            <a class="btn btn-md btn-warning text-white btn-upaycard add_cart">
                                                Add to Cart
                                            </a>
                                            <!-- <a href="" class="btn btn-md btn-success text-white btn-upaycard">
                                                        Buy Now
                                                    </a> -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Product Details End============= --}}


        {{-- ====================== Related Product Start ========================= --}}
        <section id="product-related">
            <div class="container my-lg-5 my-3">
                <div class="row py-lg-4 py-3">
                    <h1 class="upayliving-heading text-center justify-content-center">
                        Related Products
                    </h1>
                </div>
                <div class="row" id="dynamicContent">
                    @if (!empty($relatedproductsData) && count($relatedproductsData) > 0)
                        <div class="card-slider">
                            @foreach ($relatedproductsData as $products)
                                <div class="px-2 card-left">
                                    <div class="card h-100">
                                        <img class="card-img-top img-fluid img-responsive"
                                            src="{{ imageexist($products->image) }}" alt="Card image cap">
                                        <div class="card-body p-2">
                                            <h5 class="product-title ">{{ $products->name }}</h5>
                                            <p class="product-price">Price: ${{ $products->price }}</p>
                                            <div class="text-center justify-content-center">
                                                <a href="{{ route('frontend.productdetails', ['slug' => $products->slug]) }}"
                                                    class="btn btn-md btn-warning text-white btn-upaycard">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center justify-content-center py-lg-5 py-3">
                            <h2 class="mx-auto">No Related Product Found!</h2>
                            <h6 class="mx-auto">We couldn't find any Related products at the moment. Explore our wide
                                range of products or refine your search to discover more options.</h6>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        {{-- ======================= Related Product End ========================= --}}

    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="{{ ASSETS }}js/slick.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('.card-slider').slick({
                dots: false,
                arrows: true,
                slidesToShow: 4,
                infinite: false,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 800,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });
        });
        
        function changeMainImage(element) {
            // Remove 'active' class from all thumbnails
            var thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(function(thumbnail) {
                thumbnail.classList.remove('active');
            });

            // Add 'active' class to the clicked thumbnail
            element.classList.add('active');

            // Find the index of the clicked thumbnail
            var index = Array.prototype.indexOf.call(element.parentNode.children, element);

            // Find the corresponding carousel item and make it active
            var carousel = document.getElementById('carouselExampleIndicators');
            var carouselItems = carousel.querySelectorAll('.carousel-item');
            carouselItems.forEach(function(item) {
                item.classList.remove('active');
            });
            carouselItems[index].classList.add('active');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            var colorButtons = document.querySelectorAll('.thumbnail');

            colorButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Remove 'active' class from all color buttons
                    colorButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });

                    // Add 'active' class to the clicked color button
                    button.classList.add('active');

                    var targetColor = button.getAttribute('data-color');
                    var carouselItems = document.querySelectorAll('.carousel-inner .carousel-item');

                    carouselItems.forEach(function(item) {
                        if (item.getAttribute('data-color') === targetColor) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                });
            });
        });
    </script>
@endsection
