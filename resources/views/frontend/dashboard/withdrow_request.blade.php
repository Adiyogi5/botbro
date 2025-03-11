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
                        <div class="row row-cols-1 g-3">
                            @if (!$my_balance)
                                <div class="col-12">
                                    <span></span>
                                </div>
                            @else
                                @isset($my_balance)
                                <div class="col-12">
                                    <span></span>
                                </div>
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                        <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                            <div class="card-body bg-dash-out card-ship-padd py-3">
                                                <p class="dash-balance"> Balance </p>
                                                <h5 class="card-title dash-money">₹ {!! $my_balance->user_balance !!}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                @endisset
                                <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                    <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                        <div class="card-body bg-dash-out card-ship-padd py-3">
                                            <p class="dash-balance">Withdraw Request</p>
                                            <h5 class="card-title dash-money">₹ {!! ($requestAmount->total_amount) ? $requestAmount->total_amount : 0 !!}</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-6 col-md-6 col-12 my-auto">
                                    <div class="card h-100 mb-3 bg-dash-in overflow-hidden">
                                        <div class="card-body bg-dash-out card-ship-padd py-3">
                                            <p class="dash-balance">Rejected Balance </p>
                                            <h5 class="card-title dash-money">₹ {{ ($rejectAmount->total_amount) ? $rejectAmount->total_amount : 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row row-cols-1 g-3">
                            <div class="col-12 d-flex justify-content-between">
                                <p class="dash-category">Withdrow Request</p>                                
                                <div>
                                    <a data-bs-toggle="modal" data-bs-target="#addModal"
                                        class="btn btn-md btn-warning text-white btn-upaycard">
                                        Add Request
                                    </a>
                                </div>
                            </div>
                            <div class="col-12">
                                <small class="w-100">For Withdrawal Requests to a Bank Account, an additional ₹{{TRANSFER_FEE}} charges an online transfer fee & platform fee</small>
                                @if ($message = Session::get('success'))
                                    <div id="success-message"
                                        class="alert alert-success alert-block margin10 flash-message">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div id="error-message" class="alert alert-danger alert-block margin10 flash-message">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-center justify-content-center">
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Voucher No</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Request Date</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center justify-content-center">
                                        @if ($my_withdrow_request->isEmpty())
                                            <tr class="text-center">
                                                <td colspan="5" class="text-danger"> Withdrow Request History Not Found
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($my_withdrow_request as $key => $withdrow_request)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        {{ $withdrow_request['reference_id'] }}
                                                    </td>
                                                    <td>
                                                        {{ $withdrow_request['amount'] }}
                                                    </td>
                                                    <td>
                                                        {{ date('d-m-Y', strtotime($withdrow_request['request_date'])) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="btn-md rounded-pill text-white py-1 px-2 @switch($withdrow_request['request_status']) @case(0) btn-warning @break @case(1) btn-success @break @case(2) btn-danger @break @endswitch">
                                                            @switch($withdrow_request['request_status'])
                                                                @case(0)
                                                                    Requested
                                                                @break

                                                                @case(1)
                                                                    Approved
                                                                @break

                                                                @case(2)
                                                                    Rejected
                                                                @break
                                                            @endswitch
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ############# Modal For Withdrow Request Add ##############  --}}
                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content p-3">
                                <div class="modal-header border-0">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <small class="w-100">For Withdrawal Requests to a Bank Account, an additional ₹{{TRANSFER_FEE}} charges an online transfer fee & platform fee</small>
                                            <span>
                                                <p class="modal-category">Add Withdrow Request</p>
                                                <h5 class="card-title">Wallet Balance: ₹ {!! $my_balance->user_balance !!}</h5>
                                            </span>
                                        </div>
                                        <div class="col-12"  style="border-top: 1px solid #000;     margin: 5px; padding-top: 10px;">
                                            <form id="addForm" name="addForm" method="post" action="{{ route('frontend.withdrow') }}"
                                                enctype="multipart/form-data">
                                                @csrf 
                                                <div class="row g-3">
                                                    <div class="col-md-6 col-12">
                                                        <label for="amount" class="form-label">Amount</label>
                                                        <input type="text" class="form-control rounded-0"
                                                            value="{{ old('amount') }}" id="amount" name="amount"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')" placeholder="Enter Amount" >
                                                        <label class="error"
                                                            id="personError">{{ $errors->first('amount') }}</label>
                                                    </div> 
                                                </div>

                                                <div class="row">
                                                    <div class="text-center justify-content-center pt-lg-4 pt-3 pb-3">
                                                        <button type="submit" id="SubmitWidthdraw"
                                                                class="btn btn-order-ship submitbtn" />Submit</button> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section> 

    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
 
    <script type="text/javascript">
        $(document).ready(function() {
            $('#SubmitWidthdraw').on('click', function (event) {
                event.preventDefault();
                var fee = {{TRANSFER_FEE}};
                var amount = $('#amount').val();
                if(amount=='' || amount=='0'){
                    $('#personError').html('Please enter valid amount!!');
                    return false;
                }  
                var charges = fee; //(amount*fee)/100; 
                var transferAmout = amount-charges;

                Swal.fire({
                    title: "Are you sure?",
                    html: "Your widthdraw request is ₹"+amount+", Online transfer fee is ₹"+charges+".<br><b> Transfer amount to Bank is ₹"+transferAmout+" !!</b>",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, do it!'
                }).then((result) => {
                    if (result.isConfirmed) {                    
                        var $this = $('#SubmitWidthdraw');
                        BtnLoading($this);
                        $("#addForm").submit();
                    }
                });                
            });     
        }); 
   
        function BtnLoading(elem) {
            $(elem).attr("data-original-text", $(elem).html());
            $(elem).prop("disabled", true);
            $(elem).html('<i class="spinner-border spinner-border-sm"></i> Loading...');
        }

        function BtnReset(elem) {
            $(elem).prop("disabled", false);
            $(elem).html($(elem).attr("data-original-text"));
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var successMessage = document.querySelector('.alert-success');
                var errorMessage = document.querySelector('.alert-danger');
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000);
        });
    </script>
@endsection
