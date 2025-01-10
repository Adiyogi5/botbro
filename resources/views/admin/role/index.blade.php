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
                  <a href="{{ url('admin/role/create')}}" class="btn bg-success"><i class="fa fa-plus"></i> Add Role</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped datatable">
                  <thead>
                    <tr> 
                      <th>Role</th>
                      <th class="statuswidth">Status</th>
                      <th class="actionwidth">Action</th>
                    </tr>
                  </thead>
                   <tfoot>
                    <tr> 
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
          ajax: '{{ url("admin/role") }}',
          order: [[ 0, "asc" ]],   
          columns   : [ 
            { data : 'name' },
            { data : 'status','searchable':false, 'orderable':false },
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
  /// Change Stats
  $("body").on("change",".tgl_checkbox",function(){ 
    $.post('{{ url("admin/role/status") }}',
    {
      _token:"{{ csrf_token() }}",
      id : $(this).data('id'),
      status : $(this).is(':checked') == true?1:0
    },
    function(data){
      toastr.success('Status Changed Successfully');
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
            url: "role/"+id,
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
</script>
@endsection()