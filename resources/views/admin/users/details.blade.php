@extends('admin.layouts.main')
@section('header_scripts')
<link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <h4 class="me-auto">{{$data->name}}</h4>
                        <div class="card-title pull-right">
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-success"><i class="fa fa-list"></i> Customer Lists</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Profile</button>
                            {{-- </li>
                            <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Badge Level</button>
                            </li> --}}
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-refers-tab" data-bs-toggle="pill" data-bs-target="#pills-refers" type="button" role="tab" aria-controls="pills-refers" aria-selected="false">Refers</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-investments-tab" data-bs-toggle="pill" data-bs-target="#pills-investments" type="button" role="tab" aria-controls="pills-investments" aria-selected="false">Investments</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-wallet-tab" data-bs-toggle="pill" data-bs-target="#pills-wallet" type="button" role="tab" aria-controls="pills-wallet" aria-selected="false">Wallet</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-withdraw-tab" data-bs-toggle="pill" data-bs-target="#pills-withdraw" type="button" role="tab" aria-controls="pills-withdraw" aria-selected="false">Investment Withdrawal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-Referral-tab" data-bs-toggle="pill" data-bs-target="#pills-Referral" type="button" role="tab" aria-controls="pills-Referral" aria-selected="false">Referral Withdrawal</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-address-tab" data-bs-toggle="pill" data-bs-target="#pills-address" type="button" role="tab" aria-controls="pills-address" aria-selected="false">Address</button>
                            </li>
                            {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-rewards-tab" data-bs-toggle="pill" data-bs-target="#pills-rewards" type="button" role="tab" aria-controls="pills-rewards" aria-selected="false">Rewards</button>
                            </li>
                            <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profit_sharing-tab" data-bs-toggle="pill" data-bs-target="#pills-profit_sharing" type="button" role="tab" aria-controls="pills-profit_sharing" aria-selected="false">Profit Sharing</button>
                            </li> --}}
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row" style="line-height: 30px;">
                                    <div class="col-md-12" style="text-align: center;"> 
                                      <p><img src="{{ get_image($data->image, 'map') }}" class="logosmallimg"></p>   
                                       
                                    </div>
                                    <div class="col-md-4"><b>Name:&nbsp; </b> <?=$data['name'].' '.$data['lname'];?></div>
                                    <div class="col-md-4"><b>Mobile:&nbsp; </b> <?=!empty($data['mobile'])?$data['mobile']:'--';?></div>
                                    <div class="col-md-4"><b>Email:&nbsp; </b> <?=!empty($data['email'])?$data['email']:'--';?></div> 
                                    
                                    <div class="col-md-4"><b>Refer Code:&nbsp; </b>  <?=isset($data['reffer_code'])?$data['reffer_code']:'';?></div>
                                    <div class="col-md-4"><b>Wallet Balance:&nbsp; </b> <?=!empty($data['user_balance'])?$data['user_balance']:0;?></div>
                                    <div class="col-md-4"><b>Purchase Status:&nbsp; </b> <?php 
                                        if($data['purchase_status']==0){
                                            echo "Not Purchased" ;
                                        }else{
                                            echo  "Purchased";
                                        }
                                     ?></div>
                                    <div class="col-md-4"><b>Register At:&nbsp; </b> <?=date('d-m-Y',strtotime($data['created_at']));?></div>
                                  </div>

                            </div>

                            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <table class="table table-bordered table-striped datatable" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Badge Level</th>
                                            <th>Date</th>
                                            <th>Purchase Required</th>
                                            <th>Particulars</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-refers" role="tabpanel" aria-labelledby="pills-refers-tab">
                                <table class="table table-bordered table-striped datatable_refer" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Refer Name</th>
                                            <th>Refrral Name</th>
                                            <th>Refer Code</th>
                                            <th>Date</th>
                                            
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-investments" role="tabpanel" aria-labelledby="pills-investments-tab">
                                <table class="table table-bordered table-striped datatable_investments" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Invest No.</th>
                                            <th>Customer Name</th>
                                            <th>Invest Amount</th>
                                            <th>Payment Status</th>
                                            <th>Payment Date</th>
                                            
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-wallet" role="tabpanel" aria-labelledby="pills-wallet-tab">
                                <table class="table table-bordered table-striped datatable_wallet" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Voucher No.</th>
                                            <th>Customer Name</th>
                                            <th>Particulars</th>
                                            <th>Payment Type</th>
                                            <th>Amount</th>                             
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-withdraw" role="tabpanel" aria-labelledby="pills-withdraw-tab">
                                <table class="table table-bordered table-striped datatable_withdraw" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Voucher No.</th>
                                            <th>Customer Name</th>
                                            <th>Invest No.</th>
                                            <th>Current Balance</th>
                                            <th>Request Date</th>
                                            <th>Request Amount</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-Referral" role="tabpanel" aria-labelledby="pills-Referral-tab">
                                <table class="table table-bordered table-striped datatable_Referral" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Voucher No.</th>
                                            <th>Customer Name</th>
                                            <th>Current Balance</th>
                                            <th>Request Date</th>
                                            <th>Request Amount</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-address" role="tabpanel" aria-labelledby="pills-address-tab">
                                <table class="table table-bordered table-striped datatable_address" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Address 1</th>
                                            <th>Address 2</th>
                                            <th>Postcode</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Default</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-rewards" role="tabpanel" aria-labelledby="pills-rewards-tab">
                                <table class="table table-bordered table-striped datatable_rewards" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Reward</th>
                                            <th>Date</th>
                                            <th>Purchase Count</th>
                                            <th>Particulars</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="pills-profit_sharing" role="tabpanel" aria-labelledby="pills-profit_sharing-tab">
                                <table class="table table-bordered table-striped datatable_profit_sharing" style="width:100% !important">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Created By</th>
                                            <th>Particulars</th>
                                        </tr>
                                    </thead>
                                </table>
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
<script type="text/javascript">
    var tableObj;
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_badges') }}",
            order: [
                [2, "desc"]
            ],
            columns: [
                {data: 'user_name'},
                {data: 'badge_level'},
                {data: 'date'},
                {data: 'purchase_count'},
                {data: 'particulars','searchable': false,'orderable': false},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_refer').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_refer') }}",
            order: [
                [3, "desc"]
            ],
            columns: [
                {data: 'refrer_name'},
                {data: 'user_refrral'},
                {data: 'ref_code'},
                {data: 'created_at'},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_investments').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_investments') }}",
            order: [
                [4, "desc"]
            ],
            columns: [
                {data: 'invest_no'},
                {data: 'customer_name'},
                {data: 'invest_amount'},
                {data: 'payment_status'},
                {data: 'date'},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_wallet').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_wallet') }}",
            order: [
                [5, "desc"]
            ],
            columns: [
                {data: 'voucher_no'},
                {data: 'customer_name'},
                {data: 'particulars'},
                {data: 'payment_type'},
                {data: 'amount'},                
                {data: 'date'},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_withdraw').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_withdraw') }}",
            order: [
                [4, "desc"]
            ],
            columns: [
                {data: 'reference_id'},
                {data: 'customer_name'},
                {data: 'invest_no'},
                {data: 'balance'},
                {data: 'request_date'},
                {data: 'amount'},
                {data: 'payment_method'},
                {data: 'status','searchable': false,'orderable': false},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_Referral').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user-referral-withdraw') }}",
            order: [
                [4, "desc"]
            ],
            columns: [
                {data: 'reference_id'},
                {data: 'customer_name'},
                {data: 'user_reffer_balance'},
                {data: 'request_date'},
                {data: 'request_amount'},
                {data: 'payment_method'},
                {data: 'status','searchable': false,'orderable': false},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).on('click', '.approve_btn', function(e){
        e.preventDefault();
        
        var selector = this;
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) { 
            window.location.href = $(selector).attr('href');
            }
        })

    });

    $(document).on('click', '.reject_btn', function(e){
        e.preventDefault();
        
        var selector = this;
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) { 
            window.location.href = $(selector).attr('href');
            }
        })

    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_address').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_address') }}",
            order: [
                [0, "asc"]
            ],
            columns: [
                {data: 'name'},
                {data: 'address_1'},
                {data: 'address_2'},
                {data: 'postcode'},
                {data: 'country'},
                {data: 'state'},
                {data: 'city'},
                {data: 'default_id','searchable': false,'orderable': false},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_rewards').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_rewards') }}",
            order: [
                [2, "desc"]
            ],
            columns: [
                {data: 'customer_name'},
                {data: 'reward'},
                {data: 'date'},
                {data: 'purchase_count'},
                {data: 'particulars'},
                {data: 'reward_status'},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableObj = $('.datatable_profit_sharing').DataTable({
            processing: true,
            serverSide: false,
            cache: true,
            bLengthChange: false,
            type: 'GET',
            ajax: "{{ url('admin/customer/'.$id.'/user_profit_sharing') }}",
            order: [
                [1, "desc"]
            ],
            columns: [
                {data: 'customer_name'},
                {data: 'date'},
                {data: 'amount'},
                {data: 'created_by'},
                {data: 'particulars'},
                
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).addClass('form-control form-control-sm');
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                });
            }
        });
    });
</script>
@endsection