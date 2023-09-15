@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 

<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Cancel Order</a>
@endsection  
        <?php 
        $parameters = Request::segment(2);
        $parameters_level = base64_decode($parameters);
        
        ?>
<section class="dashbord-section">

	<div class="container">
<div class="row">
                <div class="col-md-12">
       				
					<div class="item-ordered">
 @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif
		   <form role="form" class="form-element" action="{{ route('cancel_order',base64_encode($master_id)) }}" method="post" enctype="multipart/form-data">
			   @csrf
		   <input type="hidden" value="{{$master_id}}" name="master_order_id">
            <div class="db-2-main-com db-2-main-com-table packagetbl">
				<h6 class="fs20 fw600 ftu mb20">Cancel Order</h6>
				<div class="row">
					<div class="col-md-8">
						<h6 class="fs14 ftu mb10">Date of Purchase : <span class="paymentorder fw600">
						    
						     <?php 
                                            $old_date_timestamp = strtotime($master_order->order_date);
                                            echo date('d M ,Y', $old_date_timestamp); 
                                            ?>
						</span></h6>
						<h6 class="fs14 ftu mb10">Mode of Payment : <span class="paymentorder fw600">
						<?php  echo ($master_order->payment_mode==0)?'COD':'Card';?>
						</span></h6>
					</div>
					<div class="col-md-4 payaddres">
						<h6 class="fs14 ftu mb10 fw600">Delivery Address</h6>
						<p>
						    {{$master_order->order_shipping_name}}
						    {{$master_order->order_shipping_address}}
						    {{$master_order->order_shipping_address1}}
						    {{$master_order->order_shipping_address2}}
						    {{$master_order->order_shipping_city}}
						    {{$master_order->order_shipping_state}},
						    {{$master_order->order_shipping_zip}}
						</p>
					</div>
				</div>
							
							<!--<h6 class="fs20 fw600 ftu mb20">Not Yet Shipped</h6>-->
				
							
				
							<div class="table mt20" id="results">
							  <div class="theader">
								  <div class="table_header">Image</div>
								  <div class="table_header">Name</div>
								  <div class="table_header">Quantity</div>
								
								  <div class="table_header">Price</div>
								  <div class="table_header">Total</div>
								  <div class="table_header">Cancel Item</div>
							  </div>
								<?php  $cancel_order=0;$i=1;foreach($sub_orders as $order){ 
								
								 $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount-$order->order_wallet_amount;                     
								
								?>
							  	<div class="table_row">
									  <div class="table_small">
											<div class="table_cell">Image</div>
											<div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" class="" target="_blank">

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
												<img src="{{$url}}"></a>
											</div>
									  </div>
								  	<div class="table_small dashboradtitle">
                                                    <div class="table_cell">Title</div>
                                                    <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" target="_blank">{{$order->product_name}}</a>
                                                   
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
                                                    <br>Color: {{$order->color}}
                                                    @endif
                                                   
                                                    </div>
                                                    </div>
                              
                                   	<div class="table_small">
								  <div class="table_cell">Quantity</div>
								 <div class="table_cell"> {{$order->product_qty}}</div>
								</div>
							
									<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> {{$order->product_price}} </div>
								</div>
								
								<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> {{$product_price}} </div>
								</div>
                                  
                                   	<div class="table_small">
								  <div class="table_cell">Cancel Item</div>
								  <div class="table_cell">
								  	  
									 
								   @switch($order->order_status)
                                        @case(0)
        <input  type="radio" checked class="cancel_reutrn_radio_button" 
        style="display:block !important"
        call_method="0"
        name="order_id[]" value="{{$order->id}}" prd_id={{$order->product_id}}>
                                        @break
                                        
                                        @case(1)
    <input  type="radio" checked class="cancel_reutrn_radio_button" 
    style="display:block !important"
    call_method="0"
    name="order_id[]" value="{{$order->id}}" prd_id={{$order->product_id}}>
                                        @break
                                        
                                        @case(2)
    <input  type="radio" checked class="cancel_reutrn_radio_button" 
    style="display:block !important"
    call_method="0"
    name="order_id[]" value="{{$order->id}}" prd_id={{$order->product_id}}>
                                        @break
                                        
                                         @case(3)
	
    <input  type="radio" checked class="cancel_reutrn_radio_button" 
    style="display:block !important"
    call_method="0"
    name="order_id[]" value="{{$order->id}}" prd_id={{$order->product_id}}>
	
                                        @break
                                        
                                         @case(4)
                                          <span> Cancelled </span>
                                        @break
                                        
                                         @case(5)
                                          <span>Already Returned</span>
                                        @break
                                        
                                        @case(6)
                                       
                                        @break
                                        
                                        @case(7)
                                          <span>Failed</span>
                                        @break
                                        
                                        @endswitch
                                        
								    
                                    @if($order->order_status==4)
                                    <?php $cancel_order++; ?>
                                    @else
                                    @endif
                                            
                                   
                               
                                </div>
                                </div>

                                </div>
								
									<?php $i++; } ?>
								
                                </div>
				
				<div class="row">
				     
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 fs14 cancel_policy">
						<label>Cancel policy</label>
					</div>
				</div>
				<?php if(sizeof($sub_orders)!=$cancel_order){?>
				<div class="row mt20">
					<div class="form-group cancel_itm">
						
						 <div class="checkbox checkbox-circle">
						     	<input  type="hidden" name="total_product" value="{{count($sub_orders)}}">
							<input id="terms1" type="checkbox" checked name="condition_accepted">
							<label for="terms1"></label> <a href="javascript:void(0)">Terms &amp; Conditions</a>
						 </div>
					</div>
				</div>
				<div class="row mt20">
					
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
            <label>Reason For Cancellation </label>
           <div class="form-group">
    <select name="reason" class="form-control custom-select cancel_reason" id="country">
    <option value="" selected="selected">--- Select Reason ---</option>
    </select>
    </div>   
            </div>
              
          </div> 
               <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
               <div class="text-right">
              <button class="cancelbtn" type="submit" value="submit">Submit</button>     
              </div>
               </div>   
          </div>
          
          <?php }?>

                                </div>  
           
           </form>
            </div>
					
			       
                    
</div>

</div>        
</div>
    
</section>  
@endsection

            

    
