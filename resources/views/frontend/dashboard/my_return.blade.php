@extends('frontend.layouts.app')

@section('header_scripts')
    <style type="text/css">
       .card-ship-padd p{
        margin-bottom: 0.3rem;
       }     
    </style>
@endsection
@section('content')
    <main>
        @include('frontend.includes.profile_header')

        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="row row-cols-1 g-3">
                            <div class="col-12 d-flex">
                                <p class="dash-category">My Return</p>
                                <input type="text" name="proname_search" id="proname_search" class="form-control w-25 ml-auto" placeholder="Search By Product Name">
                            </div>
                            <div class="col-12" id="dataContainer">
                                @if ($my_return->isEmpty())
                                    <div class="col-12">
                                        <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">
                                            No Return Found!!
                                        </h4>
                                    </div>
                                @else
                                    @foreach ($my_return as $myreturn)
                                        <div class="card mb-3 card-myreturn ">
                                            <div class="row g-0 padd-small">
                                                <div class="col-md-7 col-6 text-start justify-content-start">
                                                    <div class="card-body card-ship-padd">
                                                        <p class="myreturn-detail">
                                                        <b>Order No.</b>
                                                            {!! $myreturn['order_no'] !!} 
                                                        </p>
                                                        <p class="myreturn-detail"><b>Name :</b>  {!! $myreturn['customer_name'] !!} </p>
                                                        <p class="myreturn-detail"><b>Mobile :</b>  {!! $myreturn['customer_mobile'] !!} </p>
                                                        <p class="myreturn-detail"><b>Product :</b>  {!! $myreturn['product_name'] !!} </p>
                                                        <p class="myreturn-detail"><b>Product Model :</b>  {!! $myreturn['product_model'] !!} </p>
                                                        <p class="myreturn-detail"><b>User Comments :</b>  {!! $myreturn['comment'] !!} </p>
                                                        <p class="myreturn-detail"><b>Admin Comments :</b>  {!! $myreturn['admin_comment']?$myreturn['admin_comment']:' -- ' !!} </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-6 text-end justify-content-end">
                                                    <div class="card-body card-ship-padd">
                                                        <p class="myreturn-detail">
                                                            <b>Date :</b> {{ date('d-F-Y', strtotime($myreturn['created_at'])) }}
                                                        </p>
                                                        <p class="myreturn-date ">
                                                         <b>Status :</b>  {{ RETURNSTATUS[$myreturn['return_status_id']] }}
                                                        </p>
                                                        <p class="myreturn-date ">
                                                            <b>Action :</b>  {{ $myreturn['return_action_id'] ? RETURNACTIONS[$myreturn['return_action_id']] : '' }}
                                                        </p>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- Display custom pagination links --}}
                                    <div class="pagination text-end justify-content-end pt-lg-5 pt-3">
                                        {{-- Previous Page Link --}}
                                        @if ($my_return->onFirstPage())
                                            <span class="blog-page-link"><i class="fa-solid fa-arrow-left"></i></span>
                                        @else
                                            <a class="blog-page-link" href="{{ $my_return->previousPageUrl() }}"
                                                rel="prev"><i class="fa-solid fa-arrow-left"></i></a>
                                        @endif

                                        {{-- Page Number Links --}}
                                        @for ($i = 1; $i <= $my_return->lastPage(); $i++)
                                            <a href="{{ $my_return->url($i) }}"
                                                class="{{ $my_return->currentPage() == $i ? 'active-page' : 'blog-page-link' }}">{{ $i }}</a>
                                        @endfor

                                        @if ($my_return->hasMorePages())
                                            <a class="blog-page-link" href="{{ $my_return->nextPageUrl() }}"
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
            </div>
        </section>
        {{-- ===============Return End============= --}}


    </main>
@endsection

@section('js')
    <script>
        var RETURNSTATUS = @json(RETURNSTATUS);
        
        var RETURNACTIONS = @json(RETURNACTIONS);

        $(document).ready(function () { 
        // Function to fetch and replace data
            function refreshData() {
                var searchValue = $('#proname_search').val();

                $.ajax({
                    url: '{{ url("get_filter_return_data") }}', // Update with your route
                    type: 'GET',
                    data: { proname_search: searchValue },
                    success: function (response) {
                        // Replace existing data with new data
                        $('#dataContainer').html('');
                        $.each(response.data, function (index, item) {

                            var orderDate = new Date(item.created_at);
                                
                            var options = { day: 'numeric', month: 'long', year: 'numeric' };
                            var formattedDate = orderDate.toLocaleDateString('en-US', options).replace(/(\d+)([^\d])(\d+)([^\d])(\d+)/, '$1$2-$3$4-$5');
                            console.log(index);
                            if (item && item.order_no !== undefined) {
                                $('#dataContainer').append('<div class="card mb-3 card-myreturn "><div class="row g-0 padd-small"><div class="col-md-7 col-6 text-start justify-content-start"><div class="card-body card-ship-padd"> <p class="myreturn-detail">  Order No. ' + item.order_no + '</p> <p class="myreturn-detail"> ' + item.customer_name + ' </p> <p class="myreturn-detail"> ' + item.customer_mobile + ' </p><p class="myreturn-detail">Product :  ' + item.product_name + ' </p><p class="myreturn-detail">Product Model :  ' + item.product_model + ' </p><p class="myreturn-detail">Comments :  ' + item.comment + ' </p> </div> </div> <div class="col-md-5 col-6 text-end justify-content-end"> <div class="card-body card-ship-padd"> <p class="myreturn-detail"> Return Date : ' + formattedDate + ' </p> <p class="myreturn-date "> Return Status : ' + (RETURNSTATUS[item.return_status_id] ?? "") +'</p> <p class="myreturn-date "> Return Action :' + (RETURNACTIONS[item.return_action_id] ?? "") +' </p>   </div> </div> </div> </div>');
                            }else{
                                $('#dataContainer').html('<p class="text-center">Data not found !!</p>');
                            }
                            // Add other data fields as needed
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Bind refreshData function to input change
            $('#proname_search').on('input', function () {
                refreshData();
            });

            // Initial data load
            // refreshData();
        });
    </script>
@endsection