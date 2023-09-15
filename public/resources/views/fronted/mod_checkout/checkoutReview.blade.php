@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Review Order</a>
@endsection   
		<?php 
$timeslot=DB::table('tbl_timeslot')->where('status',1)->get();

?>
	<section class="checkout-section ">
		<div class="container">
			  <!--@include('admin.includes.session_message') -->
			<div class="checoutform_main text-style">
				<form role="form" class="form-element" action="{{ route('thankyou')}}" method="post" enctype="multipart/form-data" name="quotation">
				<div class="row">
				    	<div class="col-md-12 noProductIncart" style="display:none;">
						<h3>No Product Added to Cart</h3>
							<a href="{{route('index')}}" class="btn btn-danger btn-lg btn-lg-14 mt10">Continue Shopping</a>
					</div>
                    
                    
    <div class="col-md-12 col-md-12 col-md-8 col-lg-8 productIncart">
            
   
        
        
    <div class="row">
   <div class="cartborder">
    
     <div class="col-md-12">
     
     <div class="review_ordr_box">
         @if($shipping_address)
        <div class="review_ordr_left">
            <h4 class="title-shopping-cart">Deliver to</h4>
            <div class="addinfo">
                <p class="nameline"><?php echo ucwords($shipping_address->shipping_name);?></p>
                <p><?php echo $shipping_address->shipping_address1;?>, <?php echo $shipping_address->shipping_address2;?>, <?php echo $shipping_address->shipping_city;?>, <?php echo $shipping_address->shipping_state;?> : <?php echo $shipping_address->shipping_pincode;?></p>
            </div>
        </div>
        @endif()
         <div class="review_ordr_center hidden-xs"></div>
        <div class="review_ordr_right">
            <div class=" ">
                <a href="{{route('checkout')}}">
                    <img src="{{ asset('public/fronted/images/plus-checkout.png') }}" />
                    <span class="changeaddtext">
                           @if($shipping_address)
                           Change Address
                           @else
                           Add Address
                           @endif()
                        </span>
                </a>
            </div>
        </div>
     </div>
     
    <!--<h6 class="fs18 fw600 mb10">Review Order <span class="changeaddress"><a href="{{route('checkout')}}">Change Address</a></span></h6>-->
      
    </div> 
   <!--<div class="col-md-12 col-xs-12"><p class="dosim" id="offershipping"></p></div>-->
    </div> 
		<div class="col-md-12 col-xs-12 reviewOrderBackmain">
            <div class="checkoutdatalistbox">
                <div class="db-2-main-com db-2-main-com-table carttbl">
                    <div class="reviewOrderBackResponse">
                        
                    </div>
                </div>
            </div>
		</div>
  		<div class="col-md-12 col-xs-12">
			<a href="{{route('index')}}" class="btn btn-danger btn-lg btn-lg-14 mt10">Continue Shopping</a>
		</div>
		
    </div>
	<div class="row mt30" style="display:none;">
		
			<div class="col-md-12 col-xs-12 ">
				<h6 class="fs18 fw600 mb20">Choose Delivery Slot</h6>
			</div>
		
		<div class="col-md-12">
                
                @csrf
                
                 <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<ul class="timeslotcheckout">
					<?php 

		$date=date('Y-m-d');
		$day=date('d');
		$month=date('M');
		$month1=date('m');
		$year=date('Y');
		$start_date = date('Y-m-d');
		for ($i = 0; $i <7; $i++) 
		{

		$end_date= date('d-m-Y', strtotime($start_date. ' + '.$i.'days'));
		$timestamp = strtotime($i."-".$month1."-".$year);

		$date = '2014-02-25';
		$dt=date('D', strtotime($end_date));

		$day = date('l', $timestamp);

		$datecon = $i." ".$month." ".$year;
		$datecon1 = $i."-".$month."-".$year; 

		$selval =$dt."_".$end_date;
		if((date("ha")=='11AM')){
			
	     }
		?>
						
						<li class="<?php echo ($i==0)?'active':'';?>"><a href="javascript:void(0);" onclick="changeDelivery('<?php echo $dt.'_'.$end_date; ?>')"><?php echo $dt; ?><span><?php echo $end_date;?></span></a></li>
						
						<?php if($i==0){
						?>
	<input type="hidden" value="<?php echo $dt.'_'.$end_date; ?>" class="form-control deliveryday" name="delivery_date"> 
	 
				<?php } } ?>										
					</ul>
					
					<ul class="timeslotoptn paymnetthod">
					<?php $j=0; $city_html='';
					
                       foreach($timeslot as $trow){
            
            
            
        	$pint= explode("-",$trow->name);
        	$price =($trow->price)?"<i class='fa fa-rupee'></i>".$trow->price:'';
        	$price1 =($trow->price)?$trow->price:'0';
        	$chk = ($j==0)?'checked':'vcvv';  //checked=""checked
          $start=$pint[0];
         $end_time= $pint[1];
          $fixed= "4 PM";
         
          $timewise=(date("ha")!='11AM')?'':'';
		  
          
          $start = new \DateTime($start);  
          $end = new \DateTime($end_time);  
          $start_time = $start->format('H:i');
          $end_time = $end->format('H:i');
          
          $fixed = new \DateTime($fixed);  
          $fixtime_time = $fixed->format('H:i');
         
          
           if(strtotime(date('H:i')) >= strtotime($fixtime_time)){
               $city_html='';
              $city_html.='<div class="notdeliveryslot"><p>Oops! No slot available for today</p></div>';
           }
      else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
      // else if(strtotime(date('H:i')) >= strtotime($start_time) || strtotime(date('H:i')) <= strtotime($end_time)){
       	$city_html.='<li><label class="common-customCheckbox vertical-filters-label">
                    	<input type="radio" class="price-input delivery_time" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>
                    	'.$trow->name."  ".$price. 
                       '<div class="common-checkboxIndicator"></div></label></li>';
                }
           else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){    
      //   else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){   
       	$city_html.='<li><label class="common-customCheckbox vertical-filters-label">
                    	<input type="radio" class="price-input delivery_time" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>
                    	'.$trow->name."  ".$price. 
                       '<div class="common-checkboxIndicator"></div></label></li>';
                }
         else{
              
               //  $city_html.='<div class="notdeliveryslot"><p>Oops! No slot available for today</p></div>';     
                }
          $j++; 
           
        }         
        echo $city_html;           
                            
        ?>
        	
        </ul>
				</div>
				
			</div>
                    
                
                             
                
		</div>
		
	</div>
      	
        
        
  
        
        
        
    </div> 
    <div class="col-md-12 col-md-12 col-md-4 col-lg-4 productIncart shopping-carts">
    <div class="order-summary"><!--ordersummery-->
    <h4 class="title-shopping-cart">Order Summary</h4>   
       
    <div class="checkout-element-content couponbox">
        
        <ul>
            <li><label class="inline">Enter your coupon code/Promo code | <a href="{{route('couponlist')}}" class="badge" title="View all vailable coupon codes" target="_blank">View</a></label></li>
            <li><input type="text" class="form-control couponcode_back"  id="Coupon_code" placeholder="Coupon / Promo code" value=""></li>
            <span id="CouponMsg_code"></span>  
        </ul>
        <button type="submit" id="cartToalReview" class="applybtn couponApply" index="code" cart_total="">Apply</button> 

        <button style="display:none;" type="button" id="removeCopuonapply">Remove Coupon</button> 


        </div>
    <div class="checkout-element-content"> 
	<p class="order-left">Amount (<i class="cart_count">2</i> items) Tax Incl. <span><i class="fa fa-rupee"></i><strong class="grand_total_with_out_tax_response">0</strong></span></p> 
	  
	 <p class="order-left slothide">Slot Price <span><i class="fa fa-rupee"></i><strong class="slodedisplay"></strong></span></p>
    <p class="order-left">Less Discount <span><i class="fa fa-rupee"></i><strong class="discount_reponse">0</strong></span></p> 
    <p class="order-left">Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response">0</strong></span></p>
    
      <p class="order-left" id="cod_charge_html">COD Charge <span><i class="fa fa-rupee"></i><strong id="cod_charge"></strong></span></p>
        
    
        
	
		</div>
    <div class="paymnetthod coinsmain order-left">
		<div class="checkbox checkbox-circle">
			<input id="checkboxwallet1" type="checkbox"  name="wallet" class="wallet_button" value="1">
			<label for="checkboxwallet1">Coins <i class="fa fa-info-circle   phaukatCoinsInfo"></i> </label> 
			<span><i class="fa fa-rupee"></i><strong class="reward_points_reponse">0</strong></span>
		</div>
	
	</div> 
        <div class="totalmainbox totalmainboxcheckout">
            <p class="order-left">You Pay <span><i class="fa fa-rupee"></i><strong class="grand_total_with_tax_response">0</strong></span></p>
            <p class="savetextline">You will save <i class="fa fa-rupee"></i> <span class="total_save"></span> on this order</p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="paymentrightcheckout">
                <h6 class="fs18 fw600 mb20">Payment Method</h6>
                    
				<input id="grand_total" name="grand_total" type="hidden"  value="0">
				<input id="tax" name="tax" type="hidden"  value="0">
				<input id="shipping_charges" name="shipping_charges" type="hidden"  value="0">
				<input id="coupon_percent" name="coupon_percent" type="hidden"  value="0">
				<input id="coupon_code" name="coupon_code" type="hidden" class="couponcode_back" value="0">
				<input id="discount_amount" name="discount_amount" type="hidden"  value="0">
				<input id="wallet_amount" name="wallet_amount" type="hidden"  value="0">
   <input  name="slot_price" type="hidden" class="inputslotprice" value="0" readonly="">
                <div class="paymnetthod">
                <div class="paymnetthodBox">
                	<div class="checkbox checkbox-circle">
        
                		<input id="radio1" name="payment_mode" checked="" type="radio"  value="0" onclick="payment_method(0)">
                		<label for="radio1">COD (Cash on delivery)</label> <!--<img align=“absmiddle” src="{{ asset('public/fronted/images/cash-on-delivery.png') }}" class="pull-right" />-->
                	</div>
                	
                </div>
                <div class="paymnetthodBox">
                    <div class="checkbox checkbox-circle">
                        <input id="radio2" name="payment_mode"  type="radio" value="1" onclick="payment_method(1)">
                    <label for="radio2">Online Payment</label> <img src="{{ asset('public/fronted/images/online-payment.png') }}" class="pull-right" />
                                </div>

                            </div>
                                <!--<div class="paymnetthodBox">-->
                                <!--    <div class="checkbox checkbox-circle">-->
                                <!--        <input id="checkbox1" type="checkbox" checked name="wallet" class="wallet_button" value="1">-->
                                <!--        <label for="checkbox1">Use Coins <i class="fa fa-inr"></i> <?php echo $cust_info->total_reward_points;?></label> <i class="fa fa-google-wallet pull-right" aria-hidden="true"></i>-->

                                <!--    </div>-->

                                <!--</div>-->
                            </div>
                            <!--<input type="submit" value="Place Order" class="continue mt10">-->
                            <div class="places_ord">
                                <input type="submit" value="Place Order" id="BookingBtn" class="btn btn-danger btn-block btn-lg mt10 inStock" >
                                <input type="button" value="Product is Out of stock" class="btn btn-danger btn-block btn-lg mt10 outStock">
                                <input type="button" value="Product can not be  delivered in your port code" class="btn btn-danger btn-block btn-lg mt10 outofdelivery">									
                            </div>
                </div>
            </div>
        </div>
        
    </div>
       
    </div>    
    </div>
                </form>
			</div>
			
		</div>
	</section>

@endsection
    

  
  

    
