@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
	error_reporting(0);
      $vendor_id =Request()->vendor;
       $Brand_ids=Request()->brand;
         $selectBrands=array();
             
    if($Brand_ids!='All' &&  $Brand_ids!=''){
    $selectBrands=explode(",",$Brand_ids);
    }
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

.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
    width: 163px!important;
}
.text {
    letter-spacing: 0px!important;
}
</style>

<style>

.show_more_less1{ margin-top:35px;}
										
.show_more_less ul, .show_more_less1 ul {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
  list-style-type: none;
  background: white;
  padding:0;
}

.show_more_less li,.show_more_less1 li {
  line-height: 50px;
  border-top: 1px solid #e9ecef;
}

.show_more_less ul a, .show_more_less1 ul a {
  display: block;
  height: 100%;
  text-decoration: none;
  color: black;
  padding-left: 10px;
  position: relative;
  -webkit-transition: background .3s;
		  transition: background .3s;
}

.show_more_less ul a:after, .show_more_less1 ul a:after {
  content: '';
  position: absolute;
  right: 10px;
}

.show_more_less ul a:hover, .show_more_less1 ul a:hover {
  background: #cdcbc4; 
}



/* CHECKBOX CONTAINER STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */

.show_more_less .container1, .show_more_less1 .container2 { 
  position: relative; 
  height: auto;
  border-top: 0;
}

[type="checkbox"] {
  position: absolute;
  left:  -9999px;   
}

.show_more_less label, .show_more_less1 label {
  background: #e4e3df;
  display: block;
  width: 100%;
  height: 30px;
  cursor: pointer;
  position: relative;
  /*
   * no need to position absolutely the element 
   * because we are not interested in any transition effect
   * position: absolute;
   * top: 0;
   */
}

/*
 * use the rule below for testing purposes
 * label:hover {
 *    background: yellow;
 *  }
 */

.show_more_less label:before,
.show_more_less label:after, 
.show_more_less1 label:before,
.show_more_less1 label:after {
	position: absolute;
}

.show_more_less label:before, .show_more_less1 label:before {
  content: 'More';
  left: 10px;
  border: none;
}

.show_more_less label:after, .show_more_less1 label:after {
  content: '⇣●';
  right: 10px;
  -webkit-animation: sudo .85s linear infinite alternate;
		  animation: sudo .85s linear infinite alternate;
}

@keyframes sudo { 
	from { 
	-webkit-transform: translateY(-2px); 
			transform: translateY(-2px); 
  }
	to { 
	-webkit-transform: translateY(2px); 
			transform: translateY(2px); 
  }
}

.show_more_less input[type="checkbox"] ~ ul, .show_more_less1 input[type="checkbox"] ~ ul {
  width: 100%;
	display: none;
}



/* CHECKED STATE STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */

.show_more_less [type="checkbox"]:checked ~ ul, .show_more_less1 [type="checkbox"]:checked ~ ul {
  display: block;
} 

.show_more_less [type="checkbox"]:checked + label, .show_more_less1 [type="checkbox"]:checked + label {
  /**
   * if we have positioned relatively the element, 
   * during the "checked" state 
   * we have to change its type of positioning
   */
  position: absolute; 
  top: 100%;
}

.show_more_less [type="checkbox"]:checked +  label:before, .show_more_less1 [type="checkbox"]:checked +  label:before {
  content: 'Less';
  border: none;
  -webkit-transform: rotate(0deg);
	-ms-transform: rotate(0deg);
	transform: rotate(0deg);
	top: 0;
	left: 10px;
}

.show_more_less [type="checkbox"]:checked +  label:after, .show_more_less1 [type="checkbox"]:checked +  label:after {
 content: ' '; 
}

.show_more_less ul li:last-child, .show_more_less1 ul li:last-child {
  margin-bottom: 0px;
}
</style>

