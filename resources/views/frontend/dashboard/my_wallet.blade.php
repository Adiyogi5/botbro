@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
       
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="col-12">
                                <p class="dash-category">My Wallet</p>
                            </div>

                        <div class="row row-cols-1 g-0">
                            @if ($my_balance->isEmpty())
                                <div class="col-12">
                                    <span></span>
                                </div>
                            @else
                                @isset($my_balance[0])
                                    <div class="col-12">
                                        <div class="card mb-3 bg-dash-in overflow-hidden">
                                            <div class="row g-0 bg-dash-out py-2">
                                                <div class="col-lg-2 col-md-3 col-4 mx-auto my-auto text-end">
                                                    <span class="wallet-i"><i
                                                            class="fa-solid fa-wallet faa-wallet-i"></i></span>
                                                </div>
                                                <div class="col-lg-10 col-md-9 col-8 my-auto">
                                                    <div class="card-body card-ship-padd py-3">
                                                        <p class="dash-balance"> Balance </p>
                                                        <h5 class="card-title dash-money">₹ {!! $my_balance[0]->user_balance !!}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endisset
                            @endif

                            @if ($my_balance_data->isEmpty())
                                <div class="col-12">
                                    <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">No Transaction Found!!
                                    </h4>
                                </div>
                            @else
                                @foreach ($my_balance_data as $balance_data)
                                    <div class="col-12">
                                        <div class="card card-myorder ">
                                            <div class="row g-0 padd-small">
                                                <div class="col-lg-10 col-md-9 col-9 text-start justify-content-start">
                                                    <div class="card-body card-ship-padd">
                                                        <p class="orderbal-name">
                                                            {!! $balance_data['name'] !!}
                                                        </p>
                                                        <p class="orderbal-date">
                                                            {{ date('d-m-Y', strtotime($balance_data->date)) }}
                                                        </p>
                                                        <p class="orderbal-detail">
                                                            {!! $balance_data['particulars'] !!}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-lg-2 col-md-3 col-3 mx-auto my-auto text-center justify-content-center">
                                                    <div class="card-body card-ship-padd text-center">
                                                        @php
                                                            $amount = $balance_data['amount'];
                                                            $payment_type = $balance_data['payment_type'];
                                                            $formatted_amount = $payment_type == 1 ? '+' . number_format($amount, 2) : '-' . number_format($amount, 2);
                                                            $amount_class = $payment_type == 1 ? 'text-success' : 'text-danger';
                                                        @endphp
                                                        <p class="orderbal-bal {{ $amount_class }}">
                                                            {{ $formatted_amount }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- Display custom pagination links --}}
                                <div class="pagination text-end justify-content-end pt-lg-5 pt-3">
                                    {{-- Previous Page Link --}}
                                    @if ($my_balance_data->onFirstPage())
                                        <span class="blog-page-link"><i class="fa-solid fa-arrow-left"></i></span>
                                    @else
                                        <a class="blog-page-link" href="{{ $my_balance_data->previousPageUrl() }}"
                                            rel="prev"><i class="fa-solid fa-arrow-left"></i></a>
                                    @endif

                                    {{-- Page Number Links --}}
                                    @for ($i = 1; $i <= $my_balance_data->lastPage(); $i++)
                                        <a href="{{ $my_balance_data->url($i) }}"
                                            class="{{ $my_balance_data->currentPage() == $i ? 'active-page' : 'blog-page-link' }}">{{ $i }}</a>
                                    @endfor

                                    @if ($my_balance_data->hasMorePages())
                                        <a class="blog-page-link" href="{{ $my_balance_data->nextPageUrl() }}"
                                            rel="next"><i class="fa-solid fa-arrow-right"></i></a>
                                    @else
                                        <span class="blog-page-link"><i class="fa-solid fa-arrow-right"></i></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}


    </main>
@endsection
