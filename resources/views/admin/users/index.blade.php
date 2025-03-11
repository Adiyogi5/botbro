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
                            <div class="me-auto row">
                                <div class="col-md-7">
                                    <small class="fw-bold" for="is_approved">Membership Status</small>
                                    <select class="form-select" id="is_approved" name="is_approved">
                                        <option value="">All</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <small class="fw-bold" for="is_agent_allow">Bedge Level</small>
                                    <select class="form-select" id="is_agent_allow" name="is_agent_allow">
                                        <option value="">All</option>
                                        <option value="0">Member</option>
                                        <option value="1">Agent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-title pull-right">
                                <a href="{{ route('admin.customer.create') }}" class="btn bg-success"><i
                                        class="fa fa-plus"></i> Add Customer</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Transaction Id</th>
                                        <th>Badge</th>
                                        <th>Membership Stutus</th>
                                        <th>Registered On</th>
                                        <th class="statuswidth">Status</th>
                                        <th class="actionwidth">Action</th>
                                    </tr>
                                </thead>

                            </table>
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
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                }
            });
            tableObj = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                cache: true,
                bLengthChange: false,
                pageLength: 50,
                ajax: {
                    url: "{{ route('admin.customer.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.is_approved = $('#is_approved').val();
                        d.is_agent_allow = $('#is_agent_allow').val();
                    }
                },
                order: [
                    [0, "asc"]
                ],
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'transaction_id'
                    },
                    {
                        data: 'badge_level'
                    },
                    {
                        data: 'is_approved'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'status',
                        'searchable': false,
                        'orderable': false
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
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function() {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    });
                }
            });
            $('#is_approved, #is_agent_allow').on('change', function() {
                tableObj.draw();
            });
        });

        /// Change Approve Status
        $(document).on('click', '.approve-membership', function() {
            var userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to approve membership for this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.approve.membership') }}", // Route for approving membership
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Include CSRF token
                        },
                        data: {
                            user_id: userId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Approved!',
                                    'Membership has been approved successfully.',
                                    'success'
                                );
                                $('.datatable').DataTable().ajax.reload(null,
                                    false); // Reload table data
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    'Failed to approve membership. Please try again.',
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
        $(document).on('click', '.reject-membership', function() {
            var userId = $(this).data('id');

            // Show SweetAlert confirmation
            Swal.fire({
                title: 'Do you want to reject this user\'s membership?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request to reject membership
                    $.ajax({
                        url: "{{ route('admin.reject.membership') }}", // Replace with your reject membership route
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // CSRF token
                        },
                        data: {
                            user_id: userId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Rejected!', 'The membership has been rejected.',
                                    'success');
                                $('.datatable').DataTable().ajax.reload(null,
                                false); // Reload table
                            } else {
                                Swal.fire('Failed!',
                                    'There was an error rejecting the membership.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!',
                                'An error occurred while rejecting the membership.', 'error'
                                );
                        }
                    });
                }
            });
        });

        /// Change Stats
        $("body").on("change", ".tgl_checkbox", function() {
            $.post("{{ route('admin.customer.status') }}", {
                    _token: "{{ csrf_token() }}",
                    id: $(this).data('id'),
                    status: $(this).is(':checked') == true ? 1 : 0
                },
                function(data) {
                    toastr.success('Status Changed Successfully');
                });
        });

        /// Delete Record
        $("body").on("click", ".delete_record", function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.customer.destroy', [':id']) }}";
            url = url.replace(':id', id);
            if (id !== null) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "DELETE",
                            url: url,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: (result) => {
                                if (result != '0') {
                                    Swal.fire('Deleted!', 'Data has been deleted successfully.',
                                        'success');
                                    tableObj.ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error!', 'Failed to delete data', 'error');
                                }
                            },
                            error: (error) => {
                                Swal.fire('Error!', 'Failed to delete data', 'error')
                            }
                        })
                    }
                })
            }
        });
    </script>
@endsection()
