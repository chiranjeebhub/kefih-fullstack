@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
      $cust_id =Request()->id;
    $parameters = Request::segment(4);
    /*$str = Request::segment(4);
	$daterange = Request::segment(5);*/
    $parameters_level = base64_decode($parameters);
    
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
					<div class="col-sm-2 col-md-2">
							
					</div>
					<div class="col-sm-5 col-md-4">
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='all')? str_replace('--','/',$daterange):"";?>">
					</div>
					<div class="col-sm-5 col-md-6">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search Order/Suborder No" value="<?php echo ($str!='all')?$str:"";?>">
							<button type="submit" class="btn btn-primary searchButton" disabled>Search</button>
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
	<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="$str}}"></td>
	<td><button type="submit" class="btn btn-primary searchButton" disabled >Search</button></td>
	<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

	</tr>
</table>-->
<div class="col-sm-12">
	<p>{{ucwords($customer->name)}}</p>
	<p>{{ucwords($customer->email)}}</p>
	<p>{{ucwords($customer->phone)}}</p>
	<span>Total Items Ordered: <?php echo count($orders);?></span>
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	<a class="nav-item nav-link  <?php echo ($parameters_level==0)?'active':''; ?>"  href="{{ route('customer_orders',[$cust_id,base64_encode(0)]) }}">Orders </a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('customer_orders',[$cust_id,base64_encode(4)]) }}">Cancelled</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('customer_orders',[$cust_id,base64_encode(5)]) }}">Returned</a>
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?> "  href="{{ route('sorders',base64_encode(6)) }}">Refund</a>-->
	<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('customer_orders',[$cust_id,base64_encode(7)]) }}">Failed</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('customer_orders',[$cust_id,base64_encode(3)]) }}">Delivered</a>
	
  </div>
</nav>
</div>
<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Product Name</th>
                            <th>Product Qty</th>
                            <th>Product Price</th>
                           

							@if($parameters_level==4)
							<th>Cancel Date</th>
							@endif 

							@if($parameters_level==5)
							<th>Return Date</th>
							@endif 

							<?php //if(in_array($parameters_level,array('0','1','2'))){ ?>
							<th>&nbsp;</th>
							<?php //}?>
						
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1;
                            foreach($orders as $order){?>
                            <tr>
                            <td>{{$i}}</td>
							<td><?php echo date("d M Y ", strtotime($order->order_date)) ?></td>
							<td>Master Order ID: {{$order->order_no}}<br>
							Sub Order ID: {{$order->suborder_no}}
							</td>
							<td>{{$order->product_name}}
                                        @if($order->size!=0 &&  $order->size!='')
                                        <br>
                                        Size {{$order->size}}
                                        @endif
                                        
                                        @if($order->color!=0 &&  $order->color!='')
                                        <br>
                                        Color {{$order->color}}
                                        @endif
							</td>
							<td>{{$order->product_qty}}</td>
							<td>{{$order->product_price}}</td>
							
							@if($parameters_level==4)
							<td>
							@if($order->order_detail_cancel_date)
							{{ date("d M Y ", strtotime($order->order_detail_cancel_date)) }}
							@endif 
							</td>
							@endif 

							@if($parameters_level==5 )
							<td>
								@if($order->order_detail_return_date)
								{{ date("d M Y ", strtotime($order->order_detail_return_date)) }}
								@endif
							</td>
							@endif 

							<?php if(in_array($parameters_level,array('0','1','2'))){ ?>
							<td>
							<?php switch($parameters_level){ case 0: ?>
							
							<a href="{{ route('sorder_detail',base64_encode($order->id)) }}" class="btn btn-success btn-small">view Order </a>
							
							<?php break; 
								  case 1: ?>
					
							<?php break; 
								  case 2: ?>
					
								<?php $docket_info= App\OrdersShipping::orderDetailDocket($order->id) ; ?>
								
								<strong>Courier Information</strong>
							  	<br>{{ @$docket_info->fld_courier_name}}
								<br>{{ @$docket_info->logistic_link}}
								<br>{{ @$docket_info->docket_no}}
								<br>{{ @$docket_info->remarks}}
							<?php break; }?>
							</td>
							<?php } ?>
						
                            </tr>
                            <?php $i++;}?>
                        
                        </tbody>
					    </table>
				</div>
				{{ $orders->links() }}
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
