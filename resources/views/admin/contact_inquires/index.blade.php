@extends('admin.layouts.main')
@section('header_scripts')
    <link href="{{ CSS }}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>E-Mail</th>
                                        <th class="actionwidth">Inquiry On</th>
                                        <th class="actionwidth">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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

    <!-- Modal view -->
    <div class="modal fade" id="inquiryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0 fw-bold">Inquiry Details</h4>
                </div>
                <div class="modal-body" id="inquiryContent"></div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script type="text/javascript">
        var tableObj;
        $(document).ready(function() {
            tableObj = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                cache: true,
                bLengthChange: false,
                type: "GET",
                ajax: "{{ url('admin/contact_inquires') }}",
                order: [
                    [3, 'desc']
                ],
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'created_at',
                        orderable: true,
                        render: function(data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return moment(data).format('DD-MM-YYYY');
                            }
                            return data;
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
        });

        function callModal(id) {
            $.ajax({
                method: "GET",
                url: "{{ url('admin/contact_inquires') }}/" + id,
                success: (result) => {
                    $('#inquiryContent').html(result);
                    $('#inquiryModal').modal('show');
                }
            })
        }

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
                            url: "contact_inquires/" + id,
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
