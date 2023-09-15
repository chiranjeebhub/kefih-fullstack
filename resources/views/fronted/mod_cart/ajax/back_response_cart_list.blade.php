
 @if (count($cart)>0)
 
<div class="col-sm-12 col-md-12 col-lg-8">
                    <form>
                       <div class="db-2-main-com-table cartTable">
							<div class="table">
                                <!--<div class="theader">
                                    <div class="table_header" style="width:18%;">Image</div>
                                    <div class="table_header" style="width:35%;">Product Name</div>
                                    <div class="table_header">Price</div>
                                    <div class="table_header">Qty</div>
                                    <div class="table_header">Amount</div>
                                    <div class="table_header"> </div>
                                </div>-->
										
									<?php 
									$out_of_stock=0;
                                    $grand_total=0;
                                    $tax=0;
                                    $sub_total=0;
                                 
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
                                <div class="table_small imagCell">
								  <div class="table_cell d-block d-sm-none">Image</div>
								  <?php 
								 
								 if( $product->color_id!=0){
                                    $colorImage=DB::table('product_configuration_images')
                                    ->where('product_id',$product->prd_id)
                                    ->where('color_id',$product->color_id)
                                    ->first();
									
									$productid=DB::table('products')->where('id',$product->prd_id)->first();
									$productskuid=DB::table('product_attributes')->where('product_id',$product->prd_id)->first();
									$imagespathfolder='uploads/products/'.$productid->vendor_id.'/'.$productskuid->sku;
                  
									
                                    if($colorImage){
                                    $url=$imagespathfolder.'/'.$colorImage->product_config_image;
                                    } else{
                                    $url=$imagespathfolder.'/'.$product->default_image;  
                                    }
                                    
                                    }
                                    else{
                                    
                                      $productid=DB::table('products')->where('id',$product->prd_id)->first();
                                     
                                      $productimages=DB::table('product_images')->where('product_id',$product->prd_id)->first();
                                      $imagespathfolder='uploads/products/'.$productid->vendor_id.'/'.$productid->sku;
                                      
                                      $url=$imagespathfolder.'/'.$productimages->image;  
                                    }
								 
									$prd_url= App\Products::getProductDetailUrl($product->name,$product->prd_id,$product->size_id,$product->color_id);
								  ?>
								 
								  <div class="table_cell pro-img-table">
                                      <a href="{{$prd_url}}" class="item-photo" target="_blank"><img src="{{$url }}" alt="cart" width="50" height="50" class="img-thumbnail"></a>
                                  </div>
									  
								</div>
								<div class="table_small">
								  <div class="table_cell">Product Details</div>
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
								 
								  <div class="table_cell">
                                      <div class="product-name">
                                          <div class="prdnamecartpage">
									       <h6><a href="{{$prd_url}}" target="_blank">{{$product->name}}
									  </a></h6>
                                           </div>
                                          
                                          <div class="row">
                                              @if ($product->color_id!=0)
                                            <div class="col-md-4 col-4">
                                                <p class="sizes">Color</p>
                                                <div class="coloroptionbox">
                                                    <ul>
                                                        <li>
                                                        <label class="colrboxcart color1"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$product->color_id);?> !important">
										                  </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                              @endif
                                              <div class="col-md-6 col-8">
                                                    
                                                    <div class="coloroptionbox">
                                                        <ul>
                                                            <li>
                                                                
                                                                @if ($product->w_size_id!=0)
                                                                  <span>Men Size : {!!App\Products::getAttrName('Sizes',$product->size_id)!!}</span>
                                                                  <br>
                                                                  <span>Women Size : {!!App\Products::getAttrName('Sizes',$product->w_size_id)!!}</span>
                                                                @else
                                                                    @if ($product->size_id!=0)
                                                                      
                                                                  <p class="sizes">Size</p> <label class="size1 sizeboxcart">{!!App\Products::getAttrName('Sizes',$product->size_id)!!}</label>
                                                                  @endif
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                          </div>
                                    
									  	
									  
									  
									
										
										
										
										<!--<br>-->
									
									
                                            <!--@if($current_out_stck==0)
                                            <br><span class="in-stock"> In stock</span>
                                            @else
                                            <br><span class="out-stock clrred"> Out of stock</span>
                                            @endif()-->
										<!--<span class="savelater" prd_id='{{$product->prd_id}}'> Save for Later</span>-->
										
										<span> </span>
									  </div></div>
									  
								</div>
								<div class="table_small">
								<div class="table_cell">Price</div>
								<div class="table_cell pricecolr"> 
			
			 @if ($product->master_spcl_price!='' && $product->master_spcl_price!=0)
		     <i class="fa fa-rupee"></i> <span id="prd_price_0">
		         
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
		         
		         </span>
	
			@else
			<i class="fa fa-rupee"></i> <span id="prd_price_0">
			    
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
			    
			    
			    </span>
			@endif
								  </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Quantity</div>
								  <div class="table_cell">
                                      <div class="qty-btn">
                                        
                                        <div id="field1">
                                                 <button type="button" class="sign minus changeQtyOfCartProduct sub" method="2" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-minus"></i></button>
                                                 <input type="text" value="{{$product->qty}}" title="Qty" class="qty-input qty text" size="1" disabled id="qty_field_{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" pattern="[0-9]" >
                                                 <button type="button" class="sign plus changeQtyOfCartProduct add" method="1" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-plus"></i></button>
                                            </div>
                                       
                                    </div></div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Total</div>
								  <div class="table_cell pricecolr"><i class="fa fa-rupee"></i> 
								  <?php  
								 
				$total=$prc*$product->qty;
				$sub_total+=$total;
				echo $total?>
									
									
									</div>
									
								</div>
								   
								<div class="table_small text-center">
								  <div class="table_cell">Delete</div>
								  <div class="table_cell"><a href="javascript:void(0)" class="deleteCartItem" prd_id="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}"><i class="fa fa-trash-o"></i></a></div>
								</div>
							  </div>
                                
                              	@endforeach
										@if (count($cart) ==0)
											<div class="table_row">
											 Cart is empty
											 </div>
										@endif
                
				
                         
                                </div>
						   
                                </div>
                    </form>
    <a href="{{route('index')}}" class="btn btn-warning btn-lg mb20 mt-4"><span>Continue Shopping</span></a>
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
                
                <div class="col-sm-6 col-md-6 col-lg-4 productIncart">
                    <div class="summary">
                        <h3>Order Summary</h3>
                        
                        <div class="Price-Details">
                             <ul class="list-group">
                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Products (Tax Incl.) <span><i class="fa fa-rupee"></i><strong>{{$sub_total}}</strong></span>
                                  </li>
                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response"></strong></span>
                                  </li>
                                 <!--<li class="list-group-item d-flex justify-content-between align-items-center">
                                    COD Charge :<span><i class="fa fa-inr"></i><strong id="cod_charge_html"></strong> </span>
                                  </li>-->
                            </ul>
                            <ul class="list-group totalpay">
                                <?php $total=$sub_total;?>
                                  <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Amount
                                    <span><i class="fa fa-inr"></i> {{$total+$shipping_charge}} </span>
                                  </li>
                                @if($how_many_you_save>0)
                                 <p class="savetextline">You will save <i class="fa fa-rupee"></i>  {{$how_many_you_save}} on this order</p>
                                @endif()
                            </ul>
                            
                             <!--<div class="cartshipingaddbox">
                                <h4>Shipping Address:</h4>
                                <p>Sr no 48/4 Washington DC, United States of America, USA - 1223421</p>
                             </div>
                             <div class="cartshipingaddbox">
                                <h4>Billing Address:</h4>
                                <p>Sr no 48/4 Washington DC, United States of America, USA - 1223421</p>
                             </div>-->

                         </div>
                        
                        <div class="row mob_fixed">
                            <div class="col-md-12 col-xs-12">                              
                                    @php 
                                    $isLoggedIn = Auth::guard('customer')->check();
                                    $loginPopUpClass = ($isLoggedIn)?'':'head_user_login';
                                    @endphp 

                                    @if($out_of_stock==0)
                                    @if(empty($loginPopUpClass))
                                    <a href="{{route('review_order')}}" class="btn btn-warning btn-block btn-lg"> Checkout</a>
                                    @else 
                                     <a href="javascript:void(0);" class="btn btn-warning btn-block btn-lg {{$loginPopUpClass}}"> Checkout</a>

                                    @endif 
                                 
                                    @else
                                    <a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg"> <span>Product Out of Stock</span></a>
                                    @endif()

                            </div>				
                        </div>
                        
                        <div class="checkout-element-content">
                            <!--<p class="order-left">Total Products (Tax Incl.) <span><i class="fa fa-rupee"></i><strong>{{$sub_total}}</strong></span></p>-->
                            <!--<p class="order-left">Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response"></strong></span></p>-->
                          
                            
        <!-- <p class="order-left" id="cod_charge_html">COD Charge :<span><i class="fa fa-inr"></i> -->
        <!--<i id="">25</i></span></p>-->
                        </div>
                        <!--<div class="totalmainbox">
                            <?php //$total=$sub_total;?>
                            <p class="order-left">Total Amount <span><i class="fa fa-rupee"></i><strong>{{$total+$shipping_charge}}</strong></span></p>
                            @if($how_many_you_save>0)
                             <p class="savetextline">You will save <i class="fa fa-rupee"></i>  {{$how_many_you_save}} on this order</p>
                            @endif()
                           
                        </div>-->
                        
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