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

                        <div class="col-12 d-flex">
                            <p class="dash-category">My Investment</p>
                            <input type="text" name="orderno_search" id="orderno_search" class="form-control w-25 ml-auto"
                                placeholder="Search By Investment No">
                        </div>
                        <div class="col-12">
                            <form class="row" id="investmentForm" method="post" action="{{ route('frontend.investmoney') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col mb-3">
                                    <label for="invest_amount" class="form-label">Invest Amount</label>
                                    <input type="number" class="form-control" id="invest_amount" name="invest_amount"
                                        value="{{ old('invest_amount', $site_settings['invest_amount']) }}" min="{{ $site_settings['invest_amount'] }}">
                                    <label class="error" id="investAmountError">{{ $errors->first('invest_amount') }}</label>
                                </div>
                            
                                <div class="col mb-3">
                                    <label for="payment_type" class="form-label">Payment Type</label>
                                    <select class="form-control" id="payment_type" name="payment_type">
                                        <option value="0" {{ old('payment_type') == '0' ? 'selected' : '' }}>Cash On Delivery</option>
                                        <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Online Payment</option>
                                    </select>
                                    <label class="error" id="paymentTypeError">{{ $errors->first('payment_type') }}</label>
                                </div>
                            
                                <div class="col mb-3" id="transactionDiv" style="display: none;">
                                    <label for="transaction_id" class="form-label">Transaction ID</label>
                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                                    <label class="error" id="transactionIdError">{{ $errors->first('transaction_id') }}</label>
                                </div>

                                <div class="col mb-3">
                                    <label for="screenshot" class="form-label">Upload Screenshot</label>
                                    <input type="file" class="form-control" id="screenshot" name="screenshot" accept="image/png, image/jpeg, image/jpg">
                                    <label class="error" id="screenshotError">{{ $errors->first('screenshot') }}</label>
                                </div>

                                <div class="col mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ now()->toDateString() }}" readonly>
                                    <label class="error" id="dateError">{{ $errors->first('date') }}</label>
                                </div>
                            
                                <div class="col mb-3 mt-4 pt-2">
                                    <button type="submit" class="btn btn-md btn-success px-3">Invest</button>
                                </div>
                            </form>                            
                        </div>

                        <div class="col-12" id="dataContainer">
                            @if ($my_order->isEmpty())
                                <div class="col-12">
                                    <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">
                                        No Investment Found..!!
                                    </h4>

                                </div>
                            @else
                                @foreach ($my_order as $myorder)
                                    <div class="card mb-3 card-myorder ">
                                        <div class="row g-0 padd-small">
                                            <div class="col-md-7 col-6 text-start justify-content-start">
                                                <div class="card-body card-ship-padd">
                                                    <a class="myorder-id textdecoration-none"
                                                        href="{{ route('frontend.order_view', [$myorder['id']]) }}">Order
                                                        No.
                                                        {!! $myorder['order_no'] !!} <i class="fa-solid fa-circle-info"></i></a>
                                                    <p class="myorder-detail"> {!! $myorder['customer_name'] !!} </p>
                                                    <p class="myorder-detail"> {!! $myorder['customer_mobile'] !!} </p>
                                                    <p class="myorder-detail"> ₹ {!! $myorder['total'] !!} </p>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-6 text-end justify-content-end">
                                                <div class="card-body card-ship-padd">
                                                    <p class="myorder-date ">
                                                        {{ date('d-F-Y', strtotime($myorder['date'])) }}
                                                    </p>
                                                    <p class="fw-bold" style="margin: 0px;">
                                                        @if ($myorder['payment_status'] == 0)
                                                            Payment Status: Unpaid
                                                        @else
                                                            Payment Status: Paid
                                                        @endif
                                                    </p>
                                                    <span class="fw-bold {{ $myorder['status_class'] }}">
                                                        {{ $myorder['status_text'] }}
                                                    </span>

                                                    <div class="d-flex gap-2 mt-3 justify-content-end">
                                                        <a href="{{ route('frontend.order_view', [$myorder['id']]) }}"
                                                            class="btn btn-md btn-warning text-white btn-upaycard">
                                                            View Details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- Display custom pagination links --}}
                                <div class="pagination text-end justify-content-end pt-lg-5 pt-3">
                                    {{-- Previous Page Link --}}
                                    @if ($my_order->onFirstPage())
                                        <span class="blog-page-link"><i class="fa-solid fa-arrow-left"></i></span>
                                    @else
                                        <a class="blog-page-link" href="{{ $my_order->previousPageUrl() }}"
                                            rel="prev"><i class="fa-solid fa-arrow-left"></i></a>
                                    @endif

                                    {{-- Page Number Links --}}
                                    @for ($i = 1; $i <= $my_order->lastPage(); $i++)
                                        <a href="{{ $my_order->url($i) }}"
                                            class="{{ $my_order->currentPage() == $i ? 'active-page' : 'blog-page-link' }}">{{ $i }}</a>
                                    @endfor

                                    @if ($my_order->hasMorePages())
                                        <a class="blog-page-link" href="{{ $my_order->nextPageUrl() }}" rel="next"><i
                                                class="fa-solid fa-arrow-right"></i></a>
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


