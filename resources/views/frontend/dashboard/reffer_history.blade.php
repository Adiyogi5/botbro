@extends('frontend.layouts.app')

@section('content')
    <main>
        {{-- ===============Breadcrumb Start============= --}}
        @include('frontend.includes.profile_header')
        {{-- ===============Breadcrumb End============= --}}


        {{-- ===============Reffer Histrory Start============= --}}
        <section id="dashboard">
            <div class="container my-lg-5 my-md-4 my-3">
                <div class="row pt-lg-4 pt-3">
                    <div class="col-lg-3 col-md-4 col-12">

                        @include('frontend.includes.sidebar_inner')

                    </div>
                    <div class="col-lg-9 col-md-8 col-12 mt-md-0 mt-3">
                        <div class="row row-cols-1 g-3">

                            <div class="col-12 d-lg-flex text-lg-start justify-content-lg-between d-grid text-center justify-content-center gap-2 align-item-self mb-1">
                                <p class="dash-category mb-0">Referral History</p>
                                @if ($approvedMemberships > 5 || $totalMembers > 6)
                                    <h5 class="ms-lg-auto my-auto py-1 px-lg-3 px-2 rounded-1 text-white bg-secondary">
                                        <i class="fa-solid fa-user-secret"></i> Agent
                                    </h5>
                                @else
                                    <h5 class="ms-lg-auto my-auto py-1 px-lg-3 px-2 rounded-1 text-white bg-secondary">
                                        <i class="fa-solid fa-user"></i> Member
                                    </h5>
                                @endif
                                @isset($my_balance)
                                <h5 class="ms-auto my-auto py-1 px-2 rounded-1 text-white bg-secondary">Referral Balance :
                                    ₹ {!! $my_balance->balance !!}</h5>
                                @endisset
                            </div>
                            <div class="col-12 card p-0">
                                <!-- Nav Tabs -->
                                <ul class="nav nav-tabs custom-tabs" id="investmentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tab-one-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one"
                                            aria-selected="true">
                                            Referral List
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-two-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-two" type="button" role="tab" aria-controls="tab-two"
                                            aria-selected="false">
                                            Referral and Commission Ledger
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-three-tab" data-bs-toggle="tab"
                                            data-bs-target="#tab-three" type="button" role="tab"
                                            aria-controls="tab-three" aria-selected="false">
                                            Withdrow Request
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content custom-tab-content p-lg-4 p-2" id="investmentTabsContent">
                                    <!-- Tab One Content -->
                                    <div class="tab-pane fade show active" id="tab-one" role="tabpanel"
                                        aria-labelledby="tab-one-tab">
                                        {{-- ####### Reffer History Table ####### --}}
                                        <div class="col-12">
                                            <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="text-center justify-content-center">
                                                    <tr>
                                                        <th scope="col">S.no</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Mobile Number</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Membership Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center justify-content-center">
                                                    @if ($my_reffer->isEmpty())
                                                        <tr class="text-center">
                                                            <td colspan="4" class="text-danger"> Referral History Not
                                                                Found
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($my_reffer as $key => $reffer)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>
                                                                    {{ $reffer['name'] }}
                                                                </td>
                                                                <td>
                                                                    {{ $reffer['mobile'] }}
                                                                </td>
                                                                <td>
                                                                    {{ date('d-m-Y', strtotime($reffer['created_at'])) }}
                                                                </td>
                                                                <td>
                                                                    @if ($reffer['is_approved'] == 1)
                                                                        <span
                                                                            class="py-1 px-2 rounded-1 bg-success">Approved</span>
                                                                    @elseif($reffer['is_approved'] == 0)
                                                                        <span
                                                                            class="py-1 px-2 rounded-1 bg-warning">Pending</span>
                                                                    @elseif($reffer['is_approved'] == 2)
                                                                        <span
                                                                            class="py-1 px-2 rounded-1 bg-danger">Rejected</span>
                                                                    @else
                                                                        <span
                                                                            class="py-1 px-2 rounded-1 bg-secondary">Unknown</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab Two Content -->
                                    <div class="tab-pane fade" id="tab-two" role="tabpanel"
                                        aria-labelledby="tab-two-tab">
                                        {{-- Referral and Commission Ledger --}}
                                        {{-- ###### Tabe Three start ######  --}}
                                        <div class="col-12 p-0">
                                            <div class="table-responsive">
                                            <table class="table table-bordered datatable px-lg-3 px-2 py-2">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Description</th>
                                                        <th>Rate of Interest</th>
                                                        <th class="text-danger">Debit (₹)</th>
                                                        <th class="text-success">Credit (₹)</th>
                                                        <th class="fw-bold text-primary">Balance (₹)
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($ledgerData->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center text-danger">Record Not
                                                            Found..!!</td>
                                                    </tr>
                                                @else
                                                    @foreach ($ledgerData as $ledger)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($ledger->date)->format('d-m-Y') }}
                                                            </td>
                                                            <td>{{ $ledger->description }}</td>
                                                            <td>{{ $ledger->rate_of_intrest > 0 ? $ledger->rate_of_intrest . '%' : '--' }}</td>
                                                            <td class="text-danger">
                                                                {{ $ledger->debit ? number_format($ledger->debit, 2) : '--' }}
                                                            </td>
                                                            <td class="text-success">
                                                                {{ $ledger->credit ? number_format($ledger->credit, 2) : '--' }}
                                                            </td>
                                                            <td class="fw-bold text-primary">
                                                                {{ number_format($ledger->balance, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                        {{-- ###### Tabe Three End ######  --}}
                                    </div>

                                    <!-- Tab Three Content -->
                                    <div class="tab-pane fade" id="tab-three" role="tabpanel"
                                        aria-labelledby="tab-three-tab">
                                        {{-- ####### Referral Balance History ####### --}}
                                        <div class="col-12">
                                            <div class="d-lg-flex text-lg-start justify-content-lg-between d-grid text-center justify-content-center gap-2">
                                                <p class="dash-category">Withdrow Referral Amount Request</p>
                                                <div>
                                                    <a data-bs-toggle="modal" data-bs-target="#addModal"
                                                        class="btn btn-md btn-primary">
                                                        <i class="fa-solid fa-paper-plane"></i> Add Request
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="">
                                                <small class="w-100">Note : For Withdrawal Requests Your Referral atleast 6
                                                    Membership Approved.</small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="text-center justify-content-center">
                                                    <tr>
                                                        <th scope="col">S.No</th>
                                                        <th scope="col">Voucher No</th>
                                                        <th scope="col">Request Amount (₹)</th>
                                                        <th scope="col">Request Date</th>
                                                        <th scope="col">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center justify-content-center">
                                                    @if ($my_withdrow_request->isEmpty())
                                                        <tr class="text-center">
                                                            <td colspan="5" class="text-danger"> Withdrow Referral
                                                                Request
                                                                History Not Found
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
                                                                    {{ $withdrow_request['request_amount'] }}
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

                                        {{-- ############# Modal For Withdrow Request Add ##############  --}}
                                        <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
                                            aria-labelledby="addModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content p-3">
                                                    <div class="modal-header pt-1 border-0">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-1">
                                                        <div class="row">
                                                            <div class="col-12 text-center justify-content-center">
                                                                <span class="mx-auto">
                                                                    <p class="modal-category">Add Withdrow Referral
                                                                        Request</p>
                                                                        @isset($my_balance)
                                                                        <h5>Current Balance: ₹ {!! $my_balance->balance !!}
                                                                        </h5>
                                                                        @endisset
                                                                </span>
                                                                <small
                                                                    class="w-100 text-success text-decoration-underline">Note</small><br />
                                                                {{-- <small class="w-100 text-success">1. For Withdrawal
                                                                    Requests
                                                                    Your Investment atleast 6 month old</small><br /> --}}
                                                                <small class="w-100 text-success">1. Withdrawal requests
                                                                    can
                                                                    only be made between the 1st and 5th of each
                                                                    month.</small>
                                                            </div>
                                                            <div class="col-12 text-center justify-content-center"
                                                                style="border-top: 1px solid #000;     margin: 5px; padding-top: 10px;">
                                                                <form id="addForm" name="addForm" method="post"
                                                                    action="{{ route('frontend.withdrow_reffer_request') }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="row g-3">
                                                                        <div class="col-md-10 col-12 mx-auto">
                                                                            <label for="amount"
                                                                                class="form-label">Amount (₹)</label>
                                                                            <input type="text"
                                                                                class="form-control rounded-0"
                                                                                value="{{ old('amount') }}"
                                                                                id="amount" name="amount"
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
        {{-- ===============Reffer Histrory End============= --}}


    </main>
@endsection

@section('js')
    <script src="{{ ASSETS }}js/sweetalert2.min.js"></script>
    <script src="{{ ASSETS }}js/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
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
