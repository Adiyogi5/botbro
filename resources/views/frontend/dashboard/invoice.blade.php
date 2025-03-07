@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Dashboard Start============= --}}
        <section id="dashboard">
            <div class="container my-5">

                <img src="{{ asset('assets/img/dashboard/vector_left.png') }}" alt=""
                    class="img-fluid position-absolute z-index-n1 top-50 start-0 d-xl-block d-lg-block d-md-block d-none" />
                <img src="{{ asset('assets/img/dashboard/vector_right.png') }}" alt=""
                    class="img-fluid position-absolute z-index-n1 top-50 end-0 d-xl-block d-lg-block d-md-block d-none" />

                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-12 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-12 col-12">
                        @if ($my_order_detail)
                            <div class="row row-cols-1 g-3 ms-md-3 ms-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="accordion-header bg-white lh-base" id="headingOne">
                                        Order Invoice
                                    </h3>
                                    <a href="{{ route('generateAndDownloadPDF', ['id' => $my_order_detail->id]) }}">
                                        <button class="bg-transparent border-0" type="button">
                                            <i class="fa-solid fa-download faa-order-view text-danger"></i>
                                        </button>
                                    </a>
                                </div>

                                <div class="col-12 card-myorder py-3 px-3 bg-white">
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Order date :</p>
                                        <p class="text-end order-view-text-right">
                                            {{ \Carbon\Carbon::parse($my_order_detail->date)->format('d-m-Y') }}
                                        </p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Order No. :</p>
                                        <p class="text-end order-view-text-right">
                                            {!! $my_order_detail->order_no !!}</p>
                                    </span>
                                    <hr>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Name :</p>
                                        <p class="text-end order-view-text-right">
                                            {!! $my_order_detail->customer_name !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Email ID :</p>
                                        <p class="text-end order-view-text-right">
                                            {!! $my_order_detail->customer_email !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Mobile No. :</p>
                                        <p class="text-end order-view-text-right">
                                            {!! $my_order_detail->customer_mobile !!}</p>
                                    </span>
                                    <hr>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Sub Total :</p>
                                        <p class="text-end order-view-text-right">$
                                            {!! $my_order_detail->subtotal !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Discount :</p>
                                        <p class="text-end order-view-text-right">$
                                            {!! $my_order_detail->discount !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Tax Amount :</p>
                                        <p class="text-end order-view-text-right">$
                                            {!! $my_order_detail->tax_amount !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Shipping Amount :</p>
                                        <p class="text-end order-view-text-right">$
                                            {!! $my_order_detail->shipping_amount !!}</p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Total :</p>
                                        <p class="text-end order-view-text-right">$
                                            {!! $my_order_detail->total !!}</p>
                                    </span>
                                    <hr>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Payment method :</p>
                                        <p class="text-start order-view-text-right">
                                            @if ($my_order_detail->payment_type == 1)
                                                Online
                                            @elseif($my_order_detail->payment_type == 2)
                                                Cash On Delivery
                                            @else
                                                Unknown Payment Type
                                            @endif
                                        </p>
                                    </span>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Payment Status :</p>
                                        <p class="text-start order-view-text-right">
                                            @if ($my_order_detail->payment_status == 1)
                                                Unpaid
                                            @elseif($my_order_detail->payment_status == 2)
                                                Paid
                                            @else
                                                Unknown Payment Status
                                            @endif
                                        </p>
                                    </span>
                                    <hr>
                                    <span class="d-flex justify-content-between">
                                        <p class="text-start order-view-title">Shipping address :</p>
                                        <p class="text-start order-view-text-right">
                                            {!! $my_order_detail->shipping_address_1 !!}
                                            {!! $my_order_detail->shipping_address_2 !!}
                                            <br>
                                            {!! $my_order_detail->shipping_city !!}, {!! $my_order_detail->shipping_state !!},
                                            {!! $my_order_detail->shipping_country !!}, {!! $my_order_detail->shipping_postcode !!}
                                        </p>
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        {{-- ===============Dashboard End============= --}}


    </main>
@endsection
