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
<section class="main_section pt-4 pb-4">
	<div class="container">
		<div class="row">
			  
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="order_list order_details">
					<h6 class="fs18 fw600 mb20">Order Id - {{@$master_order->order_no}}
                        <div class="pull-right mb-4 invoicebtns">
                        <a href="{{route('download-service-invoice',base64_encode($master_order->id))}}" class="btn btn-xs btn-warning">View Service Invoice</a>
                        <a href="{{route('download-service-invoice',base64_encode($master_order->id))}}?type=1" class="btn btn-xs btn-warning ms-1">Download Service Invoice</a>
                       </div>
                    </h6>
                  
                  

				   	<div class="db-2-main-com db-2-main-com-table mt-4">
						<div class="table" id="results">
                                            <div class="theader">
                                                <!--<div class="table_header">S.N.</div>-->
                                                
        <div class="table_header">Sub Order Id</div>
                                                
                                                <div class="table_header" style="width:18%;">Image</div>
                                                <div class="table_header" style="width:40%;">Product Name</div>
                                                 <div class="table_header">Qty</div>
                                                <div class="table_header">Amount</div>
                                                <div class="table_header" style="width:15%;"></div>
                                               
                                            </div>
                                             <?php $i=1; $total=0;
                                             $cod=0;
                                             $shipping_charges=0;
                                             foreach($sub_main_order as $order){
                                                 
                        //  	$total+=($order->product_qty*$order->product_price);
                        
                                $cod+=$order->order_cod_charges;
                    $shipping_charges+=$order->order_shipping_charges; 
                    // $product_price=(intval(@$order->details_qty)*intval(@$order->details_price))+intval(@$order->details_shipping_charges)+intval(@$order->details_cod_charges)-intval(@$order->details_cpn_amt)-intval(@$order->details_wlt_amt)+intval(@$order->slot_price); 
            
                        //   $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount-$order->order_wallet_amount;                     
                        $product_price=($order->product_qty*$order->product_price)+$order->order_shipping_charges+$order->order_cod_charges-$order->order_coupon_amount;                     
   
                        $total+=$product_price;
                                                    ?>
                                            <div class="table_row">
<!--<div class="table_small">
<div class="table_cell">No.</div>
<div class="table_cell">{{$i}}</div>
</div>-->

<div class="table_small orderidmrgn">
<div class="table_cell">No.</div>
<div class="table_cell orderidmrgncell">{{$order->suborder_no}}</div>
</div>
												
                                                <div class="table_small">
                                                <div class="table_cell">Image</div>
                                                <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}" class="" target="_blank">
                                                 
                                                   <?php 

                                    $producdetails=DB::table('products')->where('id',$order->product_id)->first();
                                    $productskuid=DB::table('product_attributes')->where('product_id',$order->product_id)->first();
                                    $imagespathfolder='uploads/products/'.$producdetails->vendor_id.'/'.$productskuid->sku;
                                    $baseimagespathfolder='uploads/products/'.$producdetails->vendor_id.'/'.$producdetails->sku;
                                    $url = '';
                                                   if($order->color_id!=0){
                                                    $colorwise_images=DB::table('product_configuration_images')
                                                    ->where('product_id',$order->product_id)
                                                    ->where('color_id',$order->color_id)
                                                    ->first();
                                                    if($colorwise_images){
                                                        $url=URL::to($imagespathfolder).'/'.$colorwise_images->product_config_image;
                                                    } else{
                                                        //  $url=URL::to($imagespathfolder).'/'.$order->default_image; 
                                                    }
                                                   } else{
                                                    //    $url=URL::to($imagespathfolder).'/'.$order->default_image;
                                                    $productimages=DB::table('product_images')->where('product_id',$order->product_id)->first();
                                                    if(!empty($productimages->image)){
                                                        $url=URL::to($baseimagespathfolder).'/'.$productimages->image;
                                                    }

                                                   }

                                                   ?>
                                                   
                                                    <img src="{{$url}}"></a></div>
                                                </div>
                                                    <div class="table_small dashboradtitle">
                                                    <div class="table_cell">Title</div>
                                                    <div class="table_cell textdashboad "><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}" target="_blank">
                                                        <div class="prdnamecartpage">{{ucwords($order->product_name)}}</div></a>
                                                    
                                                    
                                                @if($order->w_size!='')
                                                
                                                    @if($order->size!='')
                                                    
                                                    Men Size : {{$order->size}}
                                                    @endif
                                                    
                                                    @if($order->w_size!='')
                                                    <br>
                                                    Women Size :{{$order->w_size}}
                                                    @endif
                                              @else
                                                    @if($order->size!=0)
                                                    <br>
                                                        <span class="sizemainbox">Size <div class="sizeboxcart">{{$order->size}}</div></span>
                                                    @endif
                                                @endif()
                                                       
                                                        
                    
                                  @if ($order->color_id!=0)
                                            <span class="colormainbox">Color : <div class="colrboxcart"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$order->color_id);?> !important">
									    </div></span>
                                       
                                    @endif
                                                   <?php
												   $prd_detail=App\Products::select('products.vendor_id','products.delivery_days')
												   ->where('products.id','=',$order->product_id)
												   ->first();
	   
													?>
													@if ($prd_detail->vendor_id!=0)
														<span class="sizemainbox">Seller: {!!$prd_detail->getProductsVendor()!!}</span>
													@endif
													
													<?php 
													if($prd_detail->delivery_days!=0){
														$t=strtotime("$order->order_date +$prd_detail->delivery_days days");
														$expected_date=date('Y-m-d',$t);
													}else{
														$expected_date=$order->order_date;
													}
													?>
													<span class="sizemainbox">Expected Delivery Date: <?php echo date('d/m/Y',strtotime($expected_date));?></span>
                                                        
                                                      <div class="track-order-section">
                                                        
                                                        <div class="track-orderbox">
                                                        <!--<h6 class="fs18 fw600 mb20">Track Order</h6>-->  
                                                        
                                            <ol class="progtrckr" data-progtrckr-steps="5">
                                            @if($order->order_status==4)
                                            <li class="progtrckr-done">Cancel <i class="fa fa-refresh"></i></li>
                                            @endif
                                            @if($order->order_status==5)
                                            <li class="progtrckr-done">Returned <i class="fa fa-refresh"></i></li>
                                            @endif
                                            
                                            @if($order->order_status==0 || $order->order_status==1 || $order->order_status==2 || $order->order_status==3)
                                            <li class="progtrckr-<?php echo ($order->order_updated=='')?'todo':'done'; ?>"> Confirmed <i class="fa fa-check"></i></li>
                                            <li class="progtrckr-<?php echo ($order->order_detail_invoice_date=='')?'todo':'done'; ?>"> In Process <i class="fa fa-refresh"></i></li>    
                                            <li class="progtrckr-<?php echo ($order->order_detail_onshipping_date=='')?'todo':'done'; ?>"> Shipped <i class="fa fa-slideshare"></i></li>
                                            <li class="progtrckr-<?php echo ($order->order_detail_delivered_date=='')?'todo':'done'; ?>"> Delivered <i class="fa fa-truck"></i></li>
                                            @endif
                                            
                                            </ol> 
        
                                                        </div>      
                                                        </div>  
                                                        
                                                    </div>
                                                    </div>
                                            
                                            
                                            <div class="table_small orderidmrgn">
                                            <div class="table_cell">Qty</div>
                                            <div class="table_cell orderidmrgncell"> {{$order->product_qty}}</div>
                                            </div>
                                            
                                            <div class="table_small orderidmrgn">
                                            <div class="table_cell">Amount</div>
                                            <div class="table_cell orderidmrgncell"><i class="fa fa-rupee"></i> {{$product_price}}</div>
                                            </div>
                                            
                                            <div class="table_small orderidmrgn">
                                                <div class="table_cell"> </div>
                                                
                                                <div class="table_cell orderidmrgncell">
                                                  @if(@$master_order->payment_mode==1)
                                                  
                                                  @if(@$master_order->txn_id=='')
                                                  Faild
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<!--<em class="processingtext">Processing</em><br>-->
                                                            <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="cancelicnbtnxx btn btn-xs btn-warning">
																 Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 <br>
																 In Process
                                                            
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
                                                    <br>
								        <a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn btn btn-xs btn-warning mt-2">Return</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
 <a class="orderbtn btn btn-xs btn-warning mt-2" href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}" title=""><i class="fa fa-star"></i> Rate This</a>
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
																<!--<em class="processingtext">Processing</em><br>-->
                                                                 <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="cancelicnbtnxx btn btn-xs btn-warning" style="margin-top:0;">
																	 Cancel </a>
                                                                @break
                                                                 @case(1)
																 In Process
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
                                          ?><br>
								            <a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn btn btn-xs btn-warning mt-2">
												Return</a>
                                                    <?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
