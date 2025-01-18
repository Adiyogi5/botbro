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

                        <div class="col-12 d-flex justify-content-between align-item-self">
                            <p class="dash-category">Investment Details</p>
                            <p class="ms-auto"><a href="{{ route('frontend.investment') }}"
                                    class="btn btn-md btn-warning">Back</a></p>
                        </div>

                        <hr>

                        <div class="col-12 card p-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <div
                                    class="bg-primary d-flex align-item-center text-white text-center justify-content-center rounded-1 h-100 w-100 p-2">
                                    <div class="my-auto">
                                        <h5 class="mb-0"><strong>Investment No.</strong> {{ $investment_data->invest_no }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-5 p-4">
                                <div class="col border-end">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Name : </p>
                                        <p>{{ $investment_data->customer_name }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Email : </p>
                                        <p>{{ $investment_data->customer_email }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Mobile : </p>
                                        <p>{{ $investment_data->customer_mobile }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Approval Status:</p>
                                        <span class="px-1 rounded-1 {{ is_null($investment_data->is_approved) ? 'bg-warning' : ($investment_data->is_approved == 1 ? 'bg-success' : 'bg-danger') }}">
                                            {{ is_null($investment_data->is_approved) ? 'PENDING' : ($investment_data->is_approved == 1 ? 'APPROVED' : 'REJECTED') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Investment Date : </p>
                                        <p>{{ \Carbon\Carbon::parse($investment_data->date)->format('d M, Y') }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Invest Amount : </p>
                                        <p>{{ $investment_data->invest_amount }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Payment Mode : </p>
                                        <p>{{ $investment_data->payment_type == 1 ? 'ONLINE' : 'OFFLINE' }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Transaction Id : </p>
                                        <p>{{ $investment_data->transaction_id }}</p>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold mb-0">Payment Status:</p>
                                        <span
                                            class="px-1 rounded-1 {{ $investment_data->payment_status == 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $investment_data->payment_status == 1 ? 'PAID' : 'PENDING' }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-bold">Payment Screenshot : </p>
                                        <p><a href="{{ imageexist($investment_data->screenshot) }}" target="_blank"><img
                                                    class="img-fluid border"
                                                    src="{{ imageexist($investment_data->screenshot) }}" alt=""
                                                    style="height: auto; width:160px"></a></p>
                                    </div>
                                </div>
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
    <script type="text/javascript"></script>
    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif
    </script>
@endsection