@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
    // Handle Payment Type Selection
    $("#payment_type").on("change", function () {
        if ($(this).val() === "1") {
            // Online Payment: Show transaction ID field and make it required
            $("#transactionDiv").show();
            $("#transaction_id").prop("required", true);
        } else {
            // Cash on Delivery: Hide transaction ID field and remove required attribute
            $("#transactionDiv").hide();
            $("#transaction_id").prop("required", false).val("");
        }
    }).trigger("change"); // Trigger the change event on page load

    // Form Validation
    $("#investmentForm").validate({
        rules: {
            invest_amount: {
                required: true,
                min: {{ $site_settings['invest_amount'] }},
            },
            payment_type: "required",
            transaction_id: {
                required: function () {
                    return $("#payment_type").val() === "1";
                },
            },
            screenshot: {
                required: true,
                accept: "image/jpeg,image/png,image/jpg",
            },
        },
        messages: {
            invest_amount: {
                required: "Please enter an investment amount",
                min: "The investment amount must be at least {{ $site_settings['invest_amount'] }}",
            },
            payment_type: "Please select a payment type",
            transaction_id: "Transaction ID is required for online payment",
            screenshot: {
                required: "Please upload a screenshot",
                accept: "Only PNG, JPG, and JPEG formats are allowed",
            },
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
});
    </script>
    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif
    </script>
    <script>
        $(document).ready(function() {
            // Function to fetch and replace data
            function refreshData() {
                var searchValue = $('#orderno_search').val();

                $.ajax({
                    url: '{{ url('get_filter_data') }}', // Update with your route
                    type: 'GET',
                    data: {
                        orderno_search: searchValue
                    },
                    success: function(response) {
                        // Replace existing data with new data
                        $('#dataContainer').html('');
                        $.each(response.data, function(index, item) {

                            if (item && item.order_no !== undefined) {
                                var orderDate = new Date(item.date);

                                var options = {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                };
                                var formattedDate = orderDate.toLocaleDateString('en-US',
                                    options).replace(/(\d+)([^\d])(\d+)([^\d])(\d+)/,
                                    '$1$2-$3$4-$5');

                                $('#dataContainer').append(
                                    '<div class="card mb-3 card-myorder "><div class="row g-0 padd-small"><div class="col-md-7 col-6 text-start justify-content-start"><div class="card-body card-ship-padd"><a class="myorder-id textdecoration-none" href="{{ route('frontend.order_view', '') }}/' +
                                    item.id + '">Order No. ' + item.order_no +
                                    ' <i class="fa-solid fa-circle-info"></i></a> <p class="myorder-detail"> ' +
                                    item.customer_name +
                                    ' </p> <p class="myorder-detail"> ' + item
                                    .customer_mobile +
                                    ' </p> <p class="myorder-detail"> ₹ ' + item.total +
                                    ' </p> </div> </div> <div class="col-md-5 col-6 text-end justify-content-end"> <div class="card-body card-ship-padd">  <p class="myorder-date "> ' +
                                    formattedDate + ' </p> <span class="fw-bold ' + item
                                    .status_class + '"> ' + item.status_text +
                                    ' </span> <div class="d-flex gap-2 mt-3 justify-content-end"> <a href=" {{ route('frontend.order_view', '') }}/' +
                                    item.id +
                                    '" class="btn btn-md btn-warning text-white btn-upaycard"> View Details </a> </div> </div> </div> </div> </div>'
                                );
                            } else {
                                $('#dataContainer').html(
                                    '<p class="text-center">Data not found !!</p>');
                            }
                            // Add other data fields as needed
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Bind refreshData function to input change
            $('#orderno_search').on('input', function() {
                refreshData();
            });

            // Initial data load
            // refreshData();
        });
    </script>
@endsection
