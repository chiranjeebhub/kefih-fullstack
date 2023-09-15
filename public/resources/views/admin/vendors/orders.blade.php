@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
      $vendor_id =Request()->id;
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
	line-height: 22px;
/*background:#0c6cd5 !important;*/
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
							
					</div>
					<div class="col-md-2">
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='all')? str_replace('--','/',$daterange):"";?>">
					</div>
					<div class="col-md-4">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search Order/Suborder No" value="<?php echo ($str!='all')?$str:"";?>">
							<button type="submit" class="btn btn-primary searchButton " disabled>Search</button>
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
<div class="row">
    <div class="col-sm-6">
    <p></p>
   <p>{{ucwords($vendor->name)}}</p>
	<p>{{ucwords($vendor->email)}}</p>
	<p>{{ucwords($vendor->phone)}}</p>
	<p>Profile Status - @if($vendor->status == 1) Approved @else Pending @endif</p>
	<p>Registerd Date - {{ \Carbon\Carbon::parse($vendor->created_at)->format('d/m/Y')}}
</p>
</div>
    <div class="col-sm-3">
    <p></p>
   	<!-- <p>GST Number - {{$vendor->gst_no}}</p> -->
	<p>GST File - @if($vendor_details)<a href="{{asset('uploads/docs/gst/'.$vendor_details->gst_file)}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif</p>
	<p>Adhar Card - @if($vendor->adharcard != '')<a href="{{$vendor->adharcard}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif    </p>
 	<p>PAN Card - @if($vendor->pancard != '')<a href="{{$vendor->pancard}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif    </p>
</div>
    <div class="col-sm-3">
    <p></p>
  
	<p>Address Proof - @if($vendor->address_proof != '')<a href="{{$vendor->address_proof}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif    </p>
	<p>Certificate - @if($vendor->certificate != '')<a href="{{$vendor->certificate}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif    </p>
	<p>Other Documents - @if($vendor->other_documents != '')<a href="{{$vendor->other_documents}}" download>Download</a>@else <a href="javascript:void(0)" class="text-warning">Not Uploaded yet</a> @endif    </p>
</div>
<div class="col-sm-12">
	

	<span>Total Items Ordered: <?php echo count($orders);?></span>
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	<!--<a class="nav-item nav-link  <?php echo ($parameters_level==0)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(0)]) }}">Orders </a>-->
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(4)]) }}">Cancelled</a>-->
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(5)]) }}">Returned</a>-->
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?> "  href="{{ route('sorders',base64_encode(6)) }}">Refund</a>-->
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(7)]) }}">Failed</a>-->
	
	
    <a class="nav-item nav-link  <?php echo ($parameters_level==0)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(0)]) }}">Pending ({{$counts['pending']}})</a>
    <a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(1)]) }}">Invoice ({{$counts['invoice']}})</a>
    <a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(2)]) }}">Shipping ({{$counts['shipping']}})</a>
    <a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(3)]) }}">Delivered ({{$counts['delivery']}})</a>
    <a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(4)]) }}">Cancelled ({{$counts['cancel']}})</a>
    <a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(5)]) }}">Returned & Refund ({{$counts['return']}})</a>
    	<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('vendors_order',[$vendor_id,base64_encode(7)]) }}">Failed ({{$counts['failed']}})</a>
	
  </div>
</nav>
</div>

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
							<td>{{$order->order_no}}<br>
								{{$order->suborder_no}}
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
							<?php if(in_array($parameters_level,array('0','1','2'))){ ?>
							<td>
							<?php switch($parameters_level){ case 0: ?>
							
							<a href="{{ route('sorder_detail',base64_encode($order->id)) }}" class="btn btn-success btn-small">view Order </a>
								
							<!--| 
							<a 
							onclick = "if (! confirm('Do you want to generate invoice ?')) { return false; }"
							href="{{ route('order_generate_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Generate Invoice</a>-->
							
							<?php break; 
								  case 1: ?>
					
							<!--<a href="{{ route('vendor_order_shipping',base64_encode($order->id)) }}" class="btn btn-success btn-small">Shipping</a>-->
							
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
