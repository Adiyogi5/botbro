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
                            <p class="dash-category">Make Investment</p>
                        </div>
                        <div class="col-12 card">
                            <form class="row gx-2 p-2" id="investmentForm" method="post"
                                action="{{ route('frontend.investmoney') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col">
                                    <label for="invest_amount" class="form-label">Invest Amount</label>
                                    <input type="number" class="form-control" id="invest_amount" name="invest_amount"
                                        value="{{ old('invest_amount', $site_settings['invest_amount']) }}"
                                        min="{{ $site_settings['invest_amount'] }}">
                                    <label class="error"
                                        id="investAmountError">{{ $errors->first('invest_amount') }}</label>
                                </div>

                                <div class="col">
                                    <label for="payment_type" class="form-label">Payment Type</label>
                                    <select class="form-control form-select" id="payment_type" name="payment_type">
                                        <option value="0" {{ old('payment_type', '1') == '0' ? 'selected' : '' }}>Cash
                                            On Delivery</option>
                                        <option value="1" {{ old('payment_type', '1') == '1' ? 'selected' : '' }}>
                                            Online Payment</option>
                                    </select>
                                    <label class="error" id="paymentTypeError">{{ $errors->first('payment_type') }}</label>
                                </div>

                                <div class="col" id="transactionDiv" style="display: none;">
                                    <label for="transaction_id" class="form-label">Transaction ID</label>
                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                                        value="{{ old('transaction_id') }}">
                                    <label class="error"
                                        id="transactionIdError">{{ $errors->first('transaction_id') }}</label>
                                </div>

                                <div class="col">
                                    <label for="screenshot" class="form-label">Screenshot</label>
                                    <input type="file" class="form-control" id="screenshot" name="screenshot"
                                        accept="image/png, image/jpeg, image/jpg">
                                    <label class="error" id="screenshotError">{{ $errors->first('screenshot') }}</label>
                                </div>

                                <div class="col">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date"
                                        value="{{ now()->toDateString() }}" readonly>
                                    <label class="error" id="dateError">{{ $errors->first('date') }}</label>
                                </div>

                                <div class="col-1 mt-4 pt-2 text-center">
                                    <button type="submit" class="btn btn-md btn-success">Invest</button>
                                </div>
                            </form>
                        </div>

                        <hr>
                        
                        <div class="col-12 d-flex">
                            <p class="dash-category">My Investments</p>
                            <input type="text" name="investno_search" id="investno_search"
                                class="form-control w-25 ml-auto" placeholder="Search By Investment No">
                        </div>
                        <div class="class-12" id="investment-card">
                            @forelse ($my_order as $order)
                                <div class="card mb-3 shadow-sm">
                                    <div class="row g-0">
                                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                                            <div
                                                class="bg-primary d-flex align-item-center text-white text-center justify-content-center rounded-1 h-100 w-100 p-3">
                                                <div class="my-auto">
                                                    <strong>Investment No.</strong><br>{{ $order->invest_no }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">#{{ $order->invest_no }}</h5>
                                                <p class="card-text mb-0"><strong>Date:</strong>
                                                    {{ \Carbon\Carbon::parse($order->date)->format('d M, Y') }}</p>
                                                <p class="card-text mb-1"><strong>Payment Type:</strong>
                                                    {{ $order->payment_type == 1 ? 'ONLINE' : 'OFFLINE' }}</p>
                                                <p class="card-text mb-1"><strong>Payment Status:</strong>
                                                    @if ($order->payment_status == 1)
                                                        <span class="p-1 rounded-1 bg-success">PAID</span>
                                                    @else
                                                        <span class="p-1 rounded-1 bg-warning">PENDING</span>
                                                    @endif
                                                </p>
                                                <p class="card-text mb-0"><strong>Approval Status:</strong>
                                                    @if (is_null($order->is_approved))
                                                        <span class="p-1 rounded-1 bg-warning">PENDING</span>
                                                    @elseif ($order->is_approved == 1)
                                                        <span class="p-1 rounded-1 bg-success">APPROVED</span>
                                                    @else
                                                        <span class="p-1 rounded-1 bg-danger">REJECTED</span>
                                                    @endif                                                
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                                            <a href="{{ route('frontend.investmentdetails', $order->id) }}"
                                                class="btn btn-md btn-warning">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info text-center">
                                    No investments found.
                                </div>
                            @endforelse

                            {{-- Pagination --}}
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

                                {{-- Next Page Link --}}
                                @if ($my_order->hasMorePages())
                                    <a class="blog-page-link" href="{{ $my_order->nextPageUrl() }}" rel="next"><i
                                            class="fa-solid fa-arrow-right"></i></a>
                                @else
                                    <span class="blog-page-link"><i class="fa-solid fa-arrow-right"></i></span>
                                @endif
                            </div>
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
        $(document).ready(function() {
            // Handle Payment Type Selection
            $("#payment_type").on("change", function() {
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
                        required: function() {
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
                submitHandler: function(form) {
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
                var searchValue = $('#investno_search').val();

                if (!searchValue) {
                    return;
                }

                $.ajax({
                    url: '{{ url('get_filter_data') }}', // Update with your route
                    type: 'GET',
                    data: {
                        investno_search: searchValue
                    },
                    success: function(response) {
                        // Clear existing data
                        $('#investment-card').html('');

                        if (response.data.length > 0) {
                            // Loop through each investment item
                            $.each(response.data, function(index, item) {
                                var orderDate = new Date(item.date);
                                var options = {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                };
                                var formattedDate = orderDate.toLocaleDateString('en-US', options);

                                $('#investment-card').append(
                                    `<div class="card mb-3 shadow-sm">
                                <div class="row g-0">
                                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                                        <div class="bg-primary d-flex align-item-center text-white text-center justify-content-center rounded-1 h-100 w-100 p-3">
                                            <div class="my-auto">
                                                <strong>Investment No.</strong><br>${item.invest_no}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title">#${item.invest_no}</h5>
                                            <p class="card-text mb-0"><strong>Date:</strong> ${formattedDate}</p>
                                            <p class="card-text mb-0"><strong>Payment Type:</strong> ${item.payment_type == 1 ? 'Online' : 'Offline'}</p>
                                            <p class="card-text mb-0"><strong>Payment Status:</strong> 
                                                ${item.payment_status === '1' 
                                                    ? '<span class="badge bg-success">Paid</span>' 
                                                    : '<span class="badge bg-danger">Pending</span>'}
                                            </p>
                                            <p class="card-text mb-0"><strong>Approval Status:</strong> 
                                                ${item.is_approved === '1'
                                                    ? '<span class="badge bg-success">Approved</span>' 
                                                    : '<span class="badge bg-danger">Not Approved</span>'}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                                        <a href="{{ url('investment-details') }}/${item.id}" class="btn btn-md btn-warning">View Details</a>
                                    </div>
                                </div>
                            </div>`
                                );
                            });
                        } else {
                            $('#investment-card').html(
                                '<p class="text-center">No investments found!</p>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching data:', error);
                        $('#investment-card').html(
                            '<p class="text-center text-danger">Error loading data.</p>');
                    }
                });
            }

            // Bind refreshData function to input change
            $('#investno_search').on('input', function() {
                refreshData();
            });
        });
    </script>
@endsection
