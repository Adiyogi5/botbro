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
                                    <a id="print" title="Invoice Print" target="_blank"
                                        href="{{ url('admin/investments/invoice') . '/' . $data->id }}"
                                        class="btn btn-success text-white"><i class="fa fa-print"></i> Invoice</a>
                                </div>
                            </div>
                            <div class="row">
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
													<a href="{{imageexist($data->screenshot)}}" target="_blank"><img class="img-fluid" style="height: 300px; width:auto"  src="{{imageexist($data->screenshot)}}" alt=""></a>
												</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
