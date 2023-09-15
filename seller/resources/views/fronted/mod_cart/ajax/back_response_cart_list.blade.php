
 @if (count($cart)>0)
 <div class="col-md-12 col-xs-12"><p class="dosim" id="offershipping"></p></div>
<div class="col-md-8">
                    <form class="form-cart">
                       <div class="db-2-main-com db-2-main-com-table carttbl">
							
							
							<div class="table  " id="results">
							 
							 
										<div class="theader">
											<div class="table_header">Product Name</div>
											<div class="table_header">Unit Price</div>
											<div class="table_header">Qty</div>
											<div class="table_header">SubTotal</div>
											<div class="table_header"> </div>
											<div class="table_header"> </div>
										
										</div>
										
									<?php 
									$out_of_stock=0;
                                    $grand_total=0;
                                    $tax=0;
                                    $sub_total=0;
                                    $shipping_charge=0;
                                     $discount=0;
									?>
							  @foreach($cart as $product)
						<?php  
					        	$current_out_stck=0;
                                      
					        	
                                        $stock = App\ProductAttributes::select('qty')
                                        ->where('size_id','=',$product->size_id)
                                        ->where('color_id','=',$product->color_id)
                                        ->where('product_id','=',$product->prd_id)
                                        ->first();
                                         $quantity = $stock ? $stock->qty : 0;
     
                                            if($quantity < $product->qty) {
                                                $current_out_stck=1;
                                            		$out_of_stock++;
                                            }
					        	?>
							   <div class="table_row" id="cart_item_row_{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}">
								<div class="table_small">
								  <div class="table_cell">Product Name</div>
								  <?php 
								  
								 if( $product->color_id!=0){
                                    $colorImage=DB::table('product_configuration_images')
                                    ->where('product_id',$product->prd_id)
                                    ->where('color_id',$product->color_id)
                                    ->first();
                                    if($colorImage){
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$colorImage->product_config_image;
                                    } else{
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$product->default_image;  
                                    }
                                    
                                    }
                                    else{
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$product->default_image;  
                                    }
								  
									$prd_url= App\Products::getProductDetailUrl($product->name,$product->prd_id,$product->size_id,$product->color_id);
								  ?>
								 
								  <div class="table_cell"><a href="{{$prd_url}}" class="item-photo" target="_blank"><img src="{{$url }}" alt="cart" width="50" height="50" class="img-thumbnail"></a>
                                      <div class="product-name">
									  <a href="{{$prd_url}}" target="_blank">{{$product->name}}
									  </a>
									  <br>
									  
									  	@if ($product->w_size_id!=0)
										  <span>Men Size : {!!App\Products::getAttrName('Sizes',$product->size_id)!!}</span>
										  <br>
									      <span>Women Size : {!!App\Products::getAttrName('Sizes',$product->w_size_id)!!}</span>
										@else
                                            @if ($product->size_id!=0)
                                              <br>
                                            <span>Size : {!!App\Products::getAttrName('Sizes',$product->size_id)!!}</span>
                                          @endif
										@endif
									  
									  
									
										
										@if ($product->color_id!=0)
										  <br>
										  <span>Color : {!!App\Products::getAttrName('Colors',$product->color_id)!!}</span>
										@endif
										
										<br>
									
									
                                            @if($current_out_stck==0)
                                            <br><span class="in-stock"> In stock</span>
                                            @else
                                            <br><span class="out-stock clrred"> Out of stock</span>
                                            @endif()
										<!--<span class="savelater" prd_id='{{$product->prd_id}}'> Save for Later</span>-->
										
										    
									
										<br>
										
										
										<br>
										<span> </span>
									  </div></div>
									  
								</div>
								<div class="table_small">
								<div class="table_cell">Unit Price</div>
								<div class="table_cell"> 
			
			 @if ($product->master_spcl_price!='' && $product->master_spcl_price!=0)
		     <i class="fa fa-rupee"></i> <b><span id="prd_price_0">
		         
		         <?php 
		         
		                             $old_prc=$product->master_price;
								  if ($product->master_spcl_price!='' && $product->master_spcl_price!=0){
									  $prc=$product->master_spcl_price;
								  }else{
									  $prc=$product->master_price;
								  }
								   if($product->color_id==0 && $product->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$product->prd_id)
		     ->where('size_id',$product->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		      $old_prc+=$attr_data->price;
		}
	 if($product->color_id!=0 && $product->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$product->prd_id)
		     ->where('color_id',$product->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($product->color_id!=0 && $product->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$product->prd_id)
		     ->where('color_id',$product->color_id)
		     ->where('size_id',$product->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		         ?>
		         {{ $prc}}
		         
		         </span></b>
		<!--<del><i class="fa fa-rupee"></i>{{$old_prc}}</del>-->
		<!--<span class="offer_txt">{{App\Products::offerPercentage($old_prc,$prc)}}% <span>off</span></span>-->
			@else
			<i class="fa fa-rupee"></i> <b><span id="prd_price_0">
			    
			        <?php 
		         
		                             $old_prc=$product->master_price;
								    if ($product->master_spcl_price!='')
								  {
									  $prc=$product->master_spcl_price;
								  }else{
									  $prc=$product->master_price;
								  }
								   if($product->color_id==0 && $product->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$product->prd_id)
		     ->where('size_id',$product->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		      $old_prc+=$attr_data->price;
		}
	 if($product->color_id!=0 && $product->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$product->prd_id)
		     ->where('color_id',$product->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($product->color_id!=0 && $product->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$product->prd_id)
		     ->where('color_id',$product->color_id)
		     ->where('size_id',$product->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		         ?>
			    {{$prc}}
			    
			    
			    </span></b>
			@endif
								  </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Qty</div>
								  <div class="table_cell"><div class="tb-qty">
                                        <div class="">
                                            <div class="quantity buttons_added">
                                                 <a href="javascript:void(0)" class="sign minus changeQtyOfCartProduct" method="2" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-minus"></i></a>
                                                 <input type="text" value="{{$product->qty}}" title="Qty" class="input-text qty text" size="1" disabled id="qty_field_{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" pattern="[0-9]" >
                                                 <a href="javascript:void(0)" class="sign plus changeQtyOfCartProduct" method="1" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div></div>
								</div>
								<div class="table_small">
								  <div class="table_cell">SubTotal</div>
								  <div class="table_cell"><b><i class="fa fa-inr"></i> 
								  <?php  
								 
				$total=$prc*$product->qty;
				$sub_total+=$total;
				echo $total?></b>
									
									
									</div>
									
								</div>
								   <div class="table_small">
								  <div class="table_cell"> </div>
								  
									
								  <div class="table_cell">
										<div class="deliveryadd">
										<!--<h6>-->
										<!--@if ($product->delivery_days!=0)-->
										<!-- Delivery with in {{$product->delivery_days}} days-->
										<!--@else-->
										<!-- Same Day Delivery-->
										<!--@endif</h6>-->

											
          <!--                          <p class="fw600">@if ($product->shipping_charges!=0)-->
          <!--                        <i class="fa fa-inr"></i>  {{$product->shipping_charges}} <span class="shippingchrgs">Shipping Charges</span> -->
          <!--                          @else-->
          <!--                           Free Delivery-->
										<!--@endif</p>-->
											
									<h6>
                                        @if ($product->return_days!='')
                                        {{$product->return_days}} Days
                                        @else
                                        @endif
									     7 Days Replacement Policy
									 </h6>
										    
										   
									</div>
									   </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Delete</div>
								  <div class="table_cell"><b><a href="javascript:void(0)" class="deleteCartItem" prd_id="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}"><i class="fa fa-times"></i></a></b></div>
								</div>
							  </div>
                                
                              	@endforeach
										@if (count($cart) ==0)
											<div class="table_row">
											 Cart is empty
											 </div>
										@endif
                
				
                         
                                </div>
						   <div class="row mob_fixed">
								<div class="col-md-12 col-xs-12">
									<a href="{{route('index')}}" class="btn-continue mt20"><span>Continue Shopping</span></a>
									
                                        @if($out_of_stock==0)
                                        <a href="{{route('review_order')}}" class="btn-update mt20"> <span>Proceed to checkout</span></a>
                                        @else
                                        <a href="javascript:void(0)" class="btn-update mt20"> <span>Product Out of Stock</span></a>
                                        @endif()
								
								</div>				
							</div>
                                </div>
                    </form>
                </div>
    
<!--                <div class="col-md-4">-->
<!--                    <div class="order-summary">-->
<!--                        <h4 class="title-shopping-cart">Order Summary</h4>-->
<!--                        <div class="checkout-element-content">-->
<!--                            <span class="order-left">Total Products (Tax Incl.):<span><i class="fa fa-inr"></i> {{$sub_total}}</span></span>-->
<!--                            <span class="order-left">Shipping Charges :-->
<!--                            <span><i class="fa fa-inr"></i><strong class="shipping_charge_response">60</strong></span>-->
<!--                            </span>-->
                            
<!--                                <span  class="order-left">-->
<!--                               Total Less Discount: -->
<!--                                <span>-->
<!--                                <i class="fa fa-inr"></i>-->
<!--                                <strong class="discount_reponse">{{$discount}}</strong>-->
<!--                                </span>-->
<!--                                </span>-->
                            
<!--                            <span class="order-left">You Pay:<span><i class="fa fa-inr"></i> -->
<!--							<strong class="grand_total_with_out_tax_response">0</strong>-->
<!--							</span></span>-->
<!--                            <ul>-->
<!--								<li>-->
<!--						<input type="checkbox" id="test1" name="" -->
<!--			   <?php echo ($isActivate)?'checked':'' ;?>>-->
<!--    								<label for="test1">I have promo code</label>-->
<!--									<div id="dvPassport" style="display: none">-->
										
<!--										<input-->
<!--value="<?php echo ($isActivate)?$isActivate['coupon_code']:'' ;?>"-->
<!--                                        class="form-control" -->
<!--                                        type="text"  -->
<!--                                        placeholder="Enter code"-->
<!--                                        id="Coupon_code"-->
<!--										>-->
										
<!--										<span id="CouponMsg_code"></span>-->
<!--										<button type="submit" class="btn-checkout couponApply" 	 index="code" cart_total="{{$sub_total}}">Apply Now</button>-->
<!--									</div>-->
<!--								</li>-->
                               
<!--                                <li>-->

<!--								</li>-->
<!--                            </ul>-->
                            
<!--                        </div>-->
<!--                    </div>-->
					
<!--                </div>-->
                
                <div class="col-md-4">
                    <div class="order-summary">
                        <h4 class="title-shopping-cart">Order Summary</h4>
                        <div class="checkout-element-content">
                            <p class="order-left">Total Products (Tax Incl.)  <span><i class="fa fa-inr"></i> {{$sub_total}}</span></p>
                            <p class="order-left">Shipping Charges : <span><i class="fa fa-inr"></i><strong class="shipping_charge_response">60</strong></span></p>
                          
                            
        <!--<p class="order-left" id="cod_charge_html">COD Charge :<span><i class="fa fa-inr"></i>-->
        <!--<i id="">25</i></span></p>-->
                            
                            
                           
                            
                           
                            
                           
                        </div>
                    </div>
                </div>

@else
	<div class="col-md-12">
		<h3>No Product Added to Cart</h3>
	</div>
@endif


<script type="text/javascript">
	 $(document).ready(function(){
        $("#test1").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
                
            } else {
                $("#dvPassport").hide();
                
            }
        });
          if ($('#test1').is(":checked")) {
                $("#dvPassport").show();
                
            } else {
                $("#dvPassport").hide();
                
            }
    });
</script>