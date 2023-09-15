@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Track Order</a>
@endsection

 <section class="dashbord-section">
<div class="container">
    
      <?php $i=1; $total=0;
   
	foreach($sub_main_order as $sub_order_details){
   	?>
<div class="row"> 
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="trackorder">
<!--<h6 class="fs18 fw600 mb20">My Order</h6> -->
<div class="db-2-main-com db-2-main-com-table packagetbl row">	
    <div class="col-sm-4 col-md-3 col-xs-12 col-lft0">
    <div class="add-image">
          <?php 
                                                   if($sub_order_details->color_id!=0){
                            $colorwise_images=DB::table('product_configuration_images')
                            ->where('product_id',$sub_order_details->product_id)
                            ->where('color_id',$sub_order_details->color_id)
                            ->first();
                            if($colorwise_images){
                                $url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
                            } else{
                                 $url=URL::to('/uploads/products').'/'.$sub_order_details->default_image; 
                            }
                                                   } else{
                                                       $url=URL::to('/uploads/products').'/'.$sub_order_details->default_image; 
                                                   }
                                                   ?>
						<a href="{{App\Products::getProductDetailUrl($sub_order_details->product_name,$sub_order_details->product_id)}}"> <img src="{{$url}}" alt="">
                        
                        </a>
					</div>
    </div>
    <div class="col-sm-8 col-md-9 col-xs-12">
    <div class="row order-dex">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <h5><a href="{{App\Products::getProductDetailUrl($sub_order_details->product_name,$sub_order_details->product_id)}}">{{ucwords($sub_order_details->product_name)}}</a></h5>
            <ul class="list-inline order-list">
                  <?php 
                  
                   $product_price=($sub_order_details->product_qty*$sub_order_details->product_price)+$sub_order_details->order_shipping_charges+$sub_order_details->order_cod_charges-$sub_order_details->order_coupon_amount-$sub_order_details->order_wallet_amount;                     
                    $total+=$product_price;
                    ?>
                <!--<li><span><i class="fa fa-inr"></i></span>{{$sub_order_details->product_qty*$sub_order_details->product_price}}</li>-->
                 <li><span><i class="fa fa-inr"></i></span>{{$product_price}}</li>
                   
                   
                    
                    @if($sub_order_details->w_size!='')
                                                
                                                    @if($sub_order_details->size!='')
                                                    <li><span>Men Size :</span> {{$sub_order_details->size}}</li>
                                                    @endif
                                                    
                                                    @if($sub_order_details->w_size!='')
                                                   <li><span>Women Size :</span> {{$sub_order_details->w_size}}</li>
                                                  @endif
                        @else
                            @if($sub_order_details->size!=0)
                           <li><span>Size :</span> {{$sub_order_details->size}}</li>
                            @endif
                        @endif()
                              
                                
                                   @if ($sub_order_details->color_id!=0)
                                            <li><span class="colormainbox">Color : <div class="colrboxcart"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$sub_order_details->color_id);?> !important">
                                                </div></span></li>
                                       
                                    @endif
                                    
                    <!-- @if($sub_order_details->color!='')-->
                    <!--  <li><span>Colorfhgfhfhfh :</span> {{$sub_order_details->color}}</li>-->
                    <!--@endif-->
          
            </ul>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-rgt0">
        <p><span class="small">Order Date</span> <?php echo date('d/m/Y',strtotime($sub_order_details->order_date));?></p>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-rgt0">
        <p><span class="small">Order ID</span> {{$sub_order_details->suborder_no}}</p>
        </div>
        </div>
<section class="track-order-section">
    <h4 class="txt-green">
        @switch($sub_order_details->order_status)
            @case(0)
            Confirmed
            @break
            
            @case(1)
            In Process
            @break
            
            @case(2)
           Shipped
            @break
            
            @case(3)
            Delivered
            @break
            
            
            @case(4)
            Canceled
            @break
            
            @case(5)
            Returned
            @break
        @endswitch
      
    </h4>
<div class="track-orderbox">
<!--<h6 class="fs18 fw600 mb20">Track Order</h6>-->     
        <ol class="progtrckr" data-progtrckr-steps="5">
            @if($sub_order_details->order_status==4)
            <li class="progtrckr-done">Cancel <i class="fa fa-refresh"></i></li>
            @endif
            @if($sub_order_details->order_status==5)
            <li class="progtrckr-done">Returned <i class="fa fa-refresh"></i></li>
           @endif
           
            @if($sub_order_details->order_status==0 || $sub_order_details->order_status==1 || $sub_order_details->order_status==2 || $sub_order_details->order_status==3)
            <li class="progtrckr-<?php echo ($sub_order_details->order_updated=='')?'todo':'done'; ?>">  Confirmed<i class="fa fa-check"></i></li>
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_invoice_date=='')?'todo':'done'; ?>"> In Process <i class="fa fa-refresh"></i></li>    
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_onshipping_date=='')?'todo':'done'; ?>"> Shipped <i class="fa fa-slideshare"></i></li>
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_delivered_date=='')?'todo':'done'; ?>"> Delivered <i class="fa fa-truck"></i></li>
           @endif
            
        </ol>     
