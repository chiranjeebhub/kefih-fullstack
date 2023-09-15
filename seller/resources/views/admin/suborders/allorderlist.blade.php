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
    
    ?>
<style>
.nav-tabs>li>a {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    /* border: 1px solid transparent; */
    border-radius: 4px 40px 8px 0 !important;
}.searchBtn{
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
							<button type="submit" class="btn btn-primary searchBtn AllOrderFilter">Search</button>
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
                            <th>Product Price </th>
							<th>Total with Shipping </th>
                            <th>Payment Mode</th>
                            <th>Status</th>
							<th>Action</th>
							
						
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1;
                            foreach($orders as $order){?>
                            <tr>
                            <td>{{$i}}</td>
							<td><?php echo date("d M Y ", strtotime($order->order_date)) ?></td>
							<td>{{$order->order_id}}
							<br>Item Id: {{$order->id}}</td>
							<td>{{$order->product_name}}
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
							<td>{{($order->product_price*$order->product_qty)+$order->order_shipping_charges}}</td>
                                        <td>
                                        @if($order->payment_mode==0)
                                        'COD'
                                        @else
                                        'Already Paid'
                                        @endif()
                                        </td>
							            <td>
							                @if($order->order_status == 0)
							                <span class="text-info">New Order</span>
							                @elseif($order->order_status == 1)
							                <span class="text-info">Invoice Generated</span>
							                @elseif($order->order_status == 2)
							                <span class="text-info">Shipped</span>
							                @elseif($order->order_status == 3)
							                <span class="text-success">Delivered </span>
							                @elseif($order->order_status == 4)
							                <span class="text-danger">Cancelled </span>
							                @elseif($order->order_status == 5)
							                <span class="text-warning">Returned  </span>
							                @elseif($order->order_status == 6)
							                <span class="text-warning">Refund   </span>
							                @elseif($order->order_status == 7)
							                <span class="text-danger">Failed   </span>
							                @endif
							                
							            </td>
							            <td>
							           <a href="{{ route('sorder_detail',base64_encode($order->id)) }}" class="btn btn-success btn-small">View Order </a>
							            </td>
                            </tr>
                            <?php $i++;}?>
                        
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
