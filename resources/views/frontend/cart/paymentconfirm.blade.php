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
                <li class="breadcrumb-item bread-active" aria-current="page">Payment <?=$mode?></li>
            </ol>
        </nav>
    </div>
    <main>
        <section>
            <div class="row">
                <div class="col-12 text-center justify-content-center">
                   
                    <div class="py-lg-3 py-md-2 py-2">
                        <h1 class="order-confirm-title2">Congratulations</h1>
                        <p>
                            <p class="order-confirm-title1"> We Recived your order, your order no <span class="fw-bolder text-dark">#{{ $order_no }}</span> </p> 
                            <br>
                            <p class="order-confirm-title3">Please wait, we will process your order with in 24 Hours.</p>
                            <p class="order-confirm-title3"> For more information please contact with Administrator!!</p>
                        </p>
                        
                    </div>
                </div>
            </div>
        </section>         
@endsection 
