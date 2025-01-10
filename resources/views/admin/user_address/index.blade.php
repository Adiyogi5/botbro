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
                        <div class="card-title pull-right">
                            <a href="{{ route('admin.address.create', ['user_id' => $user->id]) }}" class="btn bg-success"><i class="fa fa-plus"></i> Add Customer Address</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Address</th>
                                    <th class="statuswidth">Default</th>
                                    <th class="actionwidth">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="non_searchable"></td>
                                    <td class="non_searchable"></td>
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
            serverSide: true,
            cache: true,
            bLengthChange: false,
            pageLength: 50,
            type: 'GET',
            ajax: "{{ route('admin.address.index', ['user_id' => $user->id]) }}",
            order: [
                [0, "asc"]
            ],
            columns: [{
                    data: 'country_name',
                    name: 'countries.name'
                },
                {
                    data: 'state_name',
                    name: 'states.name'
                },
                {
                    data: 'city_name',
                    name: 'cities.name'
                },
                {
                    data: 'address_1'
                },
                {
                    data: 'default_id',
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
    });

    /// Change Stats
    $("body").on("change", ".tgl_checkbox", function() {
        $.post("{{ route('admin.address.status', ['user_id' => $user->id]) }}", {
                _token: "{{ csrf_token() }}",
                id: $(this).data('id'),
                status: $(this).is(':checked') == true ? 1 : 0
            },
            function(data) {
                toastr.success('Default Address Changed Successfully');
                tableObj.ajax.reload(null, false);
            });
    });

    /// Delete Record
    $("body").on("click", ".delete_record", function() {
        var id = $(this).data('id');
        var url = "{{ route('admin.address.destroy', [':id', 'user_id' => $user->id]) }}";
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