<div class="">
	<div class="allbutntbl">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			<div class="col-sm-11">
				<div class="row">
					<div class="col-sm-2 col-md-2 text-center">
          <lable>Select Vendor</lable>
						<select class="form-control vendororder">
							<option value="0"
							<?php echo ($vendor_id==0)?"selected":""; ?>
							>All Vendor</option>
							<?php 
							foreach($vendors as $vendor){
							?>
							<option value="{{$vendor->id}}" 
							<?php echo ($vendor_id==$vendor->id)?"selected":""; ?>
							>{{$vendor->username}}</option>

							<?php }?>
						</select>
					</div>
						<div class="col-sm-2 col-md-2">
                        <div class=" text-center">
                        <lable>Select Brands</lable>
                        <select class="selectpicker" id="orderBrandId" multiple data-live-search="true">
                            <option value="">Select Brand</option>
							    <?php 
							    foreach($Brands as $Brand){
							    ?>
<option value="{{$Brand->id}}" 
<?php echo (in_array($Brand->id, $selectBrands) )?"selected":""; ?>
>{{$Brand->name}}</option>
							     
							    <?php }?>
                        </select>
                        
                        </div>
                        
                        </div>
						
				
        <div class="col-sm-2 col-md-2 text-center">
        <lable>Select Category</lable>
        {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
        </div>
					<div class="col-sm-2 col-md-2">
          <lable>Select Date Range</lable>
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
					</div>
					<div class="col-md-4">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="<?php echo ($str!='All')?$str:"";?>">
							<button type="submit" class="btn btn-primary searchBtn sOrdersearch">Search</button>
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
	<span>Total Items Ordered: <?php echo count($orders);?></span>
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	<a class="nav-item nav-link <?php echo ($parameters_level==0)?'active':''; ?>"  href="{{ route('sorders',base64_encode(0)) }}">New Orders </a>
	<a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('sorders',base64_encode(1)) }}">Invoice</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('sorders',base64_encode(2)) }}">Shipping</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('sorders',base64_encode(3)) }}">Delivered</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('sorders',base64_encode(4)) }}">Cancelled</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('sorders',base64_encode(5)) }}">Returned</a>
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?> " href="{{ route('sorders',base64_encode(6)) }}">Refund</a>-->
	<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('sorders',base64_encode(7)) }}">Failed</a>
	<a class="nav-item nav-link" id="exe_crone" href="#">Refresh Track <span style="display:none;" id="loader"><i class="fa fa-spinner fa-spin text-info"></i></span></a>
	
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
							<th>Payment Mode</th>
							<th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            
                             <?php if(in_array($parameters_level,array('0','1','2','4','5'))){ ?>
							<th>Action</th>
							<?php }?>
						
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $total=0; $i=1; if(@$orders[0]->order_no!=''){
                            foreach($orders as $order){?>
                            <tr id="table_row{{$order->id}}">
                            <td>{{$i}}</td>
							<td><?php echo date("d M Y H:i", strtotime($order->order_date)) ?></td>
							<td><?php echo $order->fld_master_order_id;?><?php //echo $order->order_no;?><br>
								Item Id : <?php echo $order->id;?><?php //echo $order->suborder_no;?>
							</td>
							<td>{{($order->payment_mode==1)?'Online':'COD'}}</td>
							<td>
							     <?php 
                                                   if($order->color_id!=0){
                            $colorwise_images=DB::table('product_configuration_images')
                            ->where('product_id',$order->product_id)
                            ->where('color_id',$order->color_id)
                            ->first();
                            if($colorwise_images){
                                $url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
                            } else{
                                 $url=URL::to('/uploads/products').'/'.$order->default_image; 
                            }
                                                   } else{
                                                       $url=URL::to('/uploads/products').'/'.$order->default_image; 
                                                   }
                                                   ?>
                                            <img src="{{$url}}" height="50" widht="50"></a>
							
							{{$order->product_name}}
                                        @if($order->size!='')
                                        <br>
                                        Size {{$order->size}}
                                        @endif
                                        
                                        @if($order->color!='')
                                        <br>
                                        Color {{$order->color}}
                                        @endif
										
										<?php 
											$attr=DB::table('product_attributes')->where('product_attributes.product_id','=',$order->product_id)->first();
										?>
										<br>
										SKU : <?php echo $attr->sku;?>
							</td>
							<td>{{$order->product_qty}}</td>
							<td>{{$order->product_price*$order->product_qty}}</td>
							<?php if(in_array($parameters_level,array('0','1','2','4','5','6'))){ ?>
							<td>
							<?php switch($parameters_level){ case 0: ?>
							
							<a href="{{ route('sorder_detail',base64_encode($order->id)) }}" class="btn btn-success btn-small">view Order </a>
							<!--| 
							<a 
							onclick = "if (! confirm('Do you want to generate invoice ?')) { return false; }"
							href="{{ route('order_generate_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Generate Invoice</a>-->
							
							<?php break; 
								  case 1: ?>
									<a href="{{ route('sorders_view_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">View Invoice</a>
							<!--<a href="{{ route('vendor_order_shipping',base64_encode($order->id)) }}" class="btn btn-success btn-small">Shipping</a>-->
							
							<?php break; 
								  case 2: ?>
								<?php 
									$shipping_info= App\OrdersShipping::orderShippingInfo($order->shipping_id) ; 
									$docket_info= App\OrdersShipping::orderDetailDocket($order->id) ; 
								?>
								
								<div class="show_more_less">
								    <a href="{{ route('admin_order_track',base64_encode($order->id)) }}" class="btn btn-success btn-small">Track</a>
									<ul>
									  <li><a href="#">Shipping Information</a></li>
									  <li class="container1">
										<input type="checkbox" id="check_id<?php echo $i;?>">
										<label for="check_id<?php echo $i;?>"></label>
										<ul>
										  <li><a >
										  {{ @$shipping_info->order_shipping_name}}<br>
										  {{ @$shipping_info->order_shipping_address}}
										  {{ @$shipping_info->order_shipping_address1}}
										  {{ @$shipping_info->order_shipping_address2}}<br>
										  {{ @$shipping_info->order_shipping_city}}
										  {{ @$shipping_info->order_shipping_state}}<br>
										  {{ @$shipping_info->order_shipping_country}}
										  {{ @$shipping_info->order_shipping_zip}}<br>
										  {{ @$shipping_info->order_shipping_phone}}<br>
										  {{ @$shipping_info->order_shipping_email}}<br>
										  {{ @$shipping_info->remarks}}</a></li>
										</ul>
									  </li>
									</ul>
								</div>
								
								<div class="show_more_less1">
									<ul>
									  <li><a href="#">Courier Information</a></li>
									  <li class="container2">
										<input type="checkbox" id="check_id1<?php echo $i;?>">
										<label for="check_id1<?php echo $i;?>"></label>
										<ul>
										  <li><a >
											{{ @$docket_info->fld_courier_name}}<br>
											{{ @$docket_info->logistic_link}}<br>
											{{ @$docket_info->docket_no}}<br>
											{{ @$docket_info->remarks}}</a></li>
										</ul>
									  </li>
									</ul>
								</div>
							
							<?php break; 
								  case 4: ?>
									<?php
											$reason_cancel=DB::table('cancel_return_refund_order')
													->join('order_cancel_reason', 'order_cancel_reason.id', '=', 'cancel_return_refund_order.reason')
												  ->where('sub_order_id',$order->id)
												  ->where('type',4)
												  ->first();
											
											echo $reason_cancel->reason.'<br>';
											echo $reason_cancel->comments;
											
									?>
								
							<?php break; 
								  case 5: ?>
									
									<?php
											$reason_cancel=DB::table('cancel_return_refund_order')
													->join('order_cancel_reason', 'order_cancel_reason.id', '=', 'cancel_return_refund_order.reason')
												  ->where('sub_order_id',$order->id)
												  ->where('type',5)
												  ->first();
											
											//echo $reason_cancel->reason.'<br>';
											//echo $reason_cancel->comments;
									?>
									
								  @if($parameters_level==5)
                                 <!--return action tab-->
                                 <?php 
								  $isReplaced=DB::table('replace_order_details')
								  ->where('sub_order_id',$order->id)
								   ->where('product_id',$order->product_id)
								   ->first();
								   
								   $reason=DB::table('cancel_return_refund_order')
								   ->select('order_cancel_reason.reason')
								   ->join('order_cancel_reason','order_cancel_reason.id','cancel_return_refund_order.reason')
								  ->where('cancel_return_refund_order.sub_order_id',$order->id)
								   ->first();
								   ?>
								   @if($isReplaced) 
                                        <!--if return -->
											{{($isReplaced->remarks)?'Remarks : '.$isReplaced->remarks:''}} <br>
                                            Reason:'{{$reason->reason}}'
                                                 <!--if refunded -->
                                                @if($isReplaced->replceOrder_sts==0)
                                                    <!--if check return type -->
                                                    @if($isReplaced->return_type==0)
                                                    <span>Refund Query</span>
                                                    @else
                                                    <span class="viewReaplcedOrder" suborder_id="{{$order->id}}"> Replace Query (View)</span>
                                                    @endif
                                                    
                                                    <!--check order staus-->
                                                     
                                                    @switch($isReplaced->order_status)
                                                            @case(0)
        <br>
        <a 
        onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
        href="{{ route('spickupOrder',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small"> Pickup the package </a>
                                                            @break
                                                            
                                                            @case(1)
                                                             <br>
                                                             <a 
        onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
        href="{{ route('spackageReceived',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small"> Is package recieve </a>
                                                            @break
                                                            
                                                            @case(2)
                                                             <br>
                                                                @if($isReplaced->return_type==0)
                                <a 
                                onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
                                href="{{ route('srefundConfirm',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small">Confirm Refund </a>
                                                                @else
                            <a 
                            onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
                            href="{{ route('sreplaceConfirm',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small">Confirm Replaced </a>
                                                                @endif
                                                            @break
                                                    @endswitch 
                                                    <!--check order staus-->
                                                    
                                                    <!--if check return type -->
                                                @else
                                                 <br>
                                                        @if($isReplaced->return_type==0)
                                                        <span>Refunded</span>
                                                        @else
                                                       <span class="viewReaplcedOrder" suborder_id="{{$order->id}}"> Replaced (View)</span>
                                                        @endif
                                                @endif
                                             <!--if refunded -->
                                            
                                            
                                           
                                             
                                             
                                            
                                        <!--if return -->
								   @endif
								    <!--return action tab-->
                                @endif
							
							
							<?php break; }?>
							</td>
							<?php }?>
						
                            </tr>
                            <?php $total+=$order->product_qty*$order->product_price; 
								$i++;}}?>
							 <tr>
								<td colspan="6" align="right">Total</td>
								<td><?php echo $total;?></td>
							 </tr>
							
                        </tbody>
					    </table>
				</div>
				{{ $orders->links() }}
  </div>
  
 
