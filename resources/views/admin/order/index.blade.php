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
						<div class="row m-0 card-header d-flex justify-content-end"> 	 	<div class="col-md-12 px-0 align-self-start">
							<!-- <a href="javascript:void(0);" class="btn bg-info exportshipprice" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> OrderID's & Shipping Price(Bulk)</a>
							&nbsp;&nbsp; 
							<a href="javascript:void(0);" class="btn bg-primary exportordersap" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Export Orders With Sap No.(Bulk)</a> 
							&nbsp;&nbsp; 
							<a href="javascript:void(0);" class="btn bg-warning exportshipprint" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Print Shipping Label</a> 
							&nbsp;&nbsp; 
							<a href="{{url('order_invoice')}}" class="btn bg-success uploadordersap" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Upload Invoice(Bulk)</a>  -->
						</div>  
					</div>
					<div class="card">
						<div class="row m-0 card-header d-flex justify-content-end" style="padding:3px;">
								<div class="col-md-8 align-self-end">
									<div class="row m-0">	
										<div class="col-md-3">
											<small class="fw-bold" for="order_date">Ordered Date</small>
											<input type="date" name="order_date" id="order_date" class="form-control" />
										</div>
										<div class="col-md-4">
											<small class="fw-bold" for="order_status">Order Status</small>
											<select class="form-select" id="order_status" name="order_status">
												<option value="">All</option>
												@foreach($order_status as $value)
											 	<option value="{{$value->id}}">{{$value->name}}</option>
											 	@endforeach
										  	</select>
										</div>
										<div class="col-md-3">
											<small class="fw-bold" for="order_paystatus">Payment Status</small>
											<select class="form-select" id="order_paystatus" name="order_paystatus">
												<option value="">All</option>
											 	<option value="2">Online Paid</option>
											 	<option value="3">Online Unpaid</option>
										  	</select>
										</div>
									</div>
								</div>
								<div class="col-md-4 px-0 align-self-end">
									<!-- <a href="javascript:void(0);" class="btn bg-primary export" style="margin-left: 8px;"><i class="fa fa-file-alt"></i> Export orders</a>
                  					 &nbsp;&nbsp; 
									<a href="{{ url('admin/orders/create') }}" class="btn bg-success float-start" >Add Order</a> -->
								</div> 

								<div class="col-12" style="border-top: 1px solid #ccc; margin-top:10px;">
									<small><b>NOTE : </b> <b style="color:#FF0000;background-color:#FF0000;">Cod</b> Cancelled,&nbsp; <b style="color:#009900;background-color:#009900;">Cod</b> Paid,&nbsp; <b style="color:#FF6600;background-color:#FF6600;">Cod</b> Unpaid&nbsp; </small>
								</div>
						</div>

						<div class="card-body">
							<table class="table table-bordered table-striped datatable">
								<thead>
									<tr>
										<th><input type="checkbox" class="checkAll" id="selectChek"></th>
										<th>Order ID</th>
										<th class="actionwidth">Customer</th> 
										<th>Subtotal</th>
										<th>Shipping</th>
										<th>Discount</th>
										<th>Total</th>
										<th class="actionwidth">Status</th>
										<th>Ordered Date(GMT)</th> 
										<th style="width:70px">Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th class="non_searchable"></th>
										<th></th>
										<th></th>
										<th class="non_searchable"></th>
										<th class="non_searchable"></th>
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
		        url 		: "{{ url('admin/orders') }}",
		        type 		: "GET",
		        data: function (d) {
		          	d.order_date = $('#order_date').val();
		          	d.order_status = $('#order_status').val();
		          	d.order_paystatus = $('#order_paystatus').val();
		        }
		    },
			order 			: [[8, 'desc']],
			columns			: [
				{ data: 'ordercheck', 'searchable': false, 'orderable': false},
				{ data: 'order_no'},
				{ data: 'customer_name'},	
				{ data: 'subtotal'},	
				{ data: 'shipping_amount'},	
				{ data: 'discount'},				
				{ data: 'total'},
				{ data: 'order_status_name'},
				{  name: 'created_at',
                        data: {
                            _: 'created_at.display',
                            sort: 'created_at.timestamp'
                        }}, 
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

		$('#order_date').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
		$('#order_status').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
		$('#order_paystatus').on('change', function() {
			$('.datatable').DataTable().draw(true);
		});
	});

	$('body').on('click','.export',function(e) {
		e.preventDefault();
		var order_date = $('#order_date').val();
		var order_status = $('#order_status').val();  
		var order_paystatus = $('#order_paystatus').val();
		window.location.href="orders/orders-export?order_date="+order_date+"&order_status="+order_status+"&order_paystatus="+order_paystatus+"" ;
	});	 

  	$('body').on('click','.checkAll',function(e){
	 if($(this).is(':checked')){
	       $('.multicheck').prop('checked', true);
	    }else{
	       $('.multicheck').prop('checked', false);
	    }
	});

  	
  	$('body').on('click','.exportordersap',function(e) {
      var orderids_arr = [];
      // Read all checked checkboxes
      $("input:checkbox[class=multicheck]:checked").each(function () {
         orderids_arr.push($(this).val());
      }); 
      // Check checkbox checked or not
      if(orderids_arr.length > 0){  
      		$('#order_arr').val(orderids_arr);
      		$('#orderxls').attr('action',"exportordersap")
      		$('#orderxls').submit();
            
      }else{
        alert("Please Selcet Record?");
      }
   });

  	
  	$('body').on('click','.exportshipprint',function(e) {
      var orderids_arr = [];
      // Read all checked checkboxes
      $("input:checkbox[class=multicheck]:checked").each(function () {
         orderids_arr.push($(this).val());
      }); 
      // Check checkbox checked or not
      if(orderids_arr.length > 0){  
      		$('#order_arr').val(orderids_arr);
      		$('#orderxls').attr('action',"exportshipprint")
      		$('#orderxls').submit();
            
      }else{
        alert("Please Selcet Record?");
      }
   });
  	

	$('body').on('click','.delete_record',function() {
		var id = $(this).attr('data-id');
		if(id!=null) {
			Swal.fire({
				title 				: "Are you sure ?",
				text 				: "You won't be able to revert this!",
				icon 				: 'warning',
				showCancelButton 	: true,
				confirmButtonColor 	: '#3085d6',
				cancelButtonColor 	: '#d33',
				confirmButtonText 	: "Yes, Delete it!",
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method 	: "DELETE",
						url 	: "admin/orders/"+id,
						data 	: {'_token' : "{{ csrf_token() }}"},
						success	: (result) => {
							if (result!=0) {
								Swal.fire('Deleted!','Data has been deleted successfully','success');
								tableObj.ajax.reload(null, false);
							}else {
								Swal.fire('Error!','Failed to delete data','error');
							}
						} 
					})
				}
			})
		}
	})
</script>
@endsection