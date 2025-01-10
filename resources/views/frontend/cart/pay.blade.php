@extends('frontend.layouts.app')

@section('content')
    @include('frontend.includes.profile_header')

    <div class="shooping_cart py-lg-5 py-3">
        <div class="container">
            <div class="row">
                <div class="col-12" id="error_div">
                    @if (!empty($warning))
                        <div class="alert alert-danger" role="alert">
                            {{ $warning }}
                        </div>
                    @endif
                </div>

                <div class="col-lg-7 col-md-9 col-12 m-auto mt-2" style="text-align: center;">
                    <h5 class="fw-bold text-success">Please complete your payment for process the order !!</h5>
                    <div class="card mb-3 shadow-sm total-box">
                        <form name='razorpayform' action="{{ url('verify-payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                            <h4 class="text-center fw-bold mb-2 head-pay">Payment Details</h4>
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th class="p-3">Order No</th>
                                        <td class="p-3">{{ $order_no }}</td>
                                    </tr>
                                    <tr>
                                        <th class="p-3">Amount</th>
                                        <td class="p-3">{{ CURRENCY_SYMBOL }} {{ $display_amount }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button"
                                                class="btn btn-md btn-warning text-white btn-upaycard px-lg-4 px-md-3 razorpay-btn"
                                                id="rzp-button1">Pay with Razorpay</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loader"></div>  


@endsection

@section('js')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
        var options = <?=$payment_json?>;
        var BASEPATH = '<?=BASE_PATH?>';
        /**
         * The entire list of Checkout fields is available at
         * https://docs.razorpay.com/docs/checkout-form#checkout-fields
         */
        options.handler = function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.razorpayform.submit();
        };

        // Boolean whether to show image inside a white frame. (default: true)
        options.theme.image_padding = false;

        options.modal = {
            ondismiss: function() {
                console.log("This code runs when the popup is closed");
                window.location.href = BASEPATH;
            },
            // Boolean indicating whether pressing escape key 
            // should close the checkout form. (default: true)
            escape: true,
            // Boolean indicating whether clicking translucent blank
            // space outside checkout form should close the form. (default: false)
            backdropclose: false
        };
        var rzp = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e) {   
            $('#loader').show();
            rzp.open();
            e.preventDefault();
        }
    </script>
@endsection