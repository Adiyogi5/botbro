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
              <div class="row m-0 card-header d-flex justify-content-start">
								<div class="col-md-9 align-self-end">
								<div class="row m-0">	
									<div class="col-md-3">
										<small class="fw-bold" for="date">Reward Date</small>
										<input type="date" name="date" id="date" class="form-control" />
									</div>
									<div class="col-md-3">
										<small class="fw-bold" for="reward_status">Reward Status</small>
										<select class="form-select" id="reward_status" name="reward_status">
											<option value="">Select Status</option>
										 	<option value="'0'">Pending</option>
										 	<option value="1">Approved</option>
										 	<option value="2">Rejected</option>
									  	</select>
									</div>
								</div>
								</div>
								
						    </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-striped datatable">
                  <thead>
                      <tr>
                          <th>Customer Name</th>
                          <th>Reward</th>
                          <th>Date</th>
                          <th>Purchase Count</th>
                          <th>Particulars</th>
                          <th>Status</th>
                      </tr>
                  </thead>
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
          ajax: {
		        url 		: "{{ route('admin.rewards.index') }}",
		        type 		: "GET",
		        data: function (d) {
		          	d.date = $('#date').val();
		          	d.reward_status = $('#reward_status').val();
		        }
		      },
          order: [[ 2, "asc" ]],   
          columns: [
              {data: 'customer_name'},
              {data: 'reward'},
              {data: 'date'},
              {data: 'purchase_count'},
              {data: 'particulars'},
              {data: 'reward_status'},
              
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
      $('#date').on('change', function() {
        $('.datatable').DataTable().draw(true);
      });

      $('#reward_status').on('change', function() {
        $('.datatable').DataTable().draw(true);
      });
  }); 

  $(document).on('click', '.approve_btn', function(e){
        e.preventDefault();
        
        var selector = this;
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) { 
            window.location.href = $(selector).attr('href');
            }
        })

    });

    $(document).on('click', '.reject_btn', function(e){
        e.preventDefault();
        
        var selector = this;
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) { 
            window.location.href = $(selector).attr('href');
            }
        })

    });
 
</script>
@endsection()