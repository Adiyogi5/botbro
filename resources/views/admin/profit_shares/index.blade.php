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
          <div class="card-header d-flex justify-content-end">
            <div class="card-title pull-right">
              <a href="{{ route('admin.profit_shares.create') }}" class="btn bg-success"><i class="fa fa-paper-plane"></i> Share Profit</a>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped datatable">
              <thead>
                <tr>
                  <th>User Name</th>
                  <th>Amount</th>
                  <th>Admin Name</th>
                  <th>Sharing Date</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
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
      type: 'GET',
      ajax: '{{ route("admin.profit_shares.index") }}',
      order: [
        [3, "desc"]
      ],
      columns: [{
          data: 'user_name',
          name: 'users.name'
        },
        {
          data: 'amount'
        },
        {
          data: 'admin_name',
          name: 'admins.name'
        },
        {
          data: 'date'
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
</script>
@endsection()