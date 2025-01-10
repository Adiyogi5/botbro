@extends('admin.layouts.main')
@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{CSS}}ajax-datatables.css" />
@stop
@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card mb-0">
					<div class="card-body">
						<div class="row justify-content-end">
							<div class="col-auto px-0">
								<a id="print" title="Invoice Print" target="_blank"
									href="{{ url('admin/orders/invoice').'/'.$data->id }}"
									class="btn btn-success text-white"><i class="fa fa-print"></i> Invoice</a>
								<?php if($data->order_status_id!=6){ ?>
								&nbsp;&nbsp;
								<!-- <a id="print" title="Dispatch Print" target="_blank"
									href="{{ url('admin/orders/dispatch').'/'.$data->id }}"
									class="btn btn-primary text-white"><i class="fa fa-print"></i> Dispatch</a>-->
								<?php } ?> 
							</div>
						</div>
						<div class="row">
							<div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary"><i
											class="fa fa-shopping-cart"></i> Order Details</p>
								</div>
								<div class="card-body p-0">
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-shopping-cart" title="Order No."></div>
										<div class="col-11 align-self-end">{{ $data->order_no }}</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-calendar" title="Order Date"></div>
										<div class="col-11 align-self-end">{{ date('d-M-Y',
											strtotime($data->created_at)) }}</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card" title="Payment Status"></div>
										<div class="col-11 align-self-end">
											@if($data->payment_type ==0)
											{{ 'COD' }}
											@else
											<span class="small">
												<b>Online Payment</b> &nbsp; Payment Status:
												@if($data->payment_status ==0)
													<span class="small btn-sm btn-warning disabled">
														{{ 'Unpaid' }}
													</span>
												@else
													<span class="small btn-sm btn-success disabled">
														{{ 'Paid' }}
													</span>
												@endif
											</span>
											@endif
										</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-credit-card"  title="Transaction Detail"></div>
										<div class="col-11 align-self-end">
											@if($data->payment_type ==0)
											{{ ' -- ' }}
											@else
											<span class="small">
												Transaction ID: {{ $data->razorpay_payment_Id }}
											</span>
											@endif
										</div>
									</div>
								</div>
							</div>
							<div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary"><i class="fa fa-user"></i>
										Customer Details</p>
								</div>
								<div class="card-body p-0">
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-user" title="Customer Name"></div>
										<div class="col-11 align-self-end text-capitalize">{{ $data->customer_name }}
										</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-envelope" title="Email"></div>
										<div class="col-11 align-self-end">{{ $data->customer_email }}</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-phone" title="Mobile"></div>
										<div class="col-11 align-self-end">{{ $data->customer_mobile }}</div>
									</div>
									<div class="row m-0 p-2 border-bottom">
										<div class="col-1 p-1 bg-info text-center smallfnt rounded fa fa-truck" title="Shipping Change"></div>
										<div class="col-11 align-self-end">{{
											$data->shipping_amount!=0?$data->shipping_amount:'Free Shipping' }}</div>
									</div>
								</div>
							</div>
							 <div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary">Shipping Address</p>
								</div>
								<div class="card-body p-0">
									<div class="row m-0 p-2 border-bottom">
										{{ $data->shipping_address_1 }}
										@if($data->shipping_address_2)
										<br>
										{{ $data->shipping_address_2 }}
										@endif
										<br>
										{{ $data->shipping_city }}
										<br>
										{{ $data->shipping_state }}
										<br>
										{{ $data->shipping_country }}
										<br>
										{{ $data->shipping_postcode }}

										
										@if($data->order_ipaddresss) <br>&nbsp;<br><span> IP Address: <a target="_blank" href="https://whatismyipaddress.com/ip/{{ $data->order_ipaddresss }}">{{ $data->order_ipaddresss }}</a></span>  @endif
									</div>
								</div> 
							</div> 
							
						</div>
						 
						<div class="row">
							<div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary">Product Details</p>
								</div>
								<div class="card-body">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Product</th>
												<th>Model</th>
												<th>Quantity</th>
												<th>Unit Price</th>
												<th>Tax Amt.</th>
												<th>Total Price</th> 
											</tr>
										</thead>
										<tbody>
											@if(count($order_products) > 0)
											@foreach($order_products as $order_product)
											<tr class="{!! ($order_product['is_return_apply'] == 1) ? 'table-danger' : '' !!}">
												<td>
													<?php 
													$retstats = (!empty($order_product['return_detail']))?$order_product['return_detail']->return_status_id:''; 
													$retaction = (!empty($order_product['return_detail']))?$order_product['return_detail']->return_action_id:'';
													?>
													{{$order_product['name']}}<br />
													{!! ($order_product['is_return_apply'] == 1) ? '<span class="badge bg-danger">Return </span>' : ''!!}
													{!! ($order_product['is_replace_apply'] == 1) ? '<span class="badge bg-info">Replace </span>' : ''!!}
													<small>
													@if(!empty($retstats))
													<br>Status: {{ RETURNSTATUS[$retstats] }}
													@endif
													@if(!empty($retaction))
													<br>Action: {{ RETURNACTIONS[$retaction] }}
													@endif
													</small>
												</td>
												<td>{{$order_product['model']}}</td>
												<td>{{$order_product['quantity']}}</td>
												<td>{{$order_product['unit_price']}}</td>
												<td>{{$order_product['tax_price']}}</td>
												<td>{{$order_product['total_price']}}</td>
											</tr>
											@endforeach
											<tr>
												<td class="text-end fw-bold" colspan="5">Sub-Total</td>
												<td>{{$data->subtotal}}</td>
											</tr>
											<tr>
												<td class="text-end fw-bold" colspan="5">Tax-Amount</td>
												<td>{{$data->tax_amount}}</td>
											</tr>
											<tr>
												<td class="text-end fw-bold" colspan="5">Discount</td>
												<td>{{$data->discount}}</td>
											</tr>
											<tr>
												<td class="text-end fw-bold" colspan="5">Shipping-Price</td>
												<td>{{$data->shipping_amount}}</td>
											</tr>
											<tr>
												<td class="text-end fw-bold" colspan="5">Round Off</td>
												<td>{{$data->round_off}}</td>
											</tr>

											<tr>
												<td class="text-end fw-bold" colspan="5">Grand-Total</td>
												<td>INR {{$data->total}}
													@if ($data->currency=='USD')
													<br>
													({{$data->currency}}
													{{ $data->total * $data->conversion_value}})
													@endif
												</td>
											</tr>
											@else
											<tr>
												<td colspan="7" class="text-center">No data found</td>
											</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary">Order History</p>
								</div>
								<div class="card-body">
									<table id="order_history" class="table table-bordered table-striped datatable">
										<thead>
											<tr>
												<th>Date Added</th>
												<th>Status</th>
												<th>Comment</th>
											</tr>
										</thead>
										<tbody>
											@if(count($order_histories) > 0)
											@foreach($order_histories as $order_history)
											<tr>
												<td>{{ date('d-m-Y', strtotime($order_history['created_at'])) }}</td>
												<td>{{$order_history['order_status']}}</td>
												<td>{{$order_history['comment']}}</td>
											</tr>
											@endforeach
											@else
											<tr>
												<td colspan="5" class="text-center">No data found</td>
											</tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
						@if($data->order_status_id < 5 && $data->payment_status != 0) 
						
						<div class="row" id="order_status">
							<div class="col-md card m-2 p-0">
								<div class="card-header">
									<p class="card-title mb-0 fw-bold text-secondary">Update Order Status</p>
								</div>
								<div class="card-body">
									<form id="orderStatusForm">
										<div class="row">
											<div class="col-md-8">
												<div class="form-group mb-0">
													<label for="name">Order Status Comment</label>
													<textarea id="order_comment" name="order_comment"
														class="form-control" style="height: 200px;"></textarea>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="name">Current Status</label>
													<span
														class="form-control bggray">{{$data->order_status_name}}</span>
												</div>
												@if($data->payment_status == 0)
													<div class="form-group">
														<label class="w-100" for="name">Payment Status</label>
														<span class="small btn-sm btn-warning disabled">
															{{ 'Unpaid' }}
														</span>
													</div>
												@else
													<div class="row form-group">
														<div class="col-md-8">
															<label class="w-100" for="name">Change Status To</label>
															<select class="form-select" id="order_status"
																name="order_status">
																<option value="">Choose Status</option>
																<?php foreach ($orderstatus as $key => $value) { ?>
																<option value="<?=$value['id']?>">
																	<?=$value['name']?>
																</option>
																<?php } ?>
															</select>
														</div>
														<div class="col-md-4">
															<label class="w-100" for="name">&nbsp;</label>
															<button type="submit" id="modify_status"
																class=" btn btn-success" />Submit</button>
														</div>
													</div>
												@endif
											
												@if($data->order_status_id < 4) 
												<div class="form-group">
													<label class="w-100" for="name">Cancel Order</label>
													<button type="submit" id="cancel_status"
														class="w-100 btn btn-danger" />Order Cancel</button>
												</div>
												@endif
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
	$(document).ready(function () {
		$('#orderStatusForm').validate({
			rules: {
				order_status: 'required',
				order_comment: 'required'
			},
			messages: {
				order_status: 'Order status is required',
				order_comment: 'Order status comment is required'
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

		$('#orderStatusForm').on('submit', function (event) {
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
							url: "status/" + "{{$data->id}}",
							data: {
								"_token": "{{ csrf_token() }}",
								"status_id": $('select#order_status').val(),
								"order_comment": $('textarea#order_comment').val(),
							},
							success: (result) => {
								var obj = JSON.parse(result);
								if (!obj.error) {
									Swal.fire('Updated!', obj.msg, 'success').then((result) => {
										location.reload();
									});
								} else {
									Swal.fire('Error!', obj.msg, 'error');
								}
								$('#order_comment').val('');
								BtnReset($this);
							},
							error: (error) => {
								Swal.fire('Error!', 'Failed to update order status', 'error');
								$('#order_comment').val('');
								BtnReset($this);
							}
						})
					}
				})
			}
		});

		$('#cancel_status').on('click', function () {
			var order_comment = $('textarea#order_comment').val();
			if (order_comment == '') {
				alert('Please enter comment!!!');
				return false;
			}
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
						var $this = $('#cancel_status');
						BtnLoading($this);
						$.ajax({
							method: "POST",
							url: "status/" + "{{$data->id}}",
							data: {
								"_token": "{{ csrf_token() }}",
								"status_id": 6,
								"order_comment": $('textarea#order_comment').val(),
							},
							success: (result) => {
								var obj = JSON.parse(result);
								if (!obj.error) {
									Swal.fire('Updated!', obj.msg, 'success').then((result) => {
										location.reload();
									});
								} else {
									Swal.fire('Error!', obj.msg, 'error');
								}
								$('#order_comment').val('');
								BtnReset($this);
							},
							error: (error) => {
								Swal.fire('Error!', 'Failed to update order status', 'error');
								$('#order_comment').val('');
								BtnReset($this);
							}
						})
					}
				})
			}
		});
	});

	$('#order_status').on('change', function(){
		let comment = $('#order_status option:selected').text().replace(/\s+/g, " ");
		if(comment){
			$('#order_comment').val(comment);
		}
	});
	
</script>
@endsection