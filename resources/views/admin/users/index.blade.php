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
                            <div class="col-md-6">
                            <small class="fw-bold" for="purchase_status">Purchase Status</small>
                            <select class="form-select" id="purchase_status" name="purchase_status">
                                <option value="">All</option>
                                <option value="0">Only Registration</option>
                                <option value="1">Delivery Successfully</option>
                                <option value="2">Purchased (Under Return Days) </option>
                            </select>
                            </div>
                            <div class="col-md-6">
                            <small class="fw-bold" for="is_agent_allow">Customer</small>
                            <select class="form-select" id="is_agent_allow" name="is_agent_allow">
                                <option value="">All</option>
                                <option value="1">Agent</option>
                                <option value="0">Member</option> 
                            </select>
                            </div>
                        </div>
                        <div class="card-title pull-right">
                            <a href="{{ route('admin.customer.create') }}" class="btn bg-success"><i class="fa fa-plus"></i> Add Customer</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Badge</th>
                                    <th>Purchase Stutus</th>
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
            ajax:{ 
                url 		:"{{ route('admin.customer.index') }}",
                type 		:"GET",
                data: function (d) {
		          	d.purchase_status = $('#purchase_status').val();
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
                    data: 'badge_level'
                },
                {
                    data: 'purchase_status'
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
        $('#purchase_status').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
        $('#is_agent_allow').on('change', function() {
            $('.datatable').DataTable().draw(true);
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