</div>
<div class="modal fade" id="replace_dialog" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title replace_model_header" >Replace order</h4>
        </div>
        <div class="modal-body replace_model_body">
            <style>
                a.badge.badge-danger.sizeClass.active {
                 border-radius: 50%;
                }
                    a.colorClass.active img {
                     border-radius: 50%;
                    }
            </style>
          <form role="form" class="form-element" action="{{route('replaceOrder')}}" method="post">
			   @csrf
			   <div class="replace_model_inputs">
			       
			   </div>
                    <div class="size_class" style="display:none">
                    <label for="exampleInputEmail1">Sizes</label> 
                    <div class="replace_model_body_attr_size"></div>
                    </div>
                    <div class="color_class" style="display:none">
                    <label for="exampleInputEmail1">Colors</label> 
                    <div class="replace_model_body_attr_color"></div>
                    </div>
			  
    <div class="form-group">
    <input type="text" name="remarks" value="" class="form-control" id="remarks" placeholder="Remarks">
    </div>
            
    <div class="form-group">
    <input type="button" name="replace" value="Replace" class="replaceOrderCurrent">
    </div>
   
   
    </form>
        </div>
       
      </div>
      
    </div>
  </div>
  
  <div class="modal fade" id="replaced_dialog" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" >Replaced Order</h4>
        </div>
       
        <div class="modal-body replaced_model_body">
         
        </div>
       
      </div>
      
    </div>
  </div>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script type='text/javascript'>

$(document).ready(function(){
 
 $("#exe_crone").click(function(){
 //alert('refreshing')
  $.ajax({
   url: '{{url('/cron')}}',
   type: 'get',
   //data: {},
   beforeSend: function(){
    // Show image container
    $("#loader").show();
   },
   success: function(response){
   $("#loader").hide();
   },
   complete:function(data){
    // Hide image container
    $("#loader").hide();
   }
  });
 
 });
});
</script>
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
