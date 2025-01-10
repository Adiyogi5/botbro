@extends('frontend.layouts.app')
@section('css')
    <style>
        .bg-danger{
            background-color: #dc3545 !important;
        }
    </style>
@endsection
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
                        <div class="row row-cols-1 g-3">
                            {{-- ############ Shipping & Payment info ########### --}}
                            <div class="col-12">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header bg-white" id="headingOne">
                                            <button class="accordion-button btn-orderview bg-white collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <i class="fa-solid fa-truck faa-order-view"></i> Shipping & Payment info
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body overflow-hidden bg-white">

                                                <div class="row border-bottom mb-2">
                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Order No</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->order_no }}
                                                        </p>
                                                    </span>
                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Order Date</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ date('d-F-Y', strtotime($order_data->date)) }}</p>
                                                    </span>
                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Status</p>
                                                        <p aria-disabled="true" disabled
                                                            class="btn btn-sm text-end fw-bold {{ $order_data->order_status_bg }}" style="margin: 0;">
                                                            {!! $order_data->order_status_name !!}</p>
                                                    </span>
                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Payment Status</p>
                                                        <p aria-disabled="true" disabled
                                                            class="btn btn-sm text-end fw-bold" style="margin: 0;">
                                                             @if($order_data->payment_status==0)
                                                                Payment Status: Unpaid
                                                            @else
                                                                Payment Status: Paid
                                                            @endif</p>
                                                    </span>


                                                    <hr>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Payment Status</p>
                                                        <p class="text-end order-view-text-right">
                                                            {!! $order_data->payment_status == 0
                                                                ? '<button class="btn btn-sm btn-danger">Pending</button>'
                                                                : '<button class="btn btn-sm btn-success">Paid</button>' !!}</p>
                                                    </span>

                                                    @if ($order_data->razorpay_payment_Id)
                                                        <span class="d-flex justify-content-between">
                                                            <p class="text-start order-view-text-left">Payment ID</p>
                                                            <p aria-disabled="true" disabled
                                                                class="btn btn-sm text-end order-view-text-right ">
                                                                {!! $order_data->razorpay_payment_Id !!}</p>
                                                        </span>
                                                    @endif

                                                    @if ($order_data->transaction_payment_id)
                                                        <span class="d-flex justify-content-between">
                                                            <p class="text-start order-view-text-left">Transaction ID</p>
                                                            <p aria-disabled="true" disabled
                                                                class="btn btn-sm text-end order-view-text-right ">
                                                                {!! $order_data->transaction_payment_id !!}</p>
                                                        </span>
                                                    @endif

                                                    <hr>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Name</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->customer_name }}</p>
                                                    </span>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Email</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->customer_email }}</p>
                                                    </span>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Mobile</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->customer_mobile }}</p>
                                                    </span>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Shipping address 1</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->shipping_address_1 }}</p>
                                                    </span>

                                                    <span class="d-flex justify-content-between">
                                                        <p class="text-start order-view-text-left">Shipping address 2</p>
                                                        <p class="text-end order-view-text-right">
                                                            {{ $order_data->shipping_address_2 }} <br>
                                                            {{ $order_data->shipping_postcode }},<br>
                                                            {{ $order_data->shipping_city }}, {{ $order_data->shipping_state }}, {{ $order_data->shipping_country }}.
                                                        </p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ############ Order Products info ########### --}}
                            <div class="col-12">
                                <div class="accordion" id="accordionInfo">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header bg-white" id="headingThree">
                                            <button class="accordion-button btn-orderview bg-white" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="true" aria-controls="collapseThree">
                                                <i class="fa-solid fa-cart-shopping faa-order-view"></i> Order Products
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse show"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionInfo">
                                            <div class="accordion-body overflow-scroll">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th>S.no</th>
                                                        <th>Image</th>
                                                        <th>Product</th>
                                                        <th>Price</th>
                                                        <th>Sub Total</th>
                                                        <th colspan="2">Total</th> 
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($order_products))
                                                            @foreach ($order_products as $key => $order_product)
                                                                <tr>
                                                                    <td>{{ $key + 1 }} &nbsp;</td>
                                                                    <td>
                                                                        <img width="100" height="100"
                                                                            src="{{ get_image($order_product['product_image'], 'product_grid', 1) }}"
                                                                            alt="Product image">
                                                                    </td>
                                                                    <td>
                                                                        {{ $order_product['product_name'] }} X
                                                                        {{ $order_product['quantity'] }} <br>
                                                                    @if($order_product['is_product_replace'] == 0 && $order_product['is_product_return'] == 0)
                                                                        @if(!empty($forOrderReturn) && $forOrderReturn->order_status_id === 5 && date('Y-m-d') <= date('Y-m-d', strtotime($order_data->order_return_expiredate)))
                                                                        <button title="Return Product" class="btn btn-sm btn-danger" onclick="return_prod('{{ $order_product['product_id'] }}','{{$order_product['order_id']}}','1')"><i class="fa fa-retweet"></i> Return </button>

                                                                        <button title="Return Product" class="btn btn-sm btn-info" onclick="return_prod('{{ $order_product['product_id'] }}','{{$order_product['order_id']}}','2')"><i class="fa fa-exchange"></i> Replace </button>
                                                                        @endif
                                                                    @else
                                                                        @if($order_product['is_product_replace'] == 1)
                                                                            <span class="badge bg-info">Replace</span>
                                                                        @elseif($order_product['is_product_return'] == 1)
                                                                            <span class="badge bg-danger">Return</span>
                                                                        @endif    
                                                                    @endif
                                                                    </td>

                                                                    <td>
                                                                        {{ CURRENCY_SYMBOL }}{{ $order_product['unit_price'] }}
                                                                    </td>

                                                                    <td>
                                                                        {{ CURRENCY_SYMBOL }}{{ $order_product['unit_price'] * $order_product['quantity'] }}
                                                                    </td>

                                                                    <td colspan="2">
                                                                        {{ CURRENCY_SYMBOL }}{{ $order_product['total_price'] }}
                                                                    </td> 

                                                                </tr>
                                                            @endforeach
                                                        @endif

                                                    <tfoot>

                                                        <tr>
                                                            <th colspan="6" class="text-end">Sub Total</th>
                                                            <th class="text-end">
                                                                {{ CURRENCY_SYMBOL }}{{ number_format((float) $order_data['subtotal'], 2) }}
                                                            </th>
                                                        </tr>

                                                        @if ($order_data['discount'])
                                                            <tr>
                                                                <th colspan="6" class="text-end">Discount</th>
                                                                <th class="text-end">
                                                                    {{ CURRENCY_SYMBOL }}{{ number_format((float) $order_data['discount'], 2) }}
                                                                </th>
                                                            </tr>
                                                        @endif

                                                        @if ($order_data['shipping_amount'])
                                                            <tr>
                                                                <th colspan="6" class="text-end">Shipping Charge</th>
                                                                <th class="text-end">
                                                                    {{ CURRENCY_SYMBOL }}{{ number_format((float) $order_data['shipping_amount'], 2) }}
                                                                </th>
                                                            </tr>
                                                        @endif

                                                        @if ($order_data['round_off'])
                                                        <tr>
                                                            <th colspan="6" class="text-end">Round Off</th>
                                                            <th class="text-end">{{ CURRENCY_SYMBOL }}
                                                                {{ number_format((float) $order_data['round_off'], 2) }}
                                                            </th>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th colspan="6" class="text-end">Total</th>
                                                            <th class="text-end">
                                                                {{ CURRENCY_SYMBOL }}{{ number_format((float) $order_data['total'], 2) }}
                                                            </th>
                                                        </tr>

                                                    </tfoot>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- ############ Order History info ########### --}}
                            <div class="col-12">
                                <div class="accordion" id="accordionExample2">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header bg-white" id="headingOne">
                                            <button class="accordion-button btn-orderview bg-white collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapsefour"
                                                aria-expanded="false" aria-controls="collapsefour">
                                                <i class="fas fa-history faa-order-view"></i> Order History
                                            </button>
                                        </h2>
                                        <div id="collapsefour" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample2">
                                            <div class="accordion-body">

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Status</th>
                                                            <th>Comment</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($orderHistory as $index => $history)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <span class="fw-bold {{ $history['status_color'] }}">
                                                                        {{ $history['status_name'] }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $history['comment'] }}</td>
                                                                <td>
                                                                    {{ date('M d, Y h:i A', strtotime($history['created_at'])) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center">No order history
                                                                    available.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
        {{-- ===============Order Detail End============= --}}

    </main>
@endsection


@section('js')
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        function return_prod(product_id,order_id,type){
            if(type==1){
                var cmsg = 'Return';
            }else{
                var cmsg = 'Replace';
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, '+cmsg+' it!'
            }).then((result) => {
                if (result.isConfirmed) { 
                $.ajax({
                    method: "post",
                    url: "{{url('return-product')}}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "product_id": product_id,
                        "order_id": order_id,
                        "type":type,
                    },
                    success: (result) => {
                        let res = JSON.parse(result)
                        if (res.status == true) {
                            Swal.fire(''+cmsg+'!', res.message, 'success')
                            .then((result) => {
                            // Check if the user clicked "OK"
                            if (result.isConfirmed) {
                                // User clicked "OK", reload the page
                                location.reload();
                            }
                            });
                        } else {
                        Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: (error) => {
                        Swal.fire('Error!','Failed to return request','error')
                    }
                })
                }
            })
        }
    </script>
@endsection
