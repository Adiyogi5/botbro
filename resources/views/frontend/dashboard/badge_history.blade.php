@extends('frontend.layouts.app')

@section('content')
<main>
    {{-- ===============Breadcrumb Start============= --}}
    @include('frontend.includes.profile_header')
    {{-- ===============Breadcrumb End============= --}}


    {{-- ===============Badge History Start============= --}}
    <section id="dashboard">
        <div class="container my-lg-5 my-md-4 my-3">
            <div class="row pt-lg-4 pt-3">
                <div class="col-lg-3 col-md-4 col-12">

                    @include('frontend.includes.sidebar_inner')

                </div>
                <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                    <div class="row row-cols-1 g-3">
                        <div class="col-12">
                            <p class="dash-category">Badge History</p>
                        </div>
                        
                        @if ($my_badge->isEmpty())
                                <div class="col-12">
                                    <h4 class="fw-500 mt-lg-5 mt-3 text-center justify-content-center">No Badge Found.
                                    </h4>
                                </div>
                            @else
                                @foreach ($my_badge as $badge)
                                    <div class="col-12">
                                        <div class="card-wallet-brd bg-white px-lg-3 px-md-3 px-3 py-lg-3 py-md-2 py-2">
                                            <div class="row border-bottom mb-2">
                                                <div class="col-md-12 col-12 d-flex">
                                                    <h1 class="modal-ship-title"><i
                                                        class="fa-solid fa-award faa-modal"></i> {{$badge['name']}}
                                                    </h1>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="modal-ship-content">
                                                        {!! $badge['particulars'] !!}
                                                    </p>
                                                </div>
                                                <div class="col-12 d-flex justify-content-between">
                                                    <p class="modal-ship-content">
                                                        <span class="text-dark">Date : </span> {{ date('d-m-Y', strtotime($badge['date'])) }}
                                                    </p>
                                                    <p class="modal-ship-content">
                                                        <span class="text-dark">Purchase Required : </span> {!! $badge['purchase_count'] !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ===============Badge History End============= --}}


</main>
@endsection

@section('js')
<script src="{{ASSETS}}js/sweetalert2.min.js"></script>
<script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
@endsection