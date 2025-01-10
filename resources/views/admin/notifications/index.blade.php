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
                  <a href="{{url('admin/notifications/create')}}" class="btn bg-success"><i class="fa fa-paper-plane"></i> Send NotifiCation</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr> 
                      <th>Title</th>
                      <th>Message</th>
                      <th>Attachment</th>
                      <th>Created Date</th>
                      <th class="actionwidth">Action</th>
                    </tr>
                  </thead>
                   <tfoot>
                    <tr> 
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
          ajax: '{{ url("admin/notifications") }}',
          order: [[ 0, "asc" ]],   
          columns   : [ 
            { data : 'title' },
            { data : 'message' },
            { data : 'attachment' },
            { data : 'created_at'},
            { data : 'action', 'searchable':false, 'orderable':false },
          ],
          initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).addClass('form-control form-control-sm');
                $(input).appendTo($(column.footer()).empty())
                .on('change', function () {
                    column.search($(this).val(), false, false, true).draw();
                });
            });
          }
      });  
  }); 
 
  /// Delete Record
  $("body").on("click",".delete_record",function(){ 
    var id = $(this).data('id');
    if(id!==null) {
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
            url: "newsletters/"+id,
            data: {"_token": "{{ csrf_token() }}"},
            success: (result) => {
              if(result!='0') {
                Swal.fire('Deleted!','Data has been deleted successfully.','success');
                tableObj.ajax.reload(null, false);
              } else {
                Swal.fire('Error!','Failed to delete data','error');
              } 
            },
            error: (error) => {
                Swal.fire('Error!','Failed to delete data','error')
            }
          })
        }
      })
    }
  });


  $("body").on("click",".memberData",function(){ 
    var id = $(this).attr('id');
    var vdata = { 
      "_token": "{{ csrf_token() }}",
      'id': id,
      }; 
      $.ajax({
          type: "POST",
          datatype: "html",
          url: "{{url('admin/notifications/getusersdata') }}",
          data: vdata,
          beforeSend: function() {
            $('#myModal').show();
          },
          success: function (data) {
            $('#ModelData').html(data);
          },
      });
     
  });

</script>
@endsection()