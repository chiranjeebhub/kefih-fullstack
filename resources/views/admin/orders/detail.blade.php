@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php $total=0; ?>
<div class="row">

	<div class="allbutntbl">
		<a href="{{route('orders',base64_encode(0)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
	</div>
			<!-- /.col -->
			<div class="col-lg-12 col-12">
			  <div class="box bg-transparent no-border no-shadow">
			
				<!-- /.box-header -->
				<div class="box-body b-1">
				
				  <div class="mailbox-read-info">
					<h3>Order ID : {{$Order[0]['order_no']}}</h3>
				  </div>
				  <div class="mailbox-read-info clearfix">
					<div class="pull-right margin-r-5">
					<br><small>Payment Mode : 
						
					@if($Order[0]['payment_mode']==0)
					 COD
					@elseif($Order[0]['payment_mode']==1)
					 Online
					@elseif($Order[0]['payment_mode']==2)
					 Exhibition({{$Order[0]['exhibition_payment_mode']}})
					@elseif($Order[0]['payment_mode']==3)
					 Wallet
					 <br><small>Transaction ID : {{$Order[0]['txn_id']}}</small>
					@endif 

					</small>
					@if($Order[0]['payment_mode']==1)
					<br><small>Transaction ID : {{$Order[0]['txn_id']}}</small>
					<br><small>Razorpay Order ID  : {{$Order[0]['razorpay_order_id']}}</small>
					@endif 

					</div>
					  <h5 class="no-margin"> Ship To</h5><br>
						 <small>{{ ucfirst($Order[0]['order_shipping_name'])}}</small>
						  <small>, {{$Order[0]['order_shipping_address']}}</small>
						   <small>. {{ ucfirst($Order[0]['order_shipping_zip'])}}</small><br>
						    <small>Phone  : {{ $Order[0]['order_shipping_phone']}}</small><br>
							 <small>Email : {{ $Order[0]['order_shipping_email']}}</small>
					 

				  </div>
				
				  <div class="mailbox-read-message">
					  <div class="row">
					 	<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>SubOrder No</th>
							<th>Image</th>
							<th>Product Name</th>
							<th>Short Description</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							<th>Status</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $i=1 ?>
					  @foreach($Order as $row)
								@php 
								 $productDetails = App\Helpers\CommonHelper::GetAdminProductDetails($row['product_id'],$row['color_id'],$row['size_id']);
								@endphp
						<tr>
								<td>{{$i++}}</td>
								<td>{{$row['suborder_no']}}</td>
								<td>
									@if(!empty($productDetails['image']))
									<img class="img-thumbnail" width="70" height="70" src="{{$productDetails['image']}}"/>
									@endif 
								</td>
								<td>{{$row['product_name']}}
								<br>{{ !empty($row['size'])?'Size: '.$row['size']:''}}
								<br>{{ !empty($row['color'])?'Color: '.$row['color']:''}}
								</td>
								<td>{{$productDetails['short_description']}}</td>
								<td>{{$row['product_qty']}}</td>
								<td>{{$row['product_price']}}</td>
								<td>{{$row['product_qty']*$row['product_price']}}</td>
								<td>
									@if($row['order_status'] == 0)
									Pending
									@elseif($row['order_status'] == 1)
									Invoice Generated
									@elseif($row['order_status'] == 2)
									Shipped 
									@elseif($row['order_status'] == 3)
									Delivered
									@endif 
								
								</td>
							
						</tr>
						<?php $total+=$row['product_qty']*$row['product_price'];?>
					    @endforeach
					</tbody>
					
				  </table>
					  </div>
				   <div class="box-footer" style="float: right;">
					<h5> Sub Total <span>Rs. ({{$total}})</span></h5>
					<br>
					
					<?php if(@$Order[0]['service_charge']!=0){
						$total = $total+$Order[0]['service_charge'];
						?>
					<h5> Service Charges <span>Rs. ({{@$Order[0]['service_charge']}})</span></h5>
					<br>
					<?php } ?>


					<?php if(@$Order[0]['deduct_reward_points']!=0){?>
					<h5> Deduct Wallet <span>Rs. ({{@$Order[0]['deduct_reward_points']}})</span></h5>
					<br>
					<?php } ?>

				

					<?php if(@$Order[0]['total_shipping_charges']!=0){
						$total = $total+$Order[0]['total_shipping_charges'];
						?>
					<h5> Delivery Charges <span>Rs. ({{@$Order[0]['total_shipping_charges']}})</span></h5>
					<br>
					<?php } ?>


					<?php if(@$Order[0]['coupon_code']!=''){?>
					<h5> Coupon Applied ({{$Order[0]['coupon_code']}}) at {{$Order[0]['coupon_percent']}}% <span>Rs. ({{round($Order[0]['coupon_amount'])}})</span></h5>
					<br>
					<?php $coupon_discount=$Order[0]['coupon_amount']; } ?>

					
					<?php if(@$Order[0]['coupon_code']=='' && @$Order[0]['coupon_amount']!=''){?>
					<h5> Discount <span>Rs. ({{round($Order[0]['coupon_amount'])}})</span></h5>
					<br>
					<?php $coupon_discount=$Order[0]['coupon_amount']; } ?>
					
					<h5> Grand Total <span>Rs. ({{round($total-@$Order[0]['deduct_reward_points']-@$coupon_discount)}})</span></h5>
				</div>
				<br>
				
				  </div>
				  
				  
				  <!-- /.mailbox-read-message -->
				</div>
				<!-- /.box-body -->
		
				
			  </div>
			  <!-- /. box -->
			</div>
			<!-- /.col -->
		  </div>
@endsection

