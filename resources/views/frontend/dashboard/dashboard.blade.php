@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Dashboard Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="row mb-2">
                            <div class="col-4 ms-auto">

                            </div>
                        </div>

                        @include('frontend.includes.sidebar_inner')

                    </div>

                    @if ($user_approved->is_approved == 0)
                        <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                            <div class="row row-cols-1 g-3">
                                <div class="col-12 mb-2">
                                    <p class="dash-category">Welcome ROBO Trade</p>
                                </div>
                            </div>
                            <div class="row">
                                <div
                                    class="card h-100 mb-3 card-dash bg-white overflow-hidden px-xl-5 px-lg-4 px-md-3 px-2 py-3">
                                    <h4 class="text-center justify-content-center mb-3" style="text-decoration: underline;">
                                        Payment Details For Membership</h4>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Account Name : </h6>
                                                    <p>{{ $site_settings['account_holder_name'] }}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Account Number : </h6>
                                                    <p>{{ $site_settings['account_no'] }}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Bank Name : </h6>
                                                    <p>{{ $site_settings['bank_name'] }}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>IFSC Code : </h6>
                                                    <p>{{ $site_settings['ifsc_code'] }}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>UPI ID : </h6>
                                                    <p>{{ $site_settings['upi_id'] }}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Membership Fee : </h6>
                                                    <p>{{ $site_settings['membership_fee'] }}</p>
                                                </span>
                                            </div>
                                            <div class="col-md-6 col-12 text-center justify-content-center item-align-self">
                                                    {{-- <h6>Please scan QR Code for Payment : </h6> --}}
                                                <img class="img-fluid qr-code-img" src="{{ asset($site_settings['qr_code']) }}"
                                                        alt="">
                                            </div>
                                            <div class="col-12 text-center justify-content-center item-align-self">
                                                <a id="paymentBtn" class="btn btn-md btn-warning fw-bold mt-3 mx-auto"> MAKE PAYMENT</a>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-center" id="paymentModalLabel">Please
                                                complete the payment to join our Membership.</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="paymentForm" method="post"
                                                action="{{ route('frontend.qrcodepayment') }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="reference_id" class="form-label">Name: </label>
                                                    <input type="hidden" class="form-control" id="user_id" name="user_id"
                                                        value="{{ $user_approved->id }}">
                                                    <input type="hidden" class="form-control" id="membership_fee"
                                                        name="membership_fee"
                                                        value="{{ $site_settings['membership_fee'] }}">
                                                    <input type="text" class="form-control" id="user_name"
                                                        name="user_name" value="{{ $user_approved->name }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="reference_id" class="form-label">Reference ID</label>
                                                    <input type="text" class="form-control" id="reference_id"
                                                        name="reference_id"
                                                        value="{{ old('reference_id', $user_membership->reference_id ?? '') }}"
                                                        @if (isset($user_membership) && !is_null($user_membership->reference_id)) readonly @endif>
                                                    <label class="error"
                                                        id="personError">{{ $errors->first('reference_id') }}</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="transaction_id" class="form-label">Transaction ID</label>
                                                    <input type="text" class="form-control" id="transaction_id"
                                                        name="transaction_id"
                                                        value="{{ old('transaction_id', $user_membership->transaction_id ?? '') }}"
                                                        @if (isset($user_membership) && !is_null($user_membership->transaction_id)) readonly @endif>
                                                    <label class="error"
                                                        id="personError">{{ $errors->first('transaction_id') }}</label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="date" class="form-label">Date</label>
                                                    <input type="date" class="form-control" id="date" name="date"
                                                        value="{{ old('date', $user_membership->date ?? '') }}"
                                                        @if (isset($user_membership) && !is_null($user_membership->date)) readonly @endif>
                                                    <label class="error"
                                                        id="personError">{{ $errors->first('date') }}</label>
                                                </div>

                                                @if (
                                                    !isset($user_membership) ||
                                                        (is_null($user_membership->reference_id ?? null) &&
                                                            is_null($user_membership->transaction_id ?? null) &&
                                                            is_null($user_membership->date ?? null)))
                                                    <!-- Show submit button if all fields are null -->
                                                    <div class="text-center justify-content-center item-align-self">
                                                        <button type="submit" class="btn btn-md btn-success">Done</button>
                                                    </div>
                                                @else
                                                    <!-- Display message if any field has a value -->
                                                    <div class="alert alert-info text-center">
                                                        Please wait for your payment approval.
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                            <div class="row row-cols-1 g-3">
                                <div class="col-12 mb-2">
                                    <p class="dash-category">Dashboard</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 my-auto">
                                    <div class="card h-100 mb-3 card-dash bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="">
                                        <div class="row g-0">
                                            <div
                                                class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                                <img src="{{ asset('public/images/scooter.png') }}"
                                                    class="img-fluid rounded-start pro-img-order p-1" />
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <div class="card-body card-ship-padd d-grid">
                                                    <h5 class="card-title dash-number">{{ $total_order_count }}</h5>
                                                    <p class="dash-total"> Total
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 my-auto">
                                    <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="">
                                        <div class="row g-0">
                                            <div
                                                class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                                <img src="{{ asset('public/images/scooter.png') }}"
                                                    class="img-fluid rounded-start pro-img-order p-1" />
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <div class="card-body card-ship-padd d-grid">
                                                    <h5 class="card-title dash-number">{{ $delivered_order_count }}</h5>
                                                    <p class="dash-total"> Delivered
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 my-auto">
                                    <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="">
                                        <div class="row g-0">
                                            <div
                                                class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                                <img src="{{ asset('public/images/scooter.png') }}"
                                                    class="img-fluid rounded-start pro-img-order p-1" />
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <div class="card-body card-ship-padd d-grid">
                                                    <h5 class="card-title dash-number">{{ $cancel_order_count }}</h5>
                                                    <p class="dash-total"> Cancel
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 my-auto">
                                    <div class="card h-100 mb-3 card-dash  bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="">
                                        <div class="row g-0">
                                            <div
                                                class="col-md-5 col-5 mx-auto my-auto padd-img-ship text-end justify-content-end">
                                                <img src="{{ asset('public/images/scooter.png') }}"
                                                    class="img-fluid rounded-start pro-img-order p-1" />
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <div class="card-body card-ship-padd d-grid">
                                                    <h5 class="card-title dash-number">{{ $pending_order_count }}</h5>
                                                    <p class="dash-total"> Pending
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-xl-6 col-lg-8 col-md-12 col-12 my-auto">
                                    <div class="card h-100 mb-3 card-dash bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="">
                                        <div class="card-body by-3 d-grid">
                                            <h5 class="card-title dash-number">{{ Auth::user()->name }} </h5>
                                            <p class="dash-total"><strong>Mobile : </strong> {{ Auth::user()->mobile }}
                                            </p>
                                            <p class="dash-total"><strong>Email : </strong> {{ Auth::user()->email }} </p>
                                            <p class="dash-total"><strong>Reffer Code: </strong> {!! Auth::user()->reffer_code !!}
                                            </p>

                                            @if ($my_balance->badge_level)
                                                <p class="dash-total"><strong>Badge: </strong> {!! $my_balance->badge_level !!}</p>
                                            @endif
                                            @if ($refer_by)
                                                <p class="dash-total"><strong>Referred By: </strong>
                                                    {!! $refer_by->referred_by !!}
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-cols-1 g-3 mt-3">
                                @if (!$my_balance)
                                    <div class="col-12">
                                        <span></span>
                                    </div>
                                @else
                                    @isset($my_balance)
                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Wallet Balance </p>
                                                    <h5 class="card-title dash-money">₹ {!! $my_balance->user_balance !!}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Withdraw Request</p>
                                                    <h5 class="card-title dash-money">₹ {!! $requestAmount->total_amount ? $requestAmount->total_amount : 0 !!}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Rejected Balance </p>
                                                    <h5 class="card-title dash-money">₹
                                                        {{ $rejectAmount->total_amount ? $rejectAmount->total_amount : 0 }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                @endif
                            </div>
                        </div>
                    @endif

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
            $("#paymentForm").validate({
                rules: {
                    reference_id: "required",
                    transaction_id: "required",
                    date: "required",
                },
                messages: {
                    reference_id: "Please Enter Reference Id",
                    transaction_id: "Please Enter Transaction Id",
                    date: "Please Select Date",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif
    </script>
    <script>
        // Ensure the DOM is fully loaded before attaching the event
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("paymentBtn").addEventListener("click", function() {
                // Show the modal
                let paymentModal = new bootstrap.Modal(document.getElementById("paymentModal"));
                paymentModal.show();
            });
        });
    </script>
@endsection
