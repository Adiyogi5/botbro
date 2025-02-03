@extends('admin.layouts.main')
@section('header_scripts')
    <link rel="stylesheet" type="text/css" href="{{ CSS }}ajax-datatables.css" />
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="row m-0 card-header d-flex justify-content-end">
                            <div class="col-md-12 px-0 align-self-start">
                                <!-- <a href="javascript:void(0);" class="btn bg-info exportshipprice" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> investID's & Shipping Price(Bulk)</a>
           &nbsp;&nbsp;
           <a href="javascript:void(0);" class="btn bg-primary exportinvestsap" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Export invests With Sap No.(Bulk)</a>
           &nbsp;&nbsp;
           <a href="javascript:void(0);" class="btn bg-warning exportshipprint" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Print Shipping Label</a>
           &nbsp;&nbsp;
           <a href="{{ url('investment_invoice') }}" class="btn bg-success uploadinvestsap" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Upload Invoice(Bulk)</a>  -->
                            </div>
                        </div>
                        <div class="card">
                            <div class="row m-0 card-header d-flex justify-content-end" style="padding:3px;">
                                <div class="col-md-8 align-self-end">
                                    <div class="row m-0">
                                        <div class="col-md-3">
                                            <small class="fw-bold" for="date">Date</small>
                                            <input type="date" name="date" id="date" class="form-control" />
                                        </div>
                                        {{-- <div class="col-md-4">
											<small class="fw-bold" for="payment_status">Payment Status</small>
											<select class="form-select" id="payment_status" name="payment_status">
												<option value="">All</option>
											 	<option value="2">Online Paid</option>
											 	<option value="3">Online Unpaid</option>
										  	</select>
										</div> --}}
                                        <div class="col-md-3">
                                            <small class="fw-bold" for="investment_paystatus">Payment Status</small>
                                            <select class="form-select" id="investment_paystatus"
                                                name="investment_paystatus">
                                                <option value="">All</option>
                                                <option value="2">Paid</option>
                                                <option value="3">Unpaid</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0 align-self-end">
                                    <!-- <a href="javascript:void(0);" class="btn bg-primary export" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Export invests</a>
                      &nbsp;&nbsp;
             <a href="{{ url('admin/investment/create') }}" class="btn bg-success float-start" >Add Investment</a> -->
                                </div>

                                <div class="col-12" style="border-top: 1px solid #ccc; margin-top:10px;">
                                    <small><b>NOTE : </b> <b style="color:#FF0000;background-color:#FF0000;">Offline</b>
                                        Rejected,&nbsp; <b style="color:#009900;background-color:#009900;">Offline</b>
                                        Approved,&nbsp; <b style="color:#e7d10b;background-color:#e7d10b;">Offline</b> Pending&nbsp;
                                    </small>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checkAll" id="selectChek"></th>
                                            <th>Invest No</th>
                                            <th class="actionwidth">Customer Name</th>
                                            <th>Invest Amount</th>
                                            <th>Rate Of Intrest</th>
                                            <th class="actionwidth">Payment Status</th>
                                            <th>Invest Date(GMT)</th>
                                            <th>Investment Status</th>
                                            <th style="width:70px">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="non_searchable"></th>
                                            <th></th>
                                            <th></th>
                                            <th class="non_searchable"></th>
                                            <th class="non_searchable"></th>
                                            <th class="non_searchable"></th>
                                            <th class="non_searchable"></th>
                                            <th class="non_searchable"></th>
                                        </tr>
                                    </tfoot>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <form id="investxls" method="POST" action="">
        @csrf
        <input type="hidden" name="invest_arr" id="invest_arr">
    </form>
@endsection

