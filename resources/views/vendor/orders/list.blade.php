@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
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
    right: 3px;
    top: 2px;
    padding: 4px 15px;
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
	@if(Session::has('flash_success'))
<p class="alert alert-info">{{ Session::get('flash_success') }}</p>
@endif
		<div class="row">
		
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			<div class="col-md-2" style="display:none">
					<select class="form-control vendororder">
						<option value="43"></option>
						
					</select>
				</div>
				
			<div class="col-sm-3 col-md-3">
                        	<div class="searchmain">
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
                        
            <div class="col-sm-2 col-md-2">
							{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
						</div>
                        
			<div class="col-sm-3 col-md-3">
							<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
						</div>
					
			<div class="col-sm-3 col-md-3">
							<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="<?php echo ($str!='All')?$str:"";?>">
								<button type="submit" class="btn btn-primary searchBtn sOrdersearch"  >Search</button>
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
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-item nav-link   <?php echo (request()->route()->getName() == 'orders')?'active':''; ?>"  href="{{ route('orders',base64_encode(0)) }}">New Orders</a>
	<a class="nav-item nav-link  <?php echo ($parameters_level==0 && request()->route()->getName() != 'orders')?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(0)) }}">Pending </a>
	<a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(1)) }}">Invoice</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(2)) }}">Shipping</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==3)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(3)) }}">Delivered</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(4)) }}">Cancelled</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==5)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(5)) }}">Returned </a>
  <a class="nav-item nav-link <?php echo ($parameters_level==8)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(8)) }}">Completed</a>
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(6)) }}">Returned </a>-->
	<!--<a class="nav-item nav-link <?php echo ($parameters_level==6)?'active':''; ?> "  href="{{ route('orders',base64_encode(6)) }}">Refund</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==7)?'active':''; ?>"  href="{{ route('vendor_orders',base64_encode(7)) }}">Failed</a>-->
  <a class="nav-item nav-link  <?php echo ($parameters_level==9)?'active':''; ?>"  href="{{ route('orders',base64_encode(9)) }}">Pending Payments</a>

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
              <th>Vendor ID</th>
             
              <th>Product ID</th>
							<th>Order Date</th>
							<th>Master ID</th>
							<th>Suborder ID</th>
							<th>Product Name</th>
              
                            <th>Product Qty</th>
                            <th>Product Price </th>
                            <th>Exhibition Code</th>
                            <!-- <th>Delivery Preference </th> -->
                             <th>Total</th>
                              <th>Customer</th>
                              
                            <?php if(in_array($parameters_level,array('0','1','2','3','5','8'))){ ?>
							<th>Action</th>
							<?php }?>
							
							 <?php if(in_array($parameters_level,array('4'))){ ?>
							<th>Reason</th>
							<?php }?>
						
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1;
                            // echo '<pre>';
                            // print_r($orders);
                            // die;
                            foreach($orders as $order){?>
                            <tr>
                            <td>{{$i}}</td>
                            <td> {{$order->vendor_id}}</td>
                             
                            <td> {{$order->product_id}}</td>
							<td><?php echo date("d M Y ", strtotime($order->order_date)) ?></td>
              
							<td> {{$order->order_no}}</td>
								<td>{{$order->suborder_no}}</td>
							<td>{{$order->product_name}}
							
							
                                       @if($order->w_size!='')
							
                                        @if($order->size!='')
                                        <br>
                                        Men Size : {{$order->size}}
                                        @endif
                                    
                                            @if($order->w_size!='')
                                            <br>
                                            Women Size :{{$order->w_size}}
                                            @endif
							@else
                                    @if($order->size!='')
                                    <br>
                                    Size {{$order->size}}
                                    @endif
							@endif()
                                        
                                        @if($order->color!='')
                                        <br>
                                        Color {{$order->color}}
                                        @endif
							</td>
							<td>{{$order->product_qty}}</td>
							<td>{{$order->product_price}}</td>
							
              <td> {{$order->exhibition_code}}</td>
              <?php /*
							<td><?php echo $order->delivery_time ?>
						   <?php echo $order->delivery_day ?>
						   <?php echo $order->delivery_date ?>      
                               
                                </td>	
                                */ ?>
<td>
 
    <!-- {{($order->details_qty*$order->details_price)+$order->details_shipping_charges+$order->details_cod_charges-$order->details_cpn_amt-$order->details_wlt_amt+$order->slot_price}} -->
    <!--{{($order->product_price*$order->product_qty)-$order->order_wallet_amount-$order->order_coupon_amount+$order->order_cod_charges}}-->

    {{($order->details_qty*$order->details_price)+$order->details_shipping_charges+$order->details_cod_charges-$order->details_cpn_amt}}

  </td>

 <td> <button type="button" class="btn btn-info" data-toggle="modal" data-target="#customerModal{{$order->id}}">View</button>
 
                <div id="customerModal{{$order->id}}" class="modal fade" role="dialog">
                <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Customer  Info</h4>
                  </div>
                  <div class="modal-body">
                        <table border="1">
                           <tbody>
                        
                        <!-- <tr>
                        <th> Account Name</th>
                        <td>{{$order->cust_name}}</td>
                        </tr> -->
                        <tr>
                            <th> Name</th>
                            <td>{{$order->customer_name}}</td>
                            </tr>
                            
                          
                            
                            
                                <tr><th>Phone</th>
                                <td>{{$order->customer_phone}}</td>
                                </tr>
                                
                        <tr><th>Email</th>
                        <td>{{$order->customer_email}}</td>
                        </tr>
                        
                        <tr><th>Addreess</th>
                        <td>{{$order->customer_add}} {{$order->customer_add1}} {{$order->customer_add2}} {{$order->customer_state}} {{$order->customer_city}} -{{$order->customer_zip}}</td>
                        </tr>
                        
                          <th>Payment Mode</th>
                            <td>
                                <?php 
                                if($order->payment_mode==0){
                                    echo "COD";
                                    
                                    
                                }
                                
                                if($order->payment_mode==1){
                                     echo "Online";
                                }

                                if($order->payment_mode==2){
                                  echo " Exhibition(".$order->exhibition_payment_mode.")";
                                 }
                                ?>
                                
                               <?php /* if(@$order->order_status=='3'){?>
					 <br>
				<small>Recipt : {!! App\Helpers\CustomFormHelper::support_image('public/uploads/recipt/',@$order->payment_recipt); !!} 	</small> 
					 <?php } */ ?> 
                            </td>
                            </tr>
                            @if($order->payment_mode==2)
                            <tr>
                              <th>Exhibition Name</th>
                              <td>{{$order->exhibition_name}}</td>
                            </tr>
                            @endif 


                            <tr>
                              <th>Amount Paid</th>
                              <td>{{($order->details_qty*$order->details_price)+$order->details_shipping_charges+$order->details_cod_charges-$order->details_cpn_amt}}
</td>
                            </tr>
                      
                            </tbody>
                         </table>
                  </div>
                </div>
                
                </div>
                </div>
 
 </td>
								
            					  @if($parameters_level==4)
            					  <?php 
            					  
                        $reason=DB::table('cancel_return_refund_order')
                          ->select('order_cancel_reason.reason')
                          ->join('order_cancel_reason',
                                'order_cancel_reason.id',
                                'cancel_return_refund_order.reason'
                                )
                         ->where('cancel_return_refund_order.sub_order_id',$order->id)
                         ->first();
                         
            					  ?>
            					<td>
                    
                        @if($order->order_detail_cancel_date)
                        <strong>Cancel Date : {{date('d-m-Y',strtotime($order->order_detail_cancel_date))}} </strong>
                        <br>
                        <a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>
                         
                        @endif 
                        <br>
                        <?php 
            					
            					if($reason){
            					    echo @$reason->reason;
            					} else{
            					    echo "Other";
            					}
            					?></td>
            					@endif
                                @if($parameters_level==5)
                                 <!--return action tab-->
                                 <?php 
                             
								  $isReplaced=DB::table('replace_order_details')
								  ->where('sub_order_id',$order->id)
								   ->where('product_id',$order->product_id)
								   ->first();?>
								   @if($isReplaced) 
                                        <!--if return -->
                                            <td>
                                            @if($order->order_detail_return_date)
                                            <strong> Date : {{date('d-m-Y',strtotime($order->order_detail_return_date))}} </strong>
                                            <br>
                                            <a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>
<br>
                                            @endif 

                                        
                                                 <!--if refunded -->
                                                @if($isReplaced->replceOrder_sts==0)
                                                    <!--if check return type -->
                                                    @if($isReplaced->return_type==0)
                                                    <span>Refund Query </span> 
                                (<span data-toggle="modal" data-target="#customerBankDetails{{$order->id}}" style="cursor: pointer;
                                color: red;">See Bank Details </span>)
                                                   
                                                    
                                                    <br>
                                                            
                                                        <div id="customerBankDetails{{$order->id}}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Customer Bank Details</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                      
                                                            <table border="1">
                                                            <tbody>
                                                                
                                                            <tr>
                                                            <th> Account Holder Name</th>
                                                            <td>{{$isReplaced->account_holder_name}}</td>
                                                            </tr>
                                                            
                                                             <tr>
                                                            <th> Account NO.</th>
                                                            <td>{{$isReplaced->account_no}}</td>
                                                            </tr>
                                                            
                                                             <tr>
                                                            <th> Bank Name</th>
                                                            <td>{{$isReplaced->bank_name}}</td>
                                                            </tr>
                                                            
                                                            
                                                             <tr>
                                                            <th> IFSC Code</th>
                                                            <td>{{$isReplaced->ifsc_code}}</td>
                                                            </tr>
                                                            
                                                            
                                                             <tr>
                                                            <th> Branch</th>
                                                            <td>{{$isReplaced->branch}}</td>
                                                            </tr>
                                                              
                                                            
                                                            
                                                            </tbody>
                                                            </table>
                                                        </div>
                                                       
                                                        </div>
                                                        
                                                        </div>
                                                        </div>
                                                   
                                                    @else
                                                    <span class="viewReaplcedOrder" suborder_id="{{$order->id}}" style="cursor: pointer;
                                color: red;"> Replace Query (View)</span>
                                                    @endif
                                                    
                                                    <!--check order staus-->
                                                     
                                                    @switch($isReplaced->order_status)
                                                            @case(0)
        <br>
        <a 
        onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
        href="{{ route('pickupOrder',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small"> Pickup the package </a>
                                                            @break
                                                            
                                                            @case(1)
                                                             <br>
                                                             <a 
        onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
        href="{{ route('packageReceived',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small"> Is package recieve </a>
                                                            @break
                                                            
                                                            @case(2)
                                                             <br>
                                                                @if($isReplaced->return_type==0)
                                <a 
                                onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
                                href="{{ route('refundConfirm',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small">Confirm Refund </a>
                                                                @else
                            <a 
                            onclick = "if (! confirm('Do you want to continue ?')) { return false; }"
                            href="{{ route('replaceConfirm',base64_encode($isReplaced->id)) }}" class="btn btn-success btn-small">Confirm Replaced </a>
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
                                                       <span class="viewReaplcedOrder" suborder_id="{{$order->id}}"> Replaced generated  (View)</span>
                                                        @endif
                                                @endif
                                             <!--if refunded -->
                                            
                                            
                                           
                                             
                                            </td>
                                        <!--if return -->
								   @endif
								    <!--return action tab-->
                                @endif
                                

							<?php if(in_array($parameters_level,array('0','1','2','3','8'))){ ?>
							<td>
							<?php switch($parameters_level){ case 0: ?>
							<a onclick = "if (! confirm('Do you want to cancel this item ?')) { return false; }" href="{{ route('vendor_order_cancel',base64_encode($order->id)) }}" class="btn btn-success btn-small">Cancel Order </a>
							|
							<a href="{{ route('vendor_order_detail',base64_encode($order->id)) }}" class="btn btn-success btn-small">View Order </a>
							| 
							<a 
							onclick = "if (! confirm('Do you want to generate invoice ?')) { return false; }"
							href="{{ route('vendor_order_generate_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Generate Invoice</a>
              |                            
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">
              {{(empty($order->seller_invoice_num))?'Generate':''}}  Seller Invoice
              </a>

              @if(!empty($order->seller_invoice_num))
              | 
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small">
               <i class="fa fa-download"></i>  Download Seller Invoice
              </a>
              @endif 

							<?php break; 
								  case 1: 
								  /*
								  $deliverylist1 = App\DeliveryBoy::where(['status'=>1,"city"=>$order->customer_city])->orderBy('name','asc')->get();
								  
								
				 if(@$deliverylist1){ 
								  ?>
								    <select class="form-control" name="vendor_id" onchange="assignvendor(this.value,<?php echo $order->id;?>)">
            <option>Assign Delivery Boy</option>
            <?php foreach($deliverylist1 as $vrow){ 
             ?>
            <option value="<?php echo $vrow->id ?>" <?php echo($vrow->id==@$order->deliveryID)?'selected':''; ?>><?php echo ucfirst($vrow->name); ?> <?php echo $vrow->deliveryID; ?></option>
            <?php } ?>
            </select> 
				     	<?php } */ ?>         
                             
							<a onclick = "if (! confirm('Do you want to cancel this item ?')) { return false; }" href="{{ route('vendor_order_cancel',base64_encode($order->id)) }}" class="btn btn-success btn-small">Cancel Order </a>
							|
							<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>

              |
							<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small"><i class="fa fa-download"></i> Download Product Invoice</a>
    					


    						<!--<a href="{{ route('vendor_order_sdetail',base64_encode($order->order_id)) }}" class="btn btn-success btn-small">Multiple Shipping</a>-->
    						<!--<a href="{{ route('vendor_order_shipping_extradetails',base64_encode($order->id)) }}" class="btn btn-success btn-small">Shipping</a>-->
    						|	<a href="{{ route('vendor_order_shipping',base64_encode($order->id)) }}" class="btn btn-success btn-small">Shipping</a>
          
              @if(!empty($order->seller_invoice_num))
              |                            
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Seller Invoice</a>
             
              | 
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small">
               <i class="fa fa-download"></i>  Download Seller Invoice
              </a>
             
              @endif
							
							<?php break; 
								  case 2: ?>
						<a href="{{ route('vendor_order_delivered',base64_encode($order->id)) }}" class="btn btn-success btn-small">Deliver</a>
            <a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>
           
            |
							<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small"><i class="fa fa-download"></i> Download Product Invoice</a>
    					
            @if(!empty($order->seller_invoice_num))
              |                            
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Seller Invoice</a>
              | 
              <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small">
               <i class="fa fa-download"></i>  Download Seller Invoice
              </a>
              @endif
								<?php $docket_info= App\OrdersShipping::orderDetailDocket($order->id);
								
								$cusrior_info = DB::table('tbl_courierorderinfo')->where('order_detail_id',$order->id)->first();
								
								 ?>
								 <br>
								<strong>Courier Information</strong>
							  	<br>Courier Name: {{ @$cusrior_info->courier_name}}
								<br><a class="btn btn-xs btn-info" href="{{ @$cusrior_info->forward_label}}" target="_blank">Courier Label</a>
								<br>Tracking Number: {{ @$cusrior_info->tracking_number}}
								
								
								<!--<strong>Courier Information</strong>-->
							  	<br>{{ @$docket_info->fld_courier_name}}
								<br>{{ @$docket_info->logistic_link}}
								<br>{{ @$docket_info->docket_no}}
								<br>{{ @$docket_info->remarks}}
							<?php break; ?>
              
              <?php  
								  case 3: ?>
                  							<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>
                                |
						                	<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small"><i class="fa fa-download"></i> Download Product Invoice</a>
    					
                              |   <a href="{{ route('vendor_order_returned',base64_encode($order->id)) }}" class="btn btn-warning btn-small">Return</a>

                                @if(!empty($order->seller_invoice_num))
                                |                            
                                <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Seller Invoice</a>
                                | 
                                <a href="{{ route('vendor_order_seller_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small">
                                <i class="fa fa-download"></i>  Download Seller Invoice
                                </a>
                                @endif

                                
                               <?php 
                               // print_r($order)
                               if(!empty($order->order_detail_delivered_date))
                               {
                                  $date1 = date('Y-m-d H:i:s');
                                  $date2=$order->order_detail_delivered_date;
                                  $diff = abs(strtotime($date2) - strtotime($date1));
                                  $years = floor($diff / (365*60*60*24));
                                  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                                  if($days>='1')
                                  {
                                  ?>
                                    <a 
                                    onclick = "if (! confirm('Do you want to complete order ?')) { return false; }"
                                      href="{{ route('vendor_order_completed',base64_encode($order->id)) }}" class="btn btn-success btn-small">Mark as Completed</a>
                  
                                <?php 
                                    }
                              
                              }
                           ?>         
							<?php break; ?>
              <?php  
								  case 8: ?>
                  							<a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}" class="btn btn-success btn-small">Print Invoice</a>
                             <a href="{{ route('vendor_order_invoice',base64_encode($order->id)) }}?download=1" class="btn btn-success btn-small"><i class="fa fa-download"></i> Download Product Invoice</a>
    					
							<?php break; ?>
          
    
              
              <?php }?>

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
