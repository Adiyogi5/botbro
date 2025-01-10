@extends('admin.layouts.main')
@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}ajax-datatables.css" />
@stop
@section('content')
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card">
						<div class="row m-0 card-header d-flex justify-content-end">
								<div class="col-md-9 align-self-end">
								<div class="row m-0">	
									<div class="col-md-3">
										<small class="fw-bold" for="created_at">Return/Replace Date</small>
										<input type="date" name="created_at" id="created_at" class="form-control" />
									</div>
									<div class="col-md-3">
										<small class="fw-bold" for="return_status">Return/Replace Status</small>
										<select class="form-select" id="return_status" name="return_status">
											<option value="">All</option>
											@foreach($returnstatus as $key => $value)
										 	<option value="{{$key}}">{{$value}}</option>
										 	@endforeach
									  	</select>
									</div>
									<div class="col-md-3">
										<small class="fw-bold" for="return_type">Type</small>
										<select class="form-select" id="return_type" name="return_type">
											<option value="">All</option>
										 	<option value="1">Return</option>
										 	<option value="2">Replace</option>
									  	</select>
									</div>
								</div>
								</div>
								<div class="col-md-3 px-0 align-self-end">
									<!-- <a href="javascript:void(0);" class="btn bg-primary export" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Export Returns</a> -->
                  					  
								</div> 
						</div>
						<div class="card-body">
							<table class="table table-bordered table-striped datatable">
								<thead>
									<tr> 
										<th>Order ID</th>
										<th class="actionwidth">Customer</th> 
										<th>Product Name</th>										
										<th>Status</th>
										<th>Action</th> 
										<th>Date</th> 
										<th style="width:70px">Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th></th>
										<th></th>
										<th></th>
										<th class="non_searchable"></th>
										<th class="non_searchable"></th>										
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
	<form id="orderxls" method="POST" action="">
        @csrf
		<input type="hidden" name="order_arr" id="order_arr">
	</form>
@endsection

@section('footer_scripts')
<script type="text/javascript">
	var tableObj;
	$(document).ready(function() {
		tableObj = $('.datatable').DataTable({
			processing		: true,
			serverSide		: true,
			cache 			: true,
			bLengthChange	: false,
			pageLength		: 50,
			ajax: {
		        url 		: "{{ url('admin/returns') }}",
		        type 		: "GET",
		        data: function (d) {
		          	d.created_at = $('#created_at').val();
		          	d.return_status = $('#return_status').val();
		          	d.return_type = $('#return_type').val();
		        }
		    },
			order 			: [[5, 'desc']],
			columns			: [
				{ data: 'order_no'},
				{ data: 'customer_name'},	
				{ data: 'product_name'},	
					
				{ data: 'return_status_id'},				
				{ data: 'return_action_id'}, 
				{ data: 'created_at'}, 
				{ data: 'action', 'searchable': false, 'orderable': false},
			], 
			initComplete: function () {
				this.api().columns().every(function () {
					var column = this;
					var input = document.createElement("input");
					$(input).addClass('form-control form-control-sm');
					$(input).appendTo($(column.footer()).empty()).on('change', function () {
						column.search($(this).val(), false, false, true).draw();
					});
				});
			}
		});

		$('#created_at').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
		$('#return_status').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
		$('#return_type').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
	});

	$('body').on('click','.export',function(e) {
		e.preventDefault();
		var created_at = $('#created_at').val();
		var return_status = $('#return_status').val();  
		var return_type = $('#return_type').val();  
		window.location.href="returns-export?created_at="+created_at+"&return_status="+return_status+"&return_type="+return_type+"" ;
	});	 
  
</script>
@endsection