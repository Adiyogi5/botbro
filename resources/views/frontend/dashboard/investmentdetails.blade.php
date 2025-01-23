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
                            <p class="ms-auto"><a href="{{ route('frontend.investment') }}" class="btn btn-md btn-primary"><i
                                        class="fa-solid fa-list"></i> Investment List</a></p>
                        </div>

                        <div class="col-12 card p-0">
                            <!-- Nav Tabs -->
                            <ul class="nav nav-tabs custom-tabs" id="investmentTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-one-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one"
                                        aria-selected="true">
                                        Investment Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-two-tab" data-bs-toggle="tab" data-bs-target="#tab-two"
                                        type="button" role="tab" aria-controls="tab-two" aria-selected="false">
                                        Investment Ledger
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-three-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-three" type="button" role="tab" aria-controls="tab-three"
                                        aria-selected="false">
                                        Withdrawal Requests
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content custom-tab-content p-4" id="investmentTabsContent">
                                <!-- Tab One Content -->
                                <div class="tab-pane fade show active" id="tab-one" role="tabpanel"
                                    aria-labelledby="tab-one-tab">
                                    {{-- Investment Details --}}
                                    {{-- ###### Tabe One start ######  --}}
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div
                                            class="bg-primary d-flex align-item-center text-white text-center justify-content-center rounded-1 h-100 w-100 p-2">
                                            <div class="my-auto">
                                                <h5 class="mb-0"><strong>Investment No.</strong>
                                                    {{ $investment_data->invest_no }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-5 px-3 py-2">
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
                                                <p class="fw-bold">Address : </p>
                                                <p>{{ $investment_data->address_1 }}, {{ $investment_data->address_2 }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">Country : </p>
                                                <p>{{ $investment_data->country_name }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">State : </p>
                                                <p>{{ $investment_data->state_name }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">City : </p>
                                                <p>{{ $investment_data->city_name }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">Postcode : </p>
                                                <p>{{ $investment_data->postcode }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold mb-0">Approval Status:</p>
                                                <span
                                                    class="px-1 rounded-1 {{ is_null($investment_data->is_approved) ? 'bg-warning' : ($investment_data->is_approved == 1 ? 'bg-success' : 'bg-danger') }}">
                                                    {{ is_null($investment_data->is_approved) ? 'PENDING' : ($investment_data->is_approved == 1 ? 'APPROVED' : 'REJECTED') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">Investment Date : </p>
                                                <p>{{ \Carbon\Carbon::parse($investment_data->date)->format('d M, Y') }}
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">Invest Amount : </p>
                                                <p>{{ $investment_data->invest_amount }}</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-bold">Rate Of Intrest : </p>
                                                <p>{{ $investment_data->rate_of_intrest }}%</p>
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
                                                <p><a href="{{ imageexist($investment_data->screenshot) }}"
                                                        target="_blank"><img class="img-fluid border"
                                                            src="{{ imageexist($investment_data->screenshot) }}"
                                                            alt=""
                                                            style="height: 150px; width:auto; margin-top:3px;"></a></p>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ###### Tabe One End ######  --}}
                                </div>

                                <div class="tab-pane fade" id="tab-two" role="tabpanel" aria-labelledby="tab-two-tab">
                                    {{-- Investment Ledger --}}
                                    {{-- ###### Tabe Two start ######  --}}
                                    <div class="col-12 p-0">
                                        {{-- <div class="d-flex align-items-center justify-content-center">
                                            <div
                                                class="bg-primary d-flex align-item-center text-white text-center justify-content-center rounded-1 h-100 w-100 p-2">
                                                <div class="my-auto">
                                                    <h5 class="mb-0"><strong>Investment Ledger</strong>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <table class="table table-bordered datatable px-3 py-2">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Rate of Interest</th>
                                                    <th class="text-danger">Debit (₹)</th>
                                                    <th class="text-success">Credit (₹)</th>
                                                    <th class="fw-bold text-primary">Balance (₹)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ledgerData as $ledger)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($ledger->date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $ledger->description }}</td>
                                                        <td>{{ $ledger->rate_of_intrest ?? '-' }}%</td>
                                                        <td class="text-danger">
                                                            {{ $ledger->debit ? number_format($ledger->debit, 2) : '-' }}
                                                        </td>
                                                        <td class="text-success">
                                                            {{ $ledger->credit ? number_format($ledger->credit, 2) : '-' }}
                                                        </td>
                                                        <td class="fw-bold text-primary">
                                                            {{ number_format($ledger->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- ###### Tabe Two End ######  --}}
                                </div>

                                <div class="tab-pane fade" id="tab-three" role="tabpanel"
                                    aria-labelledby="tab-three-tab">
                                    {{-- Withdrawal Requests --}}
                                    {{-- ###### Tabe Three start ######  --}}
                                    <div class="col-12 px-3">
                                        <div class="d-flex justify-content-between">
                                            <p class="dash-category">Withdrow Request</p>
                                            <div>
                                                <a data-bs-toggle="modal" data-bs-target="#addModal"
                                                    class="btn btn-md btn-primary">
                                                    <i class="fa-solid fa-paper-plane"></i> Add Request
                                                </a>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <small class="w-100">Note : </small>
                                            <small class="w-100">1. For Withdrawal Requests Your Investment atleast 6
                                                month
                                                old</small>
                                            <small class="w-100">2. Withdrawal requests can only be made between the 1st
                                                and 5th of
                                                each month.</small>
                                        </div>
                                        <div class="col-12 overflow-scroll">
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
                                                            <td colspan="5" class="text-danger"> Withdrow Request
                                                                History Not
                                                                Found
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
                                    {{-- ###### Tabe Three End ######  --}}
                                    {{-- ############# Modal For Withdrow Request Add ##############  --}}
                                    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
                                        aria-labelledby="addModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content p-3">
                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12 text-center justify-content-center">
                                                            <small class="w-100 text-success">Note : For Withdrawal
                                                                Requests Your
                                                                Investment atleast 6 month old</small><br/>
                                                            <small class="w-100 text-success">Note : Withdrawal requests
                                                                can only
                                                                be made between the 1st and 5th of each month.</small>
                                                            <span class="mx-auto">
                                                                <p class="modal-category">Add Withdrow Request</p>
                                                                <h5>Current Balance: ₹ {!! $my_balance->balance !!}
                                                                </h5>
                                                            </span>
                                                        </div>
                                                        <div class="col-12 text-center justify-content-center"
                                                            style="border-top: 1px solid #000;     margin: 5px; padding-top: 10px;">
                                                            <form id="addForm" name="addForm" method="post"
                                                                action="{{ route('frontend.withdrowinvestment', $my_balance->invest_id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="row g-3">
                                                                    <div class="col-md-10 col-12 mx-auto">
                                                                        <label for="amount"
                                                                            class="form-label">Amount</label>
                                                                        <input type="text"
                                                                            class="form-control rounded-0"
                                                                            value="{{ old('amount') }}" id="amount"
                                                                            name="amount"
                                                                            onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/[^\d\.]/g, '')"
                                                                            placeholder="Enter Amount">
                                                                        <label class="error"
                                                                            id="personError">{{ $errors->first('amount') }}</label>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div
                                                                        class="text-center justify-content-center pt-3 pb-0">
                                                                        <button type="submit" id="SubmitWidthdraw"
                                                                            class="btn btn-primary px-3" />Submit</button>
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
    <script>
        @if (session('status'))
            toastr.success("{{ session('status') }}");
        @endif
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#SubmitWidthdraw').on('click', function(event) {
                event.preventDefault();
                var fee = {{ TRANSFER_FEE }};
                var amount = $('#amount').val();
                if (amount == '' || amount == '0') {
                    $('#personError').html('Please enter valid amount!!');
                    return false;
                }
                var transferAmout = amount;

                Swal.fire({
                    title: "Are you sure?",
                    html: "Your widthdraw request is ₹" + amount + " ",
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
