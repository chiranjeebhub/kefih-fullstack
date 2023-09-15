@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
       $vendor_id =Request()->vendor;
    /*$parameters = Request::segment(3);
    $str = Request::segment(4);
	$daterange = Request::segment(5);
    $parameters_level = base64_decode($parameters);*/

	
	
    ?>
<style>
.nav-tabs>li>a {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    /* border: 1px solid transparent; */
    border-radius: 4px 40px 8px 0 !important;
}
.searchBtn{
        border-radius: 30px;
    right: 3px;
    top: 2px;
    padding: 6px 15px;
position: absolute;
background:#0c6cd5 !important;
opacity: 1 !important;

}
</style>

<div class="">
	<div class="allbutntbl">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			
			<div class="col-sm-10">
				<div class="row">
				<div class="col-md-2">
					<select class="form-control vendororder">
						<option value="0"
						<?php echo ($vendor_id==0)?"selected":""; ?>
						>All Vendor</option>
						<?php 
						foreach($vendors as $vendor){
						?>
						<option value="{{$vendor->id}}" 
						<?php echo ($vendor_id==$vendor->id)?"selected":""; ?>
						>{{$vendor->public_name}}</option>
						 
						<?php }?>
					</select>
				</div>
				<div class="col-md-2">
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
					</div>
					<div class="col-md-5">
						<div class="searchmain">
						<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="<?php echo ($str!='All')?$str:"";?>">
							<button type="submit" class="btn btn-primary searchBtn sOrdersearch"  >Search</button>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>

<!--<table>
	<tr>
	<td><a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a></td>
	<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$str}}"></td>
	<td><button type="submit" class="btn btn-primary searchButton" disabled >Search</button></td>
	<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

	</tr>
</table>-->
<div class="col-sm-12">
<nav class="mt15 new_ordertabs">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	<a class="nav-item nav-link  <?php echo (request()->route()->getName() == 'orders')?'active':''; ?>"  href="{{ route('orders',base64_encode(0)) }}">New Orders</a>
	<?php /*
	<a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('orders',base64_encode(1)) }}">Invoice</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('orders',base64_encode(2)) }}">Shipping</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('orders',base64_encode(3)) }}">Delivered</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('orders',base64_encode(4)) }}">Cancelled</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('orders',base64_encode(5)) }}">Returned & Refund</a>
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?>"  href="{{ route('orders',base64_encode(6)) }}">Refund</a>-->
	<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('orders',base64_encode(7)) }}">Failed</a>
	*/ ?>

	<a class="nav-item nav-link <?php echo ($parameters_level==0 && request()->route()->getName() != 'orders')?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(0)) }}">Pending</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(1)) }}">Invoice</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(2)) }}">Shipping</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(3)) }}">Delivered</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(4)) }}">Cancelled</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(5)) }}">Returned </a>
    <a class="nav-item nav-link <?php echo ($parameters_level==8)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(8)) }}">Completed</a>
	<a class="nav-item nav-link  <?php echo ($parameters_level==9)?'active':''; ?>"  href="{{ route('orders',base64_encode(9)) }}">Pending Payments</a>

  </div>
