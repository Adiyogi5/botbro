@extends('admin.layouts.main')
@section('header_scripts')
    <link rel="stylesheet" type="text/css" href="{{ CSS }}ajax-datatables.css" />
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row justify-content-end">
                                <div class="col-auto px-0">
                                    <a href="{{ url('admin/investments') }}"
                                        class="btn btn-primary text-white"><i class="fa fa-list"></i> Investment List</a>
                                    <a id="print" title="Invoice Print" target="_blank"
                                        href="{{ url('admin/investments/invoice') . '/' . $data->id }}"
                                        class="btn btn-success text-white"><i class="fa fa-print"></i> Invoice</a>
                                </div>
                            </div>
                            <div class="row">
                                {{-- Investment Details --}}
                                <div class="col-md card m-2 p-0">
                                    <div class="card-header">
                                        <p class="card-title mb-0 fw-bold text-secondary"><i
                                                class="fa fa-shopping-cart"></i> Investment Details</p>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-money-bill-transfer"
                                                title="Order No."></div>
                                            <div class="col-11 align-self-end"><b>Invest No :</b> {{ $data->invest_no }}</div>
                                        </div>
										<div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-sack-dollar"
                                                title="Order No."></div>
                                            <div class="col-11 align-self-end"><b>Invest Amount :</b> {{ $data->invest_amount }}</div>
                                        </div>
										<div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-sack-dollar"
                                                title="Order No."></div>
                                            <div class="col-11 align-self-end"><b>Rate Of Intrest :</b> {{ $data->rate_of_intrest . '%' }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-calendar"
                                                title="Order Date"></div>
                                            <div class="col-11 align-self-end">
												<b>Invest Date :</b>
                                                {{ date('d-M-Y', strtotime($data->created_at)) }}</div>
                                        </div>
										<div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card"
                                                title="Transaction Detail"></div>
                                            <div class="col-11 align-self-end">
                                                @if ($data->is_approved == 0)
													<span class="small">
														<b>Investment Status : </b> 
														<span class="btn-sm btn-warning disabled">
															{{ 'Pending' }}
														</span>
													</span>
                                                @else
                                                    <span class="small">
                                                        <b>Investment Status : </b> 
														<span class="btn-sm btn-success disabled">
															{{ 'Approved' }}
														</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
										<div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card"
                                                title="Transaction Detail"></div>
                                            <div class="col-11 align-self-end">
                                                @if ($data->payment_type == 0)
													<span class="small">
														<b>Payment Type : </b> 
														<span class="btn-sm btn-warning disabled">
															{{ 'Cash On Delivery' }}
														</span>
													</span>
                                                @else
                                                    <span class="small">
                                                        <b>Payment Type : </b> 
														<span class="btn-sm btn-secondary disabled">
															{{ 'Online Payment' }}
														</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card"
                                                title="Payment Status"></div>
                                            <div class="col-11 align-self-end">
                                                    <span class="small">
                                                        <b>Payment Status:</b>
                                                        @if ($data->payment_status == 0)
                                                            <span class="btn-sm btn-warning disabled">
                                                                {{ 'Unpaid' }}
                                                            </span>
                                                        @else
                                                            <span class="btn-sm btn-success disabled">
                                                                {{ 'Paid' }}
                                                            </span>
                                                        @endif
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card"
                                                title="Transaction Detail"></div>
                                            <div class="col-11 align-self-end">
                                                    <span class="small">
                                                        <b>Transaction ID : </b>{{ $data->transaction_id }}
                                                    </span>
                                            </div>
                                        </div>
										<div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-image my-auto"
                                                title="Transaction Detail"></div>
                                            <div class="col-11 align-self-end">
                                                @if (!empty($data->screenshot))
												<span class="small">
													<a href="{{imageexist($data->screenshot)}}" target="_blank"><img class="img-fluid" style="height: 150px; width:auto"  src="{{imageexist($data->screenshot)}}" alt=""></a>
												</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Customer Details --}}
                                <div class="col-md card m-2 p-0">
                                    <div class="card-header">
                                        <p class="card-title mb-0 fw-bold text-secondary"><i class="fa fa-user"></i>
                                            Customer Details</p>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-user"
                                                title="Customer Name"></div>
                                            <div class="col-11 align-self-end text-capitalize">{{ $data->customer_name }}
                                            </div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-envelope"
                                                title="Email"></div>
                                            <div class="col-11 align-self-end">{{ $data->customer_email }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-phone"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->customer_mobile }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-address-book"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->address_1 }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-address-book"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->address_2 }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-location-dot"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->country_name }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-location-dot"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->state_name }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-location-dot"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->city_name }}</div>
                                        </div>
                                        <div class="row m-0 p-2 border-bottom">
                                            <div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-map-pin"
                                                title="Mobile"></div>
                                            <div class="col-11 align-self-end">{{ $data->postcode }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Investment Ledger Table --}}
                                <div class="col-12 card mt-2 mb-1 p-0">
                                    <div class="card-header">
                                        <p class="card-title mb-0 fw-bold text-secondary"><i class="fa-solid fa-money-bill-transfer"></i> Investment Ledger</p>
                                    </div>
                                    <div class="card-body p-0">
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
                                                        <td>{{ \Carbon\Carbon::parse($ledger->date)->format('d-m-Y') }}</td>
                                                        <td>{{ $ledger->description }}</td>
                                                        <td>{{ $ledger->rate_of_intrest ?? '-' }}%</td>
                                                        <td class="text-danger">
                                                            {{ $ledger->debit ? number_format($ledger->debit, 2) : '-' }}</td>
                                                        <td class="text-success">
                                                            {{ $ledger->credit ? number_format($ledger->credit, 2) : '-' }}</td>
                                                        <td class="fw-bold text-primary">{{ number_format($ledger->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer_scripts')

@endsection