@section('footer_scripts')
    <script type="text/javascript">
        var tableObj;
        $(document).ready(function() {
            tableObj = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                cache: true,
                bLengthChange: false,
                pageLength: 50,
                ajax: {
                    url: "{{ url('admin/investments') }}",
                    type: "GET",
                    data: function(d) {
                        d.date = $('#date').val();
                        d.payment_status = $('#payment_status').val();
                        d.investment_paystatus = $('#investment_paystatus').val();
                    }
                },
                order: [
                    [7, 'desc']
                ],
                columns: [{
                        data: 'investmentcheck',
                        'searchable': false,
                        'orderable': false
                    },
                    {
                        data: 'invest_no'
                    },
                    {
                        data: 'customer_name'
                    },
                    {
                        data: 'invest_amount'
                    },
					{
                        data: 'rate_of_intrest'
                    },
                    {
                        data: 'payment_status'
                    },
                    {
                        data: 'is_approved'
                    },
                    {
                        name: 'date',
                        data: {
                            _: 'date.display',
                            sort: 'date.timestamp'
                        }
                    },
                    {
                        data: 'action',
                        'searchable': false,
                        'orderable': false
                    },
                ],
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).addClass('form-control form-control-sm');
                        $(input).appendTo($(column.footer()).empty()).on('change', function() {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }
            });

            $('#date').on('change', function() {
                $('.datatable').DataTable().draw(true);
            });
            $('#payment_status').on('change', function() {
                $('.datatable').DataTable().draw(true);
            });
            $('#investment_paystatus').on('change', function() {
                $('.datatable').DataTable().draw(true);
            });
        });

        /// Change Approve Status
        $(document).on('click', '.approve-investment', function() {
            var investId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve investment for this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.approve.investment') }}", // Route for approving investment
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        data: {
                            id: investId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Approved!',
                                    'Investment has been approved successfully.',
                                    'success'
                                );
                                $('.datatable').DataTable().ajax.reload(null,
                                    false); // Reload table data
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to approve investment. Please try again.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'An error occurred. Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        /// Change Reject Status
        $(document).on('click', '.reject-investment', function() {
            var investId = $(this).data('id');

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Do you want to reject this user\'s investment?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request to reject Investment
                    $.ajax({
                        url: "{{ route('admin.reject.investment') }}", // Replace with your reject Investment route
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // CSRF token
                        },
                        data: {
                            id: investId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Rejected!', 'The Investment has been rejected.',
                                    'success');
                                $('.datatable').DataTable().ajax.reload(null,
                                    false); // Reload table
                            } else {
                                Swal.fire('Failed!',
                                    'There was an error rejecting the Investment.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!',
                                'An error occurred while rejecting the Investment.', 'error'
                            );
                        }
                    });
                }
            });
        });

        $('body').on('click', '.export', function(e) {
            e.preventDefault();
            var date = $('#date').val();
            var payment_status = $('#payment_status').val();
            var investment_paystatus = $('#investment_paystatus').val();
            window.location.href = "invests/invests-export?date=" + date + "&payment_status=" + payment_status +
                "&investment_paystatus=" + investment_paystatus + "";
        });

        $('body').on('click', '.checkAll', function(e) {
            if ($(this).is(':checked')) {
                $('.multicheck').prop('checked', true);
            } else {
                $('.multicheck').prop('checked', false);
            }
        });


        $('body').on('click', '.exportinvestsap', function(e) {
            var investids_arr = [];
            // Read all checked checkboxes
            $("input:checkbox[class=multicheck]:checked").each(function() {
                investids_arr.push($(this).val());
            });
            // Check checkbox checked or not
            if (investids_arr.length > 0) {
                $('#invest_arr').val(investids_arr);
                $('#investxls').attr('action', "exportinvestsap")
                $('#investxls').submit();

            } else {
                alert("Please Selcet Record?");
            }
        });


        $('body').on('click', '.exportshipprint', function(e) {
            var investids_arr = [];
            // Read all checked checkboxes
            $("input:checkbox[class=multicheck]:checked").each(function() {
                investids_arr.push($(this).val());
            });
            // Check checkbox checked or not
            if (investids_arr.length > 0) {
                $('#invest_arr').val(investids_arr);
                $('#investxls').attr('action', "exportshipprint")
                $('#investxls').submit();

            } else {
                alert("Please Selcet Record?");
            }
        });


        $('body').on('click', '.delete_record', function() {
            var id = $(this).attr('data-id');
            if (id != null) {
                Swal.fire({
                    title: "Are you sure ?",
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Yes, Delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "DELETE",
                            url: "admin/invests/" + id,
                            data: {
                                '_token': "{{ csrf_token() }}"
                            },
                            success: (result) => {
                                if (result != 0) {
                                    Swal.fire('Deleted!', 'Data has been deleted successfully',
                                        'success');
                                    tableObj.ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error!', 'Failed to delete data', 'error');
                                }
                            }
                        })
                    }
                })
            }
        })
    </script>
@endsection