</div>      
</section>
         </div>
    </div>
</div>  

 
</div>     
</div>  
<?php } ?>

    <?php $i=1;
	foreach($suborders as $sub_order_details){
   	?>
<div class="row"> 
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="trackorder">
<!--<h6 class="fs18 fw600 mb20">My Order</h6> -->
<div class="db-2-main-com db-2-main-com-table packagetbl row">	
    <div class="col-sm-4 col-md-3 col-xs-12 col-lft0">
    <div class="add-image">
          <?php 
                                                   if($sub_order_details->color_id!=0){
                            $colorwise_images=DB::table('product_configuration_images')
                            ->where('product_id',$sub_order_details->product_id)
                            ->where('color_id',$sub_order_details->color_id)
                            ->first();
                            if($colorwise_images){
                                $url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
                            } else{
                                 $url=URL::to('/uploads/products').'/'.$sub_order_details->default_image; 
                            }
                                                   } else{
                                                       $url=URL::to('/uploads/products').'/'.$sub_order_details->default_image; 
                                                   }
                                                   ?>
						<a href="{{App\Products::getProductDetailUrl($sub_order_details->product_name,$sub_order_details->product_id)}}"> <img src="{{$url}}" alt="">
                        
                        </a>
					</div>
    </div>
    <div class="col-sm-8 col-md-9 col-xs-12">
    <div class="row order-dex">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <h5><a href="{{App\Products::getProductDetailUrl($sub_order_details->product_name,$sub_order_details->product_id)}}">{{ucwords($sub_order_details->product_name)}}</a></h5>
            <ul class="list-inline order-list">
                
                <?php 
                 $product_price=($sub_order_details->product_qty*$sub_order_details->product_price)+$sub_order_details->order_shipping_charges+$sub_order_details->order_cod_charges-$sub_order_details->order_coupon_amount-$sub_order_details->order_wallet_amount;                     
                    $total+=$product_price;
                ?>
                <li><span><i class="fa fa-inr"></i></span>{{$product_price}}</li>
                   
                   
                    
                    @if($sub_order_details->w_size!='')
                                                
                                                    @if($sub_order_details->size!='')
                                                    <li><span>Men Size :</span> {{$sub_order_details->size}}</li>
                                                    @endif
                                                    
                                                    @if($sub_order_details->w_size!='')
                                                   <li><span>Women Size :</span> {{$sub_order_details->w_size}}</li>
                                                  @endif
                        @else
                            @if($sub_order_details->size!=0)
                           <li><span>Size :</span> {{$sub_order_details->size}}</li>
                            @endif
                        @endif()
                    
            @if ($sub_order_details->color_id!=0)
                <span class="colormainbox">Color : <div class="colrboxcart"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$sub_order_details->color_id);?> !important">
            </div></span>
            
            @endif
          
            </ul>
        </div>
        <div class="clearfix visible-xs"></div>
        <div class="col-xs-12 col-sm-3 col-md-2 col-rgt0">
        <p><span class="small">Order Date</span> <?php echo date('d/m/Y',strtotime($sub_order_details->order_date));?></p>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-4 col-rgt0">
        <p><span class="small">Order ID</span> {{$sub_order_details->suborder_no}}</p>
        </div>
        </div>
<section class="track-order-section">
    <h4 class="txt-green">
        @switch($sub_order_details->order_status)
            @case(0)
            In Process
            @break
            
            @case(1)
            Invoice generated
            @break
            
            @case(2)
           Shipped
            @break
            
            @case(3)
            Delivered
            @break
            
            
            @case(4)
            Canceled
            @break
            
            @case(5)
            Returned
            @break
        @endswitch
      
    </h4>
<div class="track-orderbox">
<!--<h6 class="fs18 fw600 mb20">Track Order</h6>-->     
        <ol class="progtrckr" data-progtrckr-steps="5">
            @if($sub_order_details->order_status==4)
            <li class="progtrckr-done">Cancel <i class="fa fa-refresh"></i></li>
            @endif
            @if($sub_order_details->order_status==5)
            <li class="progtrckr-done">Returned <i class="fa fa-refresh"></i></li>
           @endif
           
            @if($sub_order_details->order_status==0 || $sub_order_details->order_status==1 || $sub_order_details->order_status==2 || $sub_order_details->order_status==3)
            <li class="progtrckr-<?php echo ($sub_order_details->order_updated=='')?'todo':'done'; ?>"> In Process <i class="fa fa-refresh"></i></li>
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_invoice_date=='')?'todo':'done'; ?>"> Confirmed <i class="fa fa-check"></i></li>    
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_onshipping_date=='')?'todo':'done'; ?>"> Shipped <i class="fa fa-slideshare"></i></li>
            <li class="progtrckr-<?php echo ($sub_order_details->order_detail_delivered_date=='')?'todo':'done'; ?>"> Delivered <i class="fa fa-truck"></i></li>
           @endif
            
        </ol>     
</div>      
</section>
         </div>
    </div>
</div>  

 
</div>     
</div>  
<?php } ?>

</div>    
    
</section>
@endsection

            

    
