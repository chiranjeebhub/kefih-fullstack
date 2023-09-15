@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Review Order</a>
@endsection   
	
	<section class="checkout-section ">
		<div class="container">
			
			<div class="checoutform_main text-style">
				
				<div class="row">
				    	<div class="col-md-12 noProductIncart" style="display:none;">
						<h3>No Product Added to Cart</h3>
							<a href="{{route('index')}}" class="continue mt10">Continue Shopping</a>
					</div>
    <div class="col-md-12 col-md-12 col-md-8 col-lg-8 productIncart">
            
   
        
        
    <div class="row">
   <div class="cartborder">
    
     <div class="col-md-12">
     
     
     
    <h6 class="fs18 fw600 mb10">Review Order <span class="changeaddress"><a href="{{route('checkout')}}">Change Address</a></span></h6>
       
	 <div class="cartaddres">
     <p><i class="fa fa-map-marker"></i>
 Deliver to <span>
     
            <?php echo ucwords($shipping_address->shipping_name);?> 
            <?php echo $shipping_address->shipping_address;?>,
            <?php echo $shipping_address->shipping_address1;?>
            <?php echo $shipping_address->shipping_address2;?>
            <?php echo $shipping_address->shipping_city;?>
            <?php echo $shipping_address->shipping_state;?> : <?php echo $shipping_address->shipping_pincode;?>
     
     
     </span> </p>       
    </div>
    </div> 
   <div class="col-md-12 col-xs-12"><p class="dosim" id="offershipping"></p></div>
    </div> 
		<div class="col-md-12 col-xs-12 reviewOrderBackmain">
        
        	<div class="reviewOrderBackResponse">
        	
        	</div>
		</div>
  		<div class="col-md-12 col-xs-12">
			<a href="{{route('index')}}" class="continue mt10">Continue Shopping</a>
		</div>
		
    </div>
		<div class="row mt30">
			<div class="col-md-12">
				<div class="cartborder"></div>
			</div>
		</div>
	<div class="row mt30">
		
			<div class="col-md-12 col-xs-12 ">
				<h6 class="fs18 fw600 mb20">Payment Method</h6>
			</div>
		
		<div class="col-md-12">
                <form role="form" class="form-element" action="{{ route('thankyou')}}" method="post" enctype="multipart/form-data">
                @csrf
				<input id="grand_total" name="grand_total" type="hidden"  value="0">
				<input id="tax" name="tax" type="hidden"  value="0">
				<input id="shipping_charges" name="shipping_charges" type="hidden"  value="0">
				<input id="coupon_percent" name="coupon_percent" type="hidden"  value="0">
				<input id="coupon_code" name="coupon_code" type="hidden" class="couponcode_back" value="0">
				<input id="discount_amount" name="discount_amount" type="hidden"  value="0">
				<input id="wallet_amount" name="wallet_amount" type="hidden"  value="0">
   
                <div class="paymnetthod">
                <div class="paymnetthodBox">
                	<div class="checkbox checkbox-circle">
        
                		<input id="radio1" name="payment_mode" checked="" type="radio"  value="0" onclick="payment_method(0)">
                		<label for="radio1">COD (Cash on delivery Charges: Costs <i class="fa fa-inr"></i> 25 Extra)</label> <img align=“absmiddle” src="{{ asset('public/fronted/images/cash-on-delivery.png') }}" class="pull-right" />
                	</div>
                	
                </div>
                <div class="paymnetthodBox">
                	<div class="checkbox checkbox-circle">
            <input id="radio2" name="payment_mode"  type="radio" value="1" onclick="payment_method(1)">
        <label for="radio2">Online Payment</label> <img src="{{ asset('public/fronted/images/online-payment.png') }}" class="pull-right" />
                	</div>
                	
                </div>
                	<!--<div class="paymnetthodBox">
						<div class="checkbox checkbox-circle">
							<input id="checkbox1" type="checkbox" checked name="wallet" class="wallet_button" value="1">
							<label for="checkbox1">Use Phaukat Coins <i class="fa fa-inr"></i> <?php echo $cust_info->total_reward_points;?></label> <i class="fa fa-google-wallet pull-right" aria-hidden="true"></i>

						</div>
						
					</div>-->
                </div>
                <!--<input type="submit" value="Place Order" class="continue mt10">-->
                <div class="places_ord">
					<input type="submit" value="Place Order" class="continue mt10 inStock" >
					<input type="button" value="Product is Out of stock" class="continue mt10 outStock">
					<input type="button" value="Product can not be  delivered in your pincode" class="continue mt10 outofdelivery">									
				</div>
                             
                 					  
                </form>
		</div>
		
	</div>
      	
        
        
  
        
        
        
    </div> 
    <div class="col-md-12 col-md-12 col-md-4 col-lg-4 productIncart shopping-carts">
    <div class="order-summary"><!--ordersummery-->
    <h4 class="title-shopping-cart">ORDER SUMMARY</h4>   
       
    <div class="checkout-element-content">
	<p class="order-left">Total products (tax incl. <!--<i class="fa fa-inr tax_response">0</i>-->): <span><i class="fa fa-inr "></i><strong class="grand_total_with_out_tax_response">0</strong></span></p> 
	<p class="order-left">Shipping Charges: <span><i class="fa fa-inr"></i><strong class="shipping_charge_response">0</strong></span></p>  
    <p class="order-left">Total less Discount: <span><i class="fa fa-inr"></i><strong class="discount_reponse">0</strong></span></p> 
    
    
      <p class="order-left" id="cod_charge_html">COD Charge :<span><i class="fa fa-inr"></i>
        <i id="cod_charge"></i></span></p>
        
    <div class="paymnetthod coinsmain order-left">
		<div class="checkbox checkbox-circle">
			<input id="checkboxwallet1" type="checkbox"  name="wallet" class="wallet_button" value="1">
			<label for="checkboxwallet1">Paukat coins <i class="fa fa-info-circle   phaukatCoinsInfo"></i> </label> 
			<span><i class="fa fa-inr"></i><strong class="reward_points_reponse">0</strong></span>
		</div>
	
		<!--Can be used 20 % of Cart-->
	</div>
    <p class="order-left totalpay">You Pay: <span><i class="fa fa-inr"></i><strong class="grand_total_with_tax_response">0</strong></span></p>
    <ul>
		<li><label class="inline">Enter your coupon code if you have one.</label></li>
		<li><input type="text" class="form-control couponcode_back"  id="Coupon_code" placeholder="coupon code" value=""></li>
		<span id="CouponMsg_code"></span>  
    </ul>
	<button type="submit" id="cartToalReview" class="applybtn couponApply" index="code" cart_total="">Apply</button>  
	
		</div>	
    </div>
       
    </div>    
    </div>
			</div>
			
		</div>
	</section>

@endsection
    

  
  

    