<a class="orderbtn btn btn-xs btn-warning mt-2" href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}"><i class="fa fa-star"></i> Rate This </a>
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
                                                <!--<div class="table_small">
                                                <div class="table_cell">No.</div>
                                                <div class="table_cell">{{$i}}</div>
                                                </div>-->
												
												<div class="table_small orderidmrgn">
                                                <div class="table_cell">Sub Order ID</div>
                                                <div class="table_cell orderidmrgncell">{{$order->suborder_no}}</div>
                                                </div>
                                                
                                                <div class="table_small">
                                                <div class="table_cell">Image</div>
                                                <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" class="" target="_blank">
                                                 
                                                   <?php 
                                                   /*
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
                                                   }*/
                                                   ?>


                                                    <?php
                                                    
                                                    $producdetails=DB::table('products')->where('id',$order->product_id)->first();
                                    $productskuid=DB::table('product_attributes')->where('product_id',$order->product_id)->first();
                                    $imagespathfolder='uploads/products/'.$producdetails->vendor_id.'/'.$productskuid->sku;
                                    $baseimagespathfolder='uploads/products/'.$producdetails->vendor_id.'/'.$producdetails->sku;
                                    $url = '';
                                                   if($order->color_id!=0){
                                                    $colorwise_images=DB::table('product_configuration_images')
                                                    ->where('product_id',$order->product_id)
                                                    ->where('color_id',$order->color_id)
                                                    ->first();
                                                    if($colorwise_images){
                                                        $url=URL::to($imagespathfolder).'/'.$colorwise_images->product_config_image;
                                                    } else{
                                                        //  $url=URL::to($imagespathfolder).'/'.$order->default_image; 
                                                    }
                                                   } else{
                                                    //    $url=URL::to($imagespathfolder).'/'.$order->default_image;
                                                    $productimages=DB::table('product_images')->where('product_id',$order->product_id)->first();
                                                    if(!empty($productimages->image)){
                                                        $url=URL::to($baseimagespathfolder).'/'.$productimages->image;
                                                    }

                                                   }
                                                    
                                                    ?>
                                                   
                                                    <img src="{{$url}}"></a></div>
                                                </div>
                                                    <div class="table_small dashboradtitle">
                                                    <div class="table_cell">Title</div>
                                                    <div class="table_cell textdashboad"><a href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id)}}" target="_blank">
                                                        <div class="prdnamecartpage">{{ucwords($order->product_name)}}</div></a>
                                                    
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
                                              
                                              
                                                   @if($order->size!=0)
                                                    <br>
                                                        <span class="sizemainbox">Size <div class="sizeboxcart">{{$order->size}}</div></span>
                                                    @endif
                                                @endif()
                                                       
                                                        
                    
                                  @if ($order->color_id!=0)
                                            <span class="colormainbox">Color : <div class="colrboxcart"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$order->color_id);?> !important">
									    </div></span>
                                       
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
                                                    $expected_date = '';
													if($prd_detail->delivery_days!=0){
														$t=strtotime("$order->order_date +$prd_detail->delivery_days days");
														$expected_date=date('Y-m-d',$t);
													}else{
														// $expected_date=$order->order_date;
													}
													?>

                                                    @if(!empty($expected_date))
													<br><span>Expected Delivery Date: <?php echo date('d/m/y',strtotime($expected_date));?></span>
                                                    @endif 

                                                        <div class="track-order-section">
                                                        
                                                        <div class="track-orderbox">
                                                        <!--<h6 class="fs18 fw600 mb20">Track Order</h6>-->  
                                                        
                                            <ol class="progtrckr" data-progtrckr-steps="5">
                                            @if($order->order_status==4)
                                            <li class="progtrckr-done">Cancel <i class="fa fa-refresh"></i></li>
                                            @endif
                                            @if($order->order_status==5)
                                            <li class="progtrckr-done">Returned <i class="fa fa-refresh"></i></li>
                                            @endif
                                            
                                            @if($order->order_status==0 || $order->order_status==1 || $order->order_status==2 || $order->order_status==3)
                                            <li class="progtrckr-<?php echo ($order->order_updated=='')?'todo':'done'; ?>"> Confirmed <i class="fa fa-refresh"></i></li>
                                            <li class="progtrckr-<?php echo ($order->order_detail_invoice_date=='')?'todo':'done'; ?>"> In Process <i class="fa fa-check"></i></li>    
                                            <li class="progtrckr-<?php echo ($order->order_detail_onshipping_date=='')?'todo':'done'; ?>"> Shipped <i class="fa fa-slideshare"></i></li>
                                            <li class="progtrckr-<?php echo ($order->order_detail_delivered_date=='')?'todo':'done'; ?>"> Delivered <i class="fa fa-truck"></i></li>
                                            @endif
                                            
                                            </ol> 
        
                                                        </div>      
                                                        </div> 
                                                        
                                                    </div>
                                                    </div>
                                            
                                            
                                            <div class="table_small orderidmrgn">
                                            <div class="table_cell">Qty</div>
                                            <div class="table_cell orderidmrgncell"> {{$order->product_qty}}</div>
                                            </div>
                                            
                                            <div class="table_small orderidmrgn">
                                            <div class="table_cell">Amount</div>
                                            <div class="table_cell orderidmrgncell"><i class="fa fa-rupee"></i> {{$product_price}}</div>
                                            </div>
                                            
                                            <div class="table_small orderidmrgn">
                                                <div class="table_cell"></div>
                                                <div class="table_cell orderidmrgncell">
                                                  @if($master_order->payment_mode==1)
                                                  
                                                  @if($master_order->txn_id=='')
                                                  Faild
                                                  @else
                                                  @switch($order->order_status)
                                                                @case(0)
																<!--<em class="processingtext">Processing</em><br>-->
                                                            <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">
																<i class="fa fa-times" title="Cancel"></i> Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 In Process
                                                            
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
                                          ?><br>
								            <a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn btn btn-xs btn-warning mt-2">
											Return
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
<a class="orderbtn btn btn-xs btn-warning mt-2" href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}"><i class="fa fa-star"></i> Rate This </a>
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
																<!--<em class="processingtext">Processing</em><br>-->
                                                                 <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="cancelicnbtnxx btn btn-xs btn-danger" style="margin-top:0;">
																	 Cancel
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            </a>
                                                                @break
                                                                 @case(1)
																 <!--Invoice Generated-->
                                                            <!--      <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                @case(2)
																<!--Shipped-->
                                                            <!--     <a href="{{ route('cancel_order',base64_encode($order->id)) }}"  class="orderbtn orderbtn-bgblack">-->
                                                            <!--<i class="fa fa-times" title="Cancel"></i>-->
                                                            <!--</a>-->
                                                                @break
                                                                
                                                                @case(3)
                                                                <!--Delivered-->
																<?php
                                         $data=APP\Products::productDetails($order->product_id); 
                                         $days= $data->return_days;
                                         $return_date= date('Y-m-d', strtotime($order->order_updated. ' + '.$days.' days'));
                                       $today= date('Y-m-d');
                                       if($today<$return_date){
                                          ?>
																<a href="{{ route('return_refund_order',base64_encode($order->id)) }}" class="orderbtn btn btn-xs btn-warning mt-2">
												<i class="fa fa-undo" title="Return"></i>
												</a>
												<?php } else{?>
                                          <span>Return Days Expires</span>
                                          <br>
  <a class="orderbtn btn btn-xs btn-warning mt-2" href="{{App\Products::getProductDetailUrl($order->product_name,$order->product_id,$order->size_id,$order->color_id)}}"><i class="fa fa-star"></i> Rate This</a>
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
                                            
                                            </div>
											<?php
												
													$i++; 
												} 
											 ?>
								  </div>
								  <div class="totaldash-main">
										<div class="totaldash">
										  
										  <?php
                                          if(!empty($master_order)){
                                          if($master_order->deduct_reward_points!=''){?>
										  <div class="totaldash1">
											<h5>Deduct Wallet: <i class="fa fa-rupee"></i>{{round(@$master_order->deduct_reward_points)}}</h5>
										  </div>
										  <?php }?>
										  <?php if($master_order->coupon_code!=''){?>
										  <div class="totaldash1">
											<h5>Coupon Applied ({{@$master_order->coupon_code}})  at  {{@$master_order->coupon_percent}}%: <i class="fa fa-rupee"></i>{{@round($master_order->coupon_amount)}}</h5>
										  </div>
										  <?php }
                                          }?>
										  
			   <?php if(@$master_order->payment_mode==0){?>
			  <div class="totaldash1" style="border-top:none;">
                  <!-- <h5>COD Charges: <i class="fa fa-rupee"></i>{{$cod}}</h5> -->
                  <h5>Service Charges: <i class="fa fa-rupee"></i>{{$master_order->service_charge}}</h5>
                  
                  <h5>Delivery Charges: <i class="fa fa-rupee"></i>{{$shipping_charges}}</h5>
			  </div>
			  <?php } ?>
									<?php if(@$master_order->slot_price!=''){?>
                    <div class="totaldash1" style="border-top:none;">
					<h5>Discount: <i class="fa fa-rupee"></i>{{round($master_order->slot_price)}}</h5>
                    </div>
					<?php  } ?>		  
										  <div class="totaldash1 grandtyal">
											Grand Total: <i class="fa fa-rupee"></i><?php // echo $master_order->grand_total;
                                            echo $total+$master_order->service_charge; ?>
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

            

    
