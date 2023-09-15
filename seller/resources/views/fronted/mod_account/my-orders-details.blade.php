@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Order Detail</a>
@endsection  
        <?php 
        $parameters = Request::segment(2);
        $parameters_level = base64_decode($parameters);
        
        ?>
<section class="dashbord-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="dashbordlinks">
					<h6 class="fs18 fw600 mb20">My Account</h6>    
					<ul>
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
				</div>    
			</div>  
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div class="dashbordtxt">
					<h6 class="fs18 fw600 mb20">My Order Placement id -{{$master_order->order_no}}</h6> 
				   	<div class="db-2-main-com db-2-main-com-table">
						<div class="orderstabs">
							
							<!-- Tab panes -->
							<div class="tab-content">
							  
                                    <div class="tab-pane fade active in" id="upcoming-orders">
                                    
                                   
                                   <div class="table" id="results">
                                            <div class="theader">
                                                <div class="table_header">S.N.</div>
                                                
        <div class="table_header">Order ID</div>
                                                
                                                <div class="table_header">Product Image</div>
                                                <div class="table_header" style="width:40%;">Product Name</div>
                                                 <div class="table_header">QTY</div>
                                                <div class="table_header">Total Amount</div>
                                                <div class="table_header">Status</div>
                                               
                                            </div>
                                             <?php $i=1; $total=0;
                                             $cod=0;
                                             $shipping_charges=0;
                                             foreach($sub_main_order as $order){
                                                 
                        //  	$total+=($order->product_qty*$order->product_price);
                        
                                $cod+=$order->order_cod_charges;
                    $shipping_charges+=$order->order_shipping_charges; 
                                             
                          $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount-$order->order_wallet_amount;                     
                            $total+=$product_price;
                                                    ?>
                                            <div class="table_row">
<div class="table_small">
<div class="table_cell">No.</div>
<div class="table_cell">{{$i}}</div>
</div>

<div class="table_small">
<div class="table_cell">No.</div>
<div class="table_cell">{{$order->suborder_no}}</div>
</div>
												
                                                <div class="table_small">
                                                <div class="table_cell">Image</div>
                                                <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}" class="" target="_blank">
                                                 
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
                                                   
                                                    <img src="{{$url}}"></a></div>
                                                </div>
                                                    <div class="table_small dashboradtitle">
                                                    <div class="table_cell">Title</div>
                                                    <div class="table_cell textdashboad"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}" target="_blank">{{ucwords($order->product_name)}}</a>
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
                                                    <br>Color: {{$order->color}}
                                                    @endif
                                                   <?php
												   $prd_detail=App\Products::select('products.vendor_id','products.delivery_days')
												   ->where('products.id','=',$order->product_id)
												   ->first();
	   
													?>
													@if ($prd_detail->vendor_id!=0)
														<br><span>Seller: {!!$prd_detail->getProductsVendor()!!}</span>
													@endif
													
													<?php 
													if($prd_detail->delivery_days!=0){
														$t=strtotime("$order->order_date +$prd_detail->delivery_days days");
														$expected_date=date('Y-m-d',$t);
													}else{
														$expected_date=$order->order_date;
													}
													?>
													<br><span>Expected Delivery Date: <?php echo date('d M, Y',strtotime($expected_date));?></span>
                                                    </div>
                                                    </div>
                                            
                                            
                                            <div class="table_small">
                                            <div class="table_cell">Total Amount</div>
                                            <div class="table_cell"> {{$order->product_qty}}</div>
                                            </div>
                                            
                                            <div class="table_small">
                                            <div class="table_cell">Total Amount</div>
                                            <div class="table_cell"><i class="fa fa-inr"></i> {{$product_price}}</div>
                                            </div>
                                            
                                            <div class="table_small">
                                           
                                                  @if($master_order->payment_mode==1)
                                                  
                                                  @if($master_order->txn_id=='')
                                                  Faild
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<em class="processingtext">Processing</em><br>
                                                            <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">
																Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 Invoice Generated
                                                            
                                                                @break
                                                                @case(2)
																Shipped
                                                                
                                                                @break
                                                                
                                                                @case(3)
                                                                Delivered
																<?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
																<a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn orderbtn-bgblack">
											<i class="fa fa-undo" title="Return"></i>
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
                    <a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}">
                                                                Rate This
                                                                </a>
                                    <?php }?>
                                                                
                                                                @break
                                                                
                                                                @case(4)
                                                                Cancelled
                                                                @break
                                                                 @case(5)
                                                                Returned 
                                                                @break
                                                                @endswitch
                                                  @endif
                                                  
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<em class="processingtext">Processing</em><br>
                                                                 <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">
																	 Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 Invoice Generated
                                                            <!--      <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                @case(2)
																Shipped
                                                            <!--     <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                
                                                                @case(3)
                                                                Delivered
																<?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
																<a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn orderbtn-bgblack">
												<i class="fa fa-undo" title="Return"></i>
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
                    <a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}">
                                                                Rate This
                                                                </a>
                                    <?php }?>
                                                                
                                                                @break
                                                                
                                                                @case(4)
                                                                Cancelled
                                                                @break
                                                                 @case(5)
                                                                Returned 
                                                                @break
                                                                @endswitch
                                                  @endif
                                                  
                                                
                                            
                                            </div>
                                            
                                            </div>
											<?php
												
													$i++; 
												} 
											 ?>
											 
                                                    <?php $i=2; foreach($sub_orders as $order){
                    $cod+=$order->order_cod_charges;
                    $shipping_charges+=$order->order_shipping_charges;          
                    $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount-$order->order_wallet_amount;                     
                    $total+=$product_price;
                    	
                                                    ?>
                                            <div class="table_row">
                                                <div class="table_small">
                                                <div class="table_cell">No.</div>
                                                <div class="table_cell">{{$i}}</div>
                                                </div>
												
												<div class="table_small">
                                                <div class="table_cell">Order ID.</div>
                                                <div class="table_cell">{{$order->suborder_no}}</div>
                                                </div>
                                                
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
                                                   
                                                    <img src="{{$url}}"></a></div>
                                                </div>
                                                    <div class="table_small dashboradtitle">
                                                    <div class="table_cell">Title</div>
                                                    <div class="table_cell textdashboad"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" target="_blank">{{ucwords($order->product_name)}}</a>
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
                                                    <br>Color: {{$order->color}}
                                                    @endif
                                                   <?php
												   $prd_detail=App\Products::select('products.vendor_id','products.delivery_days')
												   ->where('products.id','=',$order->product_id)
												   ->first();
	   
													?>
													@if ($prd_detail->vendor_id!=0)
														<br><span>Seller: {!!$prd_detail->getProductsVendor()!!}</span>
													@endif
													
													<?php 
													if($prd_detail->delivery_days!=0){
														$t=strtotime("$order->order_date +$prd_detail->delivery_days days");
														$expected_date=date('Y-m-d',$t);
													}else{
														$expected_date=$order->order_date;
													}
													?>
													<br><span>Expected Delivery Date: <?php echo date('d M, Y',strtotime($expected_date));?></span>
                                                    </div>
                                                    </div>
                                            
                                            
                                            <div class="table_small">
                                            <div class="table_cell">Total Amount</div>
                                            <div class="table_cell"> {{$order->product_qty}}</div>
                                            </div>
                                            
                                            <div class="table_small">
                                            <div class="table_cell">Total Amount</div>
                                            <div class="table_cell"><i class="fa fa-inr"></i> {{$product_price}}</div>
                                            </div>
                                            
                                            <div class="table_small">
                                           
                                                  @if($master_order->payment_mode==1)
                                                  
                                                  @if($master_order->txn_id=='')
                                                  Faild
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<em class="processingtext">Processing</em><br>
                                                            <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">
																Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 Invoice Generated
                                                            
                                                                @break
                                                                @case(2)
																Shipped
                                                                
                                                                @break
                                                                
                                                                @case(3)
                                                                Delivered
																<?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
																<a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn orderbtn-bgblack">
											<i class="fa fa-undo" title="Return"></i>
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
                    <a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}">
                                                                Rate This
                                                                </a>
                                    <?php }?>
                                                                
                                                                @break
                                                                
                                                                @case(4)
                                                                Cancelled
                                                                @break
                                                                 @case(5)
                                                                Returned 
                                                                @break
                                                                @endswitch
                                                  @endif
                                                  
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<em class="processingtext">Processing</em><br>
                                                                 <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">
																	 Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 Invoice Generated
                                                            <!--      <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                @case(2)
																Shipped
                                                            <!--     <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                
                                                                @case(3)
                                                                Delivered
																<?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
																<a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn orderbtn-bgblack">
												<i class="fa fa-undo" title="Return"></i>
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
                    <a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}">
                                                                Rate This
                                                                </a>
                                    <?php }?>
                                                                
                                                                @break
                                                                
                                                                @case(4)
                                                                Cancelled
                                                                @break
                                                                 @case(5)
                                                                Returned 
                                                                @break
                                                                @endswitch
                                                  @endif
                                                  
                                                
                                            
                                            </div>
                                            
                                            </div>
											<?php
												
													$i++; 
												} 
											 ?>
								  </div>
								  <div class="totaldash-main">
										<div class="totaldash">
										  
										  <?php if($master_order->deduct_reward_points!=''){?>
										  <div class="totaldash1">
											Deduct Wallet : <strong><i class="fa fa-inr"></i>  {{round($master_order->deduct_reward_points)}}</strong>
										  </div>
										  <?php }?>
										  <?php if($master_order->coupon_code!=''){?>
										  <div class="totaldash1">
											Coupon Applied ({{$master_order->coupon_code}})  at  {{$master_order->coupon_percent}}% : <strong><i class="fa fa-inr"></i> {{round($master_order->coupon_amount)}}</strong>
										  </div>
										  <?php }?>
										  
			   <?php if($master_order->payment_mode==0){?>
			  <div class="totaldash1">
				COD charges ({{$cod}}) 
			  </div>
			  <?php }?>
										  
										  <div class="totaldash1">
											Grand Total : <strong><i class="fa fa-inr"></i> <?php echo $master_order->grand_total;?></strong>
										  </div>
										  
										  </div>
										</div>
                                    </div>
							
							</div>
						</div>
						
						
					</div>

				</div>    
				<!--
			<div class="no-order text-center">
			<h6 class="fs20 fw600 mb20">Sadly, you haven't placed any orders till now.</h6>    
			<img src="images/no-order.png" alt="">  
			<button type="submit" class="norderbtn" value="submit">Continue Shopping</button>    
			</div>   -->  
			</div>     
		</div>    
	</div>    
    
</section>  
@endsection

            

    
