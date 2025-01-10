@extends('admin.layouts.main')
@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}ajax-datatables.css" />
<style type="text/css">
	.returnaction{
	  margin-top: 15px;
	}
</style>
@stop
@section('content')
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card mb-0">
						<div class="card-body">
							 
							<div class="row">
								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary"><i class="fa fa-shopping-cart"></i> Return Details</p>
									</div>
									<div class="card-body p-0">
										<div class="row m-0 p-2 border-bottom">
											<div class="col-3 pr-2"> Order No.:</div>
											<div class="col-9 ">{{ $data->order_no }}</div>
										</div>
										<div class="row m-0 p-2 border-bottom">
											<div class="col-3 pr-2"> Date: </div>
											<div class="col-9 ">{{ date('d-M-Y', strtotime($data->created_at)) }}</div>
										</div>
										
										<div class="row m-0 p-2 border-bottom">
											<div class="col-3 pr-2">Action: </div>
											<div class="col-9 "> 
												Status : {{isset(RETURNSTATUS[$data->return_status_id]) ? RETURNSTATUS[$data->return_status_id] : ''}}  
												<br>
												{{isset(RETURNACTIONS[$data->return_action_id]) ? RETURNACTIONS[$data->return_action_id] : ''}} 
											</div>
										</div> 
									</div>
								</div>
								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary"><i class="fa fa-user"></i> Customer Details</p>
									</div>
									<div class="card-body p-0">
										<div class="row m-0 p-2 border-bottom">
											<div class="col-1 p-2 bg-info text-center rounded fa fa-user"></div>
											<div class="col-11 align-self-end text-capitalize">{{ $data->customer_name }}</div>
										</div> 
										<div class="row m-0 p-2 border-bottom">
											<div class="col-1 p-2 bg-info text-center rounded fa fa-envelope"></div>
											<div class="col-11 align-self-end">{{ $data->customer_email }}</div>
										</div>
										<div class="row m-0 p-2 border-bottom">
											<div class="col-1 p-2 bg-info text-center rounded fa fa-phone"></div>
											<div class="col-11 align-self-end">{{ $data->customer_mobile }}</div>
										</div> 
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary">Return Product Detail</p>
									</div>
									<div class="card-body">
										{{$data->product_name}}<br>  
										{{$data->product_model}}
									</div>
								</div>
							 
								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary">Customer Comment</p>
									</div>
									<div class="card-body">
										{{ $data->comment }}
									</div>
								</div>

								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary">Admin Comment</p>
									</div>
									<div class="card-body">
										{{ ($data->admin_comment)?$data->admin_comment:'--' }}
									</div>
								</div>

							</div>
							
							@if($data->return_status_id < 3)
							<div class="row" id="return_status1">
								<div class="col-md card m-2 p-0">
									<div class="card-header">
										<p class="card-title mb-0 fw-bold text-secondary">Update Return Status</p>
									</div>
									<div class="card-body">
										<form id="orderStatusForm">
	               							<div class="row">
							                    <div class="col-md-8">
							                    	<div class="form-group mb-0">
								                        <label for="name">Return Status Comment</label>
								                        <textarea id="return_comment" name="return_comment" class="form-control" style="height: 200px;"></textarea>
								                    </div>
							                    </div>
							                    <div class="col-md-4">
							                    	<div class="form-group">
								                        <label for="name">Current Status</label>
								                        <span class="form-control bggray">{{isset(RETURNSTATUS[$data->return_status_id]) ? RETURNSTATUS[$data->return_status_id] : ''}}</span>
								                    </div>
									                <div class="row form-group">
								                        <div class="col-md-8">
								                        <label class="w-100" for="name">Change Status To</label>
								                        <select class="form-select" id="return_status" 
								                        name="return_status">
								                        	<option value="">Choose Status</option>
								                        	<?php foreach ($returnstatus as $key => $value) { ?>
								                        	<option value="<?=$key?>"><?=$value?></option>
								                        	<?php } ?>
								                        </select>
								                    	</div>
								                    	<div class="col-md-8 returnaction">
								                        <label class="w-100" for="name">Choose Action</label>
								                        <select class="form-select" id="return_action" name="return_action">
								                        	<option value="">Choose Action</option>
								                        	<?php foreach ($returntype as $akey => $avalue) { ?>
								                        	<option value="<?=$akey?>"><?=$avalue?></option>
								                        	<?php } ?>
								                        </select>
								                    	</div>
								                    	<div class="col-md-4">
								                    		<label class="w-100" for="name">&nbsp;</label>
								                        <button type="submit" id="modify_status" class=" btn btn-success" />Submit</button> 
								                    	</div>
								                    </div>
								                     
							                    </div>
							                </div>
               							</form>
									</div>
								</div>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('footer_scripts')
<script type="text/javascript">
	$(document).ready(function() { 
		$('#orderStatusForm').validate({
			rules: {
				return_status  : 'required',
				return_action  : {
									required:function(){
										 return $('#return_status option:selected').val() == 3;
									}
							},
				return_comment : 'required'
			},
			messages: {
				return_status  : 'Return status is required',	
				return_action  : 'Return action is required',	
				return_comment : 'Return status comment is required'	
			} 
		}); 

		$('.returnaction').css("display", "none"); 
		$('#return_status').on('change', function(event) {
			var returnstatus = $('#return_status option:selected').val();
			
			if(returnstatus==3){
				$('.returnaction').css("display", "block");
			}else{
				$('#return_action').val('');
				$('.returnaction').css("display", "none");
			}
		});

	    function BtnLoading(elem) {
        	$(elem).attr("data-original-text", $(elem).html());
	        $(elem).prop("disabled", true);
	        $(elem).html('<i class="spinner-border spinner-border-sm"></i> Loading...');
	    }
	    
	    function BtnReset(elem) {
	        $(elem).prop("disabled", false);
	        $(elem).html($(elem).attr("data-original-text"));
	    }

		$('#orderStatusForm').on('submit', function(event) {

			if ($(this).valid()) {
		        event.preventDefault();
				Swal.fire({
			        title: 'Are you sure?',
			        text: "You won't be able to revert this!",
			        icon: 'warning',
			        showCancelButton: true,
			        confirmButtonColor: '#3085d6',
			        cancelButtonColor: '#d33',
			        confirmButtonText: 'Yes, update it!'
		      	}).then((result) => {
			        if (result.isConfirmed) {

			        	var $this = $('#modify_status');
			        	BtnLoading($this);
			        	$.ajax({
				            method: "POST",
				            url: "status/"+"{{$data->id}}",
				            data: {
				            	"_token": "{{ csrf_token() }}",
				            	"status_id": $('select#return_status').val(),
				            	"return_action": $('select#return_action').val(),
				            	"return_comment": $('textarea#return_comment').val(),
				            },
				            success: (result) => {
				              var obj = JSON.parse(result);	
				              if(obj.error=='0') {
				                Swal.fire('Updated!',obj.msg,'success').then((result) => {
				                	location.reload();
				                });
				              } else {
				                Swal.fire('Error!',obj.msg,'error');
				              } 
				              $('#return_comment').val('');
				              BtnReset($this);
				            },
				            error: (error) => {
				                Swal.fire('Error!','Failed to update return status','error');
				                $('#return_comment').val('');
				                BtnReset($this);
				            }
			          	})			          	
			        }
		      	})
		    }
		});

		 
	});

	/*$('#return_status').on('change', function(){
		let comment = $('#return_status option:selected').text().replace(/\s+/g, " ");
		if(comment){
			$('#return_comment').val(comment);
		}		
	});*/
</script>
@endsection