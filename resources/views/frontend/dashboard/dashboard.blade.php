@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Dashboard Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="row mb-2">
                            <div class="col-4 ms-auto">
                            
                        </div>
                        </div>
                        
                        @include('frontend.includes.sidebar_inner')
                    
                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col-12 mb-2">
                                <p class="dash-category">Dashboard</p>
                            </div>
                            <div class="col-lg-3 col-md-6 col-6 my-auto">
                                <div class="card h-100 mb-3 card-dash bg-white overflow-hidden">
                                    <img src="{{ asset('public/images/Ellipse.png') }}"
                                        class="position-absolute dash-abs-img translate-middle" alt="">
                                    <div class="row g-0">
                                        <div
                                            class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                            <img src="{{ asset('public/images/scooter.png') }}"
                                                class="img-fluid rounded-start pro-img-order p-1" />
                                        </div>
                                        <div class="col-md-7 col-7">
                                            <div class="card-body card-ship-padd d-grid">
                                                <h5 class="card-title dash-number">{{ $total_order_count }}</h5>
                                                <p class="dash-total"> Total
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-6 my-auto">
                                <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                    <img src="{{ asset('public/images/Ellipse.png') }}"
                                        class="position-absolute dash-abs-img translate-middle" alt="">
                                    <div class="row g-0">
                                        <div
                                            class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                            <img src="{{ asset('public/images/scooter.png') }}"
                                                class="img-fluid rounded-start pro-img-order p-1" />
                                        </div>
                                        <div class="col-md-7 col-7">
                                            <div class="card-body card-ship-padd d-grid">
                                                <h5 class="card-title dash-number">{{ $delivered_order_count }}</h5>
                                                <p class="dash-total"> Delivered
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-6 my-auto">
                                <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                    <img src="{{ asset('public/images/Ellipse.png') }}"
                                        class="position-absolute dash-abs-img translate-middle" alt="">
                                    <div class="row g-0">
                                        <div
                                            class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                            <img src="{{ asset('public/images/scooter.png') }}"
                                                class="img-fluid rounded-start pro-img-order p-1" />
                                        </div>
                                        <div class="col-md-7 col-7">
                                            <div class="card-body card-ship-padd d-grid">
                                                <h5 class="card-title dash-number">{{ $cancel_order_count }}</h5>
                                                <p class="dash-total"> Cancel
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-6 my-auto">
                                <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                    <img src="{{ asset('public/images/Ellipse.png') }}"
                                        class="position-absolute dash-abs-img translate-middle" alt="">
                                    <div class="row g-0">
                                        <div
                                            class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                            <img src="{{ asset('public/images/scooter.png') }}"
                                                class="img-fluid rounded-start pro-img-order p-1" />
                                        </div>
                                        <div class="col-md-7 col-7">
                                            <div class="card-body card-ship-padd d-grid">
                                                <h5 class="card-title dash-number">{{ $pending_order_count }}</h5>
                                                <p class="dash-total"> Pending
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xl-6 col-lg-8 col-md-12 col-12 my-auto">
                                <div class="card h-100 mb-3 card-dash bg-white overflow-hidden">
                                    <img src="{{ asset('public/images/Ellipse.png') }}"
                                        class="position-absolute dash-abs-img translate-middle" alt="">
                                    <div class="card-body by-3 d-grid">
                                        <h5 class="card-title dash-number">{{ Auth::user()->name }} </h5>
                                        <p class="dash-total"><strong>Mobile : </strong> {{ Auth::user()->mobile }} </p>
                                        <p class="dash-total"><strong>Email : </strong> {{ Auth::user()->email }} </p>
                                        <p class="dash-total"><strong>Reffer Code: </strong> {!! Auth::user()->reffer_code !!}</p>
                                               
                                        @if ($my_balance->badge_level)
                                            <p class="dash-total"><strong>Badge: </strong> {!! $my_balance->badge_level !!}</p>
                                        @endif
                                        @if ($refer_by)
                                            <p class="dash-total"><strong>Referred By: </strong> {!! $refer_by->referred_by !!}</p>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row-cols-1 g-3 mt-3">
                            @if (!$my_balance)
                                <div class="col-12">
                                    <span></span>
                                </div>
                            @else
                                @isset($my_balance)
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                        <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                            <div class="card-body bg-dash-out card-ship-padd py-3">
                                                <p class="dash-balance">Wallet Balance </p>
                                                <h5 class="card-title dash-money">₹ {!! $my_balance->user_balance !!}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                        <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                            <div class="card-body bg-dash-out card-ship-padd py-3">
                                                <p class="dash-balance">Withdraw Request</p>
                                                <h5 class="card-title dash-money">₹ {!! $requestAmount->total_amount ? $requestAmount->total_amount: 0 !!}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                        <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                            <div class="card-body bg-dash-out card-ship-padd py-3">
                                                <p class="dash-balance">Rejected Balance </p>
                                                <h5 class="card-title dash-money">₹
                                                    {{ $rejectAmount->total_amount ? $rejectAmount->total_amount : 0 }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                @endisset
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}

    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif
    </script>    
@endsection
