@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
<?php $total=0; ?>
<div class="row">
			<!-- /.col -->
			<div class="col-lg-12 col-12">
			  <div class="box bg-transparent no-border no-shadow">
			
				<!-- /.box-header -->
				<div class="box-body b-1">
				
				  <div class="mailbox-read-info">
					<h3>Order ID : {{$Order[0]['order_no']}}</h3>
				  </div>
				  <div class="mailbox-read-info clearfix">
					<div class="left-float margin-r-5">
				
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
							<th>Product Name</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $i=1 ?>
					  @foreach($Order as $row)

						<tr>
								<td>{{$i++}}</td>
								<td>{{$row['suborder_no']}}</td>
								<td>{{$row['product_name']}}
								<br>{{ !empty($row['size'])?'Size: '.$row['size']:''}}
								<br>{{ !empty($row['color'])?'Color: '.$row['color']:''}}
								</td>
								<td>{{$row['product_qty']}}</td>
								<td>{{$row['product_price']}}</td>
								<td>{{$row['product_qty']*$row['product_price']}}</td>
							
						</tr>
						<?php $total+=$row['product_qty']*$row['product_price'];?>
					    @endforeach
					</tbody>
					
				  </table>
					  </div>
				   <div class="box-footer" style="float: right;">
					<h5> Sub Total <span>Rs. ({{$total}})</span></h5>
					<br>
					<!--< ?php if(@$Order[0]['deduct_reward_points']!=0){?>
					<h5> Deduct Wallet <span>Rs. (@$Order[0]['deduct_reward_points']}})</span></h5>
					<br>
					< ?php } ?>
					< ?php if(@$Order[0]['coupon_code']!=''){?>
					<h5> Coupon Applied ($Order[0]['coupon_code']}}) at $Order[0]['coupon_percent']}}% <span>Rs. (round($Order[0]['coupon_amount'])}})</span></h5>
					<br>
					< ?php $coupon_discount=$Order[0]['coupon_amount']; } ?>
					<h5> Grand Total <span>Rs. (round($total-@$Order[0]['deduct_reward_points']-@$coupon_discount)}})</span></h5>-->
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

