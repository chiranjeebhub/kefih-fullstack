@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 

<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Return & Refund Order</a>
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
		   <form role="form" class="form-element" action="{{ route('return_refund_order',base64_encode($master_id)) }}" method="post" enctype="multipart/form-data">
			   @csrf
		   <input type="hidden" value="{{$master_id}}" name="master_order_id">
            <div class="db-2-main-com db-2-main-com-table packagetbl">
				<h6 class="fs20 fw600 ftu mb20">Return & Refund Order</h6>
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
								  <!--<div class="table_header">Description</div>-->
								  <div class="table_header">Select Item</div>
							  </div>
								<?php  $cancel_order=0;$i=1;foreach($sub_orders as $order){
								
								 $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount-$order->order_wallet_amount;                     
								
								?>
							  <div class="table_row">
                                        <div class="table_small">
                                        <div class="table_cell">Image</div>
                                        <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" class="">
                                            
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
                                                    <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}">{{$order->product_name}}</a>
                                                    <br>
                                                  
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
                                                    Color: {{$order->color}}
                                                    @endif
                                                   
                                                    </div>
                                                    </div>
                              
                                <div class="table_small">
								  <div class="table_cell">Quantity</div>
								 <div class="table_cell"> {{$order->product_qty}}</div>
								</div>
							
								<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> {{$order->product_qty*$order->product_price}} </div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> {{$product_price}} </div>
								</div>
                                  
                                   <div class="table_small">
								  <div class="table_cell">Select Item</div>
								  <div class="table_cell"> 
								    
                                        @switch($order->order_status)
                                        @case(0)
                                        Processing
                                        @break
                                        
                                        @case(1)
                                         Invoice generated
                                        @break
                                        
                                        @case(2)
                                        <span>On Shipping</span>
                                        @break
                                        
                                         @case(3)
                                         
                                         <?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
                                      
									  
				
				<input  type="radio" checked class="cancel_reutrn_radio_button" 
				call_method="1"
				style="display: block !important;"
				name="order_id[]" value="{{$order->id}}" prd_id="{{$order->product_id}}" >
			
									  
                                         <?php } else{?>
                                          <span>Return Days Expires</span>
                                    <?php }?>
                                       
                                        @break
                                        
                                         @case(4)
                                          <span>Already Canceled</span>
                                        @break
                                        
                                         @case(5)
                                          <span>Already Return</span>
                                        @break
                                        
                                        @case(6)
                                       
                                        @break
                                        
                                        @case(7)
                                          <span>Failed</span>
                                        @break
                                        
                                        @endswitch

                                    @if($order->order_status==5 || $order->order_status==6)
                                    <?php $cancel_order++; ?>
                                      
                                    @else
                                      
                                    @endif
                                            
                                   
                               
                                </div>
                                </div>

                                </div>
								
									<?php $i++; } ?>
								
                                </div>
				
				<div class="row">
				      <label>Return policy</label>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 fs14 return_policy">
					
					</div>
				</div>
				<?php if(sizeof($sub_orders)!=$cancel_order){?>
				<div class="row mt20">
					<div class="form-group cancel_itm">
						
						 <div class="checkbox checkbox-circle">
						     	<input  type="hidden" name="total_product" value="{{count($sub_orders)}}">
						     		<input  type="hidden" name="payment_mode" value="{{$master_order->payment_mode}}">
							<input id="terms1" type="checkbox" checked name="condition_accepted">
							<label for="terms1"></label> <a href="javascript:void(0)">Terms &amp; Conditions</a>
						 </div>
					</div>
				</div>
				<div class="row mt20">
				
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                            <label>Remarks?</label>
                            <div class="form-group">
                            <input type="text" name="remarks" value="" class="form-control">
                            <?php 
                            $data=APP\Products::productDetails($order->product_id); 
                            ?>
                                    <input type="hidden" value="{{$data->product_type}}" id="productType">
                                    <input type="hidden" value="{{$order->product_id}}" id="productId">
                                    <input type="hidden" name="color_id" value="0" id="color_id">
                                    <input type="hidden" name="size_id" value="0" id="size_id">
                                      <input type="hidden" name="w_size_id" value="0" id="w_size_id">
                                    <input type="hidden"  value="{!!App\Products::Iscolorrequires($order->product_id)!!}" id="color_require">
                                    <input type="hidden" value="{!!App\Products::Issize_requires($order->product_id)!!}" id="size_require">
                            </div>   
                            </div>
                            
                            </div>
                    
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="form-group">
                    <label>Waht do you want ?</label>
                    <div class="form-group">
                    <select name="return_or_refund" class="form-control custom-select returnType"   suborder_id="{{$order->id}}"
                    product_id="{{$order->product_id}}">
                    <option value="0" selected>Refund</option>
                    <option value="1">Replaced</option>
                    </select>
                    </div>   
                    </div>
                    
                    </div>
          
          <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
            <label>Reason For Return  </label>
           <div class="form-group">
    <select name="reason" class="form-control custom-select return_reason" id="country">
    <option value="" selected="selected">--- Select Reason ---</option>
  
    </select>
    </div>   
            </div>
              
          </div> 
          
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 refundState">
                    <div class="form-group">
                    <label>Account Holder Name  </label>
                    <div class="form-group">
                    <input type="text" name="account_holder_name" value="" class="form-control" placeholder="ABC">
                    </div>   
                    </div>
                    </div> 
                    
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 refundState">
                    <div class="form-group">
                    <label>Account No  </label>
                    <div class="form-group">
                    <input type="text" name="account_no" value="" class="form-control" placeholder="00100010">
                    </div>   
                    </div>
                    </div> 
                    
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 refundState">
                    <div class="form-group">
                    <label>Bank Name  </label>
                    <div class="form-group">
                    <input type="text" name="bank_name" value="" class="form-control" placeholder="ABC">
                    </div>   
                    </div>
                    </div> 
                    
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 refundState">
                    <div class="form-group">
                    <label>IFSC Code  </label>
                    <div class="form-group">
                    <input type="text" name="ifsc_code" value="" class="form-control" placeholder="IFSC CODE">
                    </div>   
                    </div>
                    </div> 
                    
                     <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 refundState">
                    <div class="form-group">
                    <label>Branch  </label>
                    <div class="form-group">
                    <input type="text" name="branch" value="" class="form-control" placeholder="Branch">
                    </div>   
                    </div>
                    </div> 
          
          
               <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
               <div class="text-right">
              <button class="cancelbtn" type="submit" value="submit" id="originalButton" style="display:none">Submit</button> 
              <button class="cancelbtn" type="button" value="submit" id="fakeButtonButton">Submit</button>
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
<div class="modal fade" id="replace_dialog" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title replace_model_header" >Replace order</h4>
        </div>
        <div class="modal-body replace_model_body">
            
          <form role="form" class="form-element" action="" method="post">
			   @csrf
			   <div class="replace_model_inputs">
			       
			   </div>
			   
			   
                            <div class="m_size_class" style="display:none">
                            <label for="exampleInputEmail1">Men Sizes</label> 
                            <div class="replace_model_body_m_attr_size"></div>
                            </div>
                            
                            <div class="w_size_class" style="display:none">
                            <label for="exampleInputEmail1">Women Sizes</label> 
                            <div class="replace_model_body_w_attr_size"></div>
                            </div>
                            
                    
                    
                    <div class="size_class" style="display:none">
                    <label for="exampleInputEmail1">Sizes</label> 
                    <div class="replace_model_body_attr_size"></div>
                    </div>
                    
                    
                    
                    <div class="color_class" style="display:none">
                    <label for="exampleInputEmail1">Colors</label> 
                    <div class="replace_model_body_attr_color"></div>
                    </div>
    </form>
        </div>
       
      </div>
      
    </div>
  </div>
@endsection

            

    
