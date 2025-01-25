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
                                    <h4 class="text-center justify-content-center mb-3 membership-heading-badge">
                                        Payment Details For Membership
                                    </h4>

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
                                                <p class="membership-fee-badge">{{ $site_settings['membership_fee'] }}</p>
                                            </span>
                                            @if (
                                                !isset($user_membership) ||
                                                    (is_null($user_membership->reference_id ?? null) &&
                                                        is_null($user_membership->transaction_id ?? null) &&
                                                        is_null($user_membership->payment_date ?? null)))
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Payment Status : </h6>
                                                    <p class="payment-status-unpaid-badge">UnPaid</p>
                                                </span>
                                            @else
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Payment Status : </h6>
                                                    <p class="payment-status-paid-badge">Paid</p>
                                                </span>
                                            @endif
                                        </div>
                                        @if(!empty($site_settings['qr_code']))
                                            <div class="col-md-6 col-12 text-center justify-content-center item-align-self">
                                                {{-- <h6>Please scan QR Code for Payment : </h6> --}}
                                                <img class="img-fluid qr-code-img" src="{{ asset($site_settings['qr_code']) }}"
                                                    alt="">
                                            </div>
                                        @endif
                                        @if (
                                            !isset($user_membership) ||
                                                (is_null($user_membership->reference_id ?? null) &&
                                                    is_null($user_membership->transaction_id ?? null) &&
                                                    is_null($user_membership->payment_date ?? null)))
                                            <div class="col-12 text-center justify-content-center item-align-self">
                                                <a id="paymentBtn" class="btn btn-md btn-warning fw-bold mt-3 mx-auto"> MAKE
                                                    PAYMENT</a>
                                            </div>
                                        @else
                                            <div class="alert alert-success text-center">
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Reference ID : </h6>
                                                    <p>{{ $user_membership->reference_id}}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Transaction ID : </h6>
                                                    <p>{{ $user_membership->transaction_id}}</p>
                                                </span>
                                                <span class="d-flex justify-content-between item-align-self">
                                                    <h6>Payment Date : </h6>
                                                    <p> {{\Carbon\Carbon::parse($user_membership->payment_date)->format('d-m-Y')}}</p>
                                                </span>
                                                Please wait for your payment approval.
                                            </div>
                                        @endif
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
                                                {{-- <div class="mb-3">
                                                    <label for="payment_date" class="form-label">Payment Date</label>
                                                    <input type="payment_date" class="form-control" id="payment_date" name="payment_date"
                                                        value="{{ old('payment_date', $user_membership->payment_date ?? '') }}"
                                                        @if (isset($user_membership) && !is_null($user_membership->payment_date)) readonly @endif>
                                                    <label class="error"
                                                        id="personError">{{ $errors->first('payment_date') }}</label>
                                                </div> --}}

                                                @if (
                                                    !isset($user_membership) ||
                                                        (is_null($user_membership->reference_id ?? null) &&
                                                            is_null($user_membership->transaction_id ?? null) &&
                                                            is_null($user_membership->payment_date ?? null)))
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
                           
                            <div class="row gy-3">
                                @if(!empty($user_membership))
                                @php
                                    $membershipStartDate = \Carbon\Carbon::parse(
                                        $user_membership->membership_start_date,
                                    );
                                    $membershipEndDate = \Carbon\Carbon::parse($user_membership->membership_end_date);
                                    $remainingTime = $membershipEndDate->diffInSeconds(now()); // In seconds
                                @endphp

                                <div class="col-12 mb-2 d-flex text-center justify-content-between">
                                    <p class="dash-category my-auto">Membership Validity :</p>
                                    <div class="dash-timer my-auto w-100" id="timer-message"
                                        data-remaining-time="{{ $remainingTime }}">
                                        <div class="timer-box">
                                            <div class="timer d-flex">
                                                <div id="days" class="timer-element">
                                                    <span>00</span>
                                                    <p>Days</p>
                                                </div>
                                                <div id="hours" class="timer-element">
                                                    <span>00</span>
                                                    <p>Hours</p>
                                                </div>
                                                <div id="minutes" class="timer-element">
                                                    <span>00</span>
                                                    <p>Minutes</p>
                                                </div>
                                                <div id="seconds" class="timer-element">
                                                    <span>00</span>
                                                    <p>Seconds</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-auto">
                                    <div class="d-flex card h-100 mb-3 card-dash bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="" style="filter: hue-rotate(120deg);">
                                        <div class="card-body by-3 d-grid ">
                                            {{-- <h5 class="card-title dash-number">{{ Auth::user()->name }} </h5> --}}
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Name : </strong></span>
                                                <span>{{ Auth::user()->name }}</span>
                                            </p>
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Mobile : </strong></span>
                                                <span>{{ Auth::user()->mobile }}</span>
                                            </p>
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Email : </strong></span>
                                                <span>{{ Auth::user()->email }}</span>
                                            </p>
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Reffer Code: </strong></span>
                                                <span> {!! Auth::user()->reffer_code !!}</span>
                                            </p>

                                            {{-- @if ($my_balance->badge_level)
                                                <p class="dash-total d-flex text-center justify-content-between">
                                                    <span><strong>Badge: </strong></span>
                                                    <span> {!! $my_balance->badge_level !!}</span>
                                                </p>
                                            @endif --}}
                                            @if ($refer_by)
                                                <p class="dash-total d-flex text-center justify-content-between">
                                                    <span><strong>Referred By: </strong></span>
                                                    <span>{!! $refer_by->referred_by !!}</span>
                                                </p>
                                            @endif
                                            @if (!empty($user_membership->membership_fee))
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Membership Fee : </strong></span>
                                                <span> {{ $user_membership->membership_fee }}</span>
                                            </p>
                                            @endif
                                            @if (Auth::user()->is_approved)
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Payment Status: </strong></span>
                                                @if (Auth::user()->is_approved == 1)
                                                    <span class="badge bg-success my-auto">Paid</span>
                                                @else
                                                    <span class="badge bg-danger my-auto">Unpaid</span>
                                                @endif
                                            </p>
                                            @endif
                                            @if (Auth::user()->is_approved)
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Membership Status: </strong></span>
                                                @if (Auth::user()->is_approved == 1)
                                                    <span class="badge bg-success my-auto">Active</span>
                                                @else
                                                    <span class="badge bg-danger my-auto">Inactive</span>
                                                @endif
                                            </p>
                                            @endif
                                            @if (!empty($user_membership->membership_start_date))
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Membership Start Date : </strong></span>
                                                <span>{{ \Carbon\Carbon::parse($user_membership->membership_start_date)->format('d-m-Y') }}</span>
                                            </p>
                                            @endif
                                            @if (!empty($user_membership->membership_end_date))
                                            <p class="dash-total d-flex text-center justify-content-between">
                                                <span><strong>Membership End Date : </strong></span>
                                                <span>{{ \Carbon\Carbon::parse($user_membership->membership_end_date)->format('d-m-Y') }}</span>
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            {{-- <div class="row row-cols-1 g-3 mt-3">
                                <div class="col-lg-3 col-md-6 col-6 my-auto">
                                    <div class="card h-100 mb-3 card-dash bg-white overflow-hidden">
                                        <img src="{{ asset('public/images/Ellipse.png') }}"
                                            class="position-absolute dash-abs-img translate-middle" alt="" style="filter: hue-rotate(120deg);">
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
                                            class="position-absolute dash-abs-img translate-middle" alt="" style="filter: hue-rotate(120deg);">
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
                                            class="position-absolute dash-abs-img translate-middle" alt="" style="filter: hue-rotate(120deg);">
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
                                            class="position-absolute dash-abs-img translate-middle" alt="" style="filter: hue-rotate(120deg);">
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
                            </div> --}}

                            <div class="row row-cols-1 g-3 mt-3">
                                @if (!$totalInvestmentSum)
                                    <div class="col-12">
                                        <span></span>
                                    </div>
                                @else
                                    @isset($totalInvestmentSum)
                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Total Investment Balance </p>
                                                    <h5 class="card-title dash-money">₹ {!! $totalInvestmentSum !!}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Current Referral Balance</p>
                                                    <h5 class="card-title dash-money">₹ {!! $user_refferBalance->user_reffer_balance ? $user_refferBalance->user_reffer_balance : 0 !!}</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                            <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                                <div class="card-body bg-dash-out card-ship-padd py-3">
                                                    <p class="dash-balance">Current Commission Balance </p>
                                                    <h5 class="card-title dash-money">₹
                                                        {{ $user_commissionBalance->user_commission_balance ? $user_commissionBalance->user_commission_balance : 0 }}
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
                    payment_date: "required",
                },
                messages: {
                    reference_id: "Please Enter Reference Id",
                    transaction_id: "Please Enter Transaction Id",
                    payment_date: "Please Select Date",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });

        // Initialize timer
        document.addEventListener('DOMContentLoaded', function() {
            var remainingTime = document.getElementById("timer-message").getAttribute('data-remaining-time');
            var timerElements = {
                days: document.getElementById("days").querySelector("span"),
                hours: document.getElementById("hours").querySelector("span"),
                minutes: document.getElementById("minutes").querySelector("span"),
                seconds: document.getElementById("seconds").querySelector("span")
            };

            function updateTimer() {
                var timeLeft = remainingTime * 1000; // Convert remaining time to milliseconds
                var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                // Update the display with the calculated values
                timerElements.days.textContent = days < 10 ? "0" + days : days;
                timerElements.hours.textContent = hours < 10 ? "0" + hours : hours;
                timerElements.minutes.textContent = minutes < 10 ? "0" + minutes : minutes;
                timerElements.seconds.textContent = seconds < 10 ? "0" + seconds : seconds;

                // Reduce the remaining time
                remainingTime -= 1;

                // Stop the timer when it reaches zero
                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    timerElements.days.textContent = "00";
                    timerElements.hours.textContent = "00";
                    timerElements.minutes.textContent = "00";
                    timerElements.seconds.textContent = "00";
                }
            }

            // Update the timer every second
            var timerInterval = setInterval(updateTimer, 1000);
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
