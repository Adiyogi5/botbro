@extends('frontend.layouts.app')


@section('header_scripts')

<style>

.modal
{
    background-color: #000000cc !important;
}


</style>

@endsection

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item bread-head"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item bread-active" aria-current="page">Payment Success</li>
            </ol>
        </nav>
    </div>

    <main>
        <section>
            <div class="container mt-md-0 mt-4 mb-3"> 
                <hr>
                <hr>
                <hr>
                <hr>
                <hr>
                <hr>
            </div>
        </section>


       
        {{-- ############# Modal For Order Placed ##############  --}}
        <div class="modal fade" id="orderplacedModal" tabindex="-1" aria-labelledby="addaddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header z-index-1000 position-absolute end-0 border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center justify-content-center">
                                <div>
                                    <img src="{{ asset('assets/img/order-confirm-img.png') }}" alt=""
                                        class="img-fluid order-confirm-img" />
                                </div>
                                <div class="py-lg-3 py-md-2 py-2">
                                    <div>
                                        <p class="order-confirm-title1">your order <span class="fw-bolder text-dark">#{{ $order_no }}</span> is</p>
                                        <p class="order-confirm-title2">Placed</p>
                                        <p class="order-confirm-title3">Please wait till we confirm your order</p>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


@section('footer_scripts')
   
    <script type="text/javascript">
        
        $(document).ready(function() {

            $("#orderplacedModal").modal('show');

        });

        $("#orderplacedModal").on('hide.bs.modal',function(){

            location.href = "{{ route('dashboard') }}";

        })
        

    </script>
@endsection