</nav>
</div>
<div class="col-sm-12 mt15">
	<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				<?php 
				switch($parameters_level){
					case 0:
					?>
					<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							<!-- <th>Deduct Wallet</th> -->
							<th>Total Amount</th>
							<th>Service Charge</th>
							<th>Exhibition Code</th>
							<!--<th>Action</th>-->
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<!-- <td>{{!empty($row->deduct_reward_points)?$row->deduct_reward_points:''}}</td> -->
							<!-- <td>{{$row->grand_total-$row->deduct_reward_points}}</td> -->
							<td>{{$row->grand_total}}</td>
							<td>{{$row->service_charge}}</td>
							<td>{{$row->exhibition_code}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">View Order </a>
							<a href="{{ route('order_service_invoice',base64_encode($row->id)) }}" class="btn btn-success btn-small" title="View Service Invoice"><i class="fa fa-eye"></i> Service Invoice</a>
							<a href="{{ route('order_service_invoice',base64_encode($row->id)) }}?download=1" class="btn btn-success btn-small"  title="Download Service Invoice"><i class="fa fa-download"></i> Download Service Invoice</a>

							<!--| 
							<a 
							onclick = "if (! confirm('Do you want to generate invoice ?')) { return false; }"
							href="{{ route('order_generate_invoice',base64_encode($row->id)) }}" class="btn btn-success btn-small">Generate Invoice</a>-->
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
						<?php
						break;
						
						case 1:
						?>
						<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Invoice Date</th>
							<th>Ship to</th>							
							<!--<th>Action</th>-->
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y ", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>
							<?php echo date("d M Y ", strtotime($row->updated_at)) ?>
							</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							<!--| 
							<a href="{{ route('order_shipping',base64_encode($row->id)) }}" class="btn btn-success btn-small">Shipping</a>-->
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
						<?php break; case 2:?>
						
						<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>	
							<th>Order ID</th>
							<th>Last Update</th>
							<th>Ship to</th>
							<th>Courier Details</th>							
							<!--<th>Action</th>-->
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					<?php //if(@$orders[0]['id']!=''){?>
					  @foreach($orders as $row)
					  <?php 
							$docket_info= App\OrdersShipping::orderDocket($row->id) ; 
					  ?>
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>
							<?php echo date("d M Y ", strtotime($row->updated_at)) ?>
							</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
									{{ @$docket_info->courier_name}}
								<br>{{ @$docket_info->docket_no}}
								<br>{{ @$docket_info->remarks}}
							</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							| 
							<a 	onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
							href="{{ route('order_delivered',base64_encode($row->id)) }}" class="btn btn-success btn-small">Delivered</a>
							</td>
						</tr>
					    @endforeach
					<?php //} ?>
					</tbody>
					
				  </table>
				  
							<?php  break; case 3:?>
							<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							<th>Courier Details</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($orders as $row)
					  <?php 
							$docket_info= App\OrdersShipping::orderDocket($row->id) ; 
					  ?>
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
									{{ @$docket_info->courier_name}}
								<br>{{ @$docket_info->docket_no}}
								<br>{{ @$docket_info->remarks}}
							</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
							<?php  break; case 4:?>
							<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
							<?php  break; case 5:?>
							<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							
						</tr>
					</thead>
					<tbody>
						<?php $i=1;?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
							<?php  break; case 6:?>
							<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
							<?php  break; case 7:?>
							<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Ship to</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y ", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
							<?php  break;?>

					<?php 
							case 9:
					?>
					<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Razorpay Order ID</th>
							<th>Ship to</th>					
							<th>Total Amount</th>
							<th>Service Charge</th>
							<th>Exhibition Code</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($orders as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>
							<?php echo date("d M Y", strtotime($row->order_date)) ?>
							</td>
							<td>{{$row->order_no}}</td>
							<td>{{$row->razorpay_order_id}}</td>
							<td>{{$row->order_shipping_address}},{{$row->order_shipping_zip}}</td>
							<td>{{$row->grand_total-$row->deduct_reward_points}}</td>
							<td>{{$row->service_charge}}</td>
							<td>{{$row->exhibition_code}}</td>
							<td>
							<a href="{{ route('order_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">view Order </a>
								@if(!empty($row->razorpay_order_id))
								<a href="{{route('razor_pay_check',$row->razorpay_order_id)}}" class="btn btn-xs btn-success">Razor Pay Check</a>
								@endif 
							</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
						<?php
						break;

						?>
						<?php 
						}
						?>
				  
				</div>
				<?php //if(@$orders[0]['id']!=''){?>
				{{ $orders->links() }}
				<?php //} ?>
  </div>
  
 
</div>
</div>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">
$(function() {

  $('input[name="daterange"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear',
		  format: 'DD-MM-YYYY'
      }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format('DD-MM-YYYY'));
	  $('.daterange').trigger('change');
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>
@include('admin.includes.Common_search_and_delete')

@endsection
