@extends('admin.layouts.main')
@section('header_scripts')
<link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <div class="card-title pull-right">
                            <a href="{{ route('admin.products.create') }}" class="btn bg-success"><i class="fa fa-plus"></i>
                                Add Product</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Sort Order</th>
                                    <th class="statuswidth">Status</th>
                                    <th class="actionwidth">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td class=""></td>
                                    <td class=""></td>
                                    <td class=""></td>
                                    <td class=""></td>
                                    <td class=""></td>
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
    <!-- /.container-fluid -->
</section>
@endsection

@section('footer_scripts')
<script src="{{ JS }}jquery-ui.min.js"></script>
<script>
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
            iDisplayLength: 50,
            type: 'GET',
            ajax: {
                url: "{{ route('admin.products.index') }}",
                type: 'GET',
                data: function(d) {
                    d.category_1 = $('#category_1').val();
                    d.category_2 = $('#category_2').val();
                    d.category_3 = $('#category_3').val();
                }
            },
            order: [
                [0, "asc"]
            ],
            columns: [{
                    data: 'name',
                    'searchable': true,
                    'orderable': true
                },
                {
                    data: 'model',
                    'searchable': true,
                    'orderable': true
                },
                {
                    data: 'price',
                    'searchable': true,
                    'orderable': true
                },
                {
                    data: 'stock',
                    'searchable': true,
                    'orderable': true
                },
                {
                    data: 'sort_order',
                    'searchable': true,
                    'orderable': true
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

        $('#search').click(function() {
            $('.datatable').DataTable().draw(true);
        });
    });

    /// Change Stats
    $("body").on("change", ".tgl_checkbox", function() {
        $.post("{{ route('admin.products.status') }}", {
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
        var url = "{{ route('admin.products.destroy', [':id']) }}";
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
@endsection