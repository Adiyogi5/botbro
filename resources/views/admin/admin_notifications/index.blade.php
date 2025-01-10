@extends('admin.layouts.main')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped datatable">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Date</th>
                  <th>Message</th>
                  <th>Is Read</th>
                  <th class="actionwidth">Action</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td></td>
                  <td></td>
                  <td class="non_searchable"></td>
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

  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Notification Details</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="ModelData"></div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('footer_scripts')
<script type="text/javascript">
  var table;
  $(document).ready(function() {
    table = $('.datatable').DataTable({
      processing: true,
      serverSide: true,
      cache: true,
      bLengthChange: false,
      type: 'GET',
      ajax: "{{ route('admin.admin_notifications.index') }}",
      order: [
        [1, "desc"]
      ],
      columns: [{
          data: 'title'
        },
        {
          data: 'created_at'
        },
        {
          data: 'message'
        },
        {
          data: 'is_read',
          'searchable': false,
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

  $("body").on("click", ".details", function() {
    var id = $(this).attr('id');
    var url = "{{ route('admin.admin_notifications.show', [':id']) }}";
    url = url.replace(':id', id)
    $.ajax({
      type: "GET",
      datatype: "html",
      url: url,
      beforeSend: function() {
        $('#myModal').show();
      },
      success: function(data) {
        $('#ModelData').html(data);
        table.draw();
      },
    });
  });
</script>
@endsection()