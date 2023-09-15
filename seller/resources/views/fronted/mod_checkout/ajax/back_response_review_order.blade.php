 <?php
  $out_of_stock=0;
    foreach($cart_data  as $row) {
        
        	$current_out_stck=0;
        
					        	
                                        $stock = App\ProductAttributes::select('qty')
                                        ->where('size_id','=',$row->size_id)
                                        ->where('color_id','=',$row->color_id)
                                        ->where('product_id','=',$row->prd_id)
                                        ->first();
                                         $quantity = $stock ? $stock->qty : 0;
     
                                            if($quantity < $row->qty) {
                                                $current_out_stck=1;
                                            		$out_of_stock++;
                                            }
		  $old_prc=$row->master_price;
			if ($row->master_spcl_price!='')
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
        if($row->color_id==0 && $row->size_id!=0){
        
                $attr_data=DB::table('product_attributes')
                ->where('product_id',$row->prd_id)
                ->where('size_id',$row->size_id)
                ->first();
                $prc+=$attr_data->price;
                $old_prc+=$attr_data->price;
        }
                if($row->color_id!=0 && $row->size_id==0){
                    $attr_data=DB::table('product_attributes')
                    ->where('product_id',$row->prd_id)
                    ->where('color_id',$row->color_id)
                    ->first();
                    $prc+=$attr_data->price;
                    $old_prc+=$attr_data->price;
                }
	     if($row->color_id!=0 && $row->size_id!==0){
        $attr_data=DB::table('product_attributes')
        ->where('product_id',$row->prd_id)
        ->where('color_id',$row->color_id)
        ->where('size_id',$row->size_id)
        ->first();
        $prc+=$attr_data->price;
        $old_prc+=$attr_data->price;
		}
        	$subtotal=$prc*$row->qty;
				
        
     if( $row->color_id!=0){
                                    $colorImage=DB::table('product_configuration_images')
                                    ->where('product_id',$row->prd_id)
                                    ->where('color_id',$row->color_id)
                                    ->first();
                                    if($colorImage){
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$colorImage->product_config_image;
                                    } else{
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$row->default_image;  
                                    }
                                    
                                    }
                                    else{
                                    $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$row->default_image;  
                                    }
    ?>
    <div  class=" ">
    <div class="col-md-8 col-sm-7 col-xs-12">
    <div class="cartimg">
    <img src="{{$url}}" alt="" width="100" height="100">    
    </div> 
    <div class="cartdetails">  
    <h6>{{$row->name}}</h6>  
    <!--<span>Seller: SuperComNet</span>  -->
    <h4>
            @if ($row->master_spcl_price!='')
            <span id="prd_price_0"><i class="fa fa-rupee"></i> {{$prc}}</span>
            <del><i class="fa fa-rupee"></i>{{$old_prc}}</del>
            <span class="offer_txt">{{App\Products::offerPercentage($old_prc,$prc)}}% <span>off</span></span>
            @else
            <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$old_prc}}</span>
            @endif
    </h4> 
     <span>Qty {{$row->qty}}</span> 
   
    	<span>
                                    @if ($row->delivery_days!=0)
                                     Delivery with in {{$row->delivery_days}} days
                                    @else
                                     Same Day Delivery
                                    @endif
										    
										    </span>
										    
									
										<br>
										
										<span>
                                    @if ($row->shipping_charges!=0)
                                    
            <div class="checkbox checkbox-circle">
			Shipping Charges : {{$row->shipping_charges}}  <!--<i class="fa fa-info-circle" onclick="shippinginfo();"></i> -->
			
		</div>
                                    @else
                                     Free Delivery
                                    @endif
										    
										    </span>
										    
										    
                    @if($current_out_stck==0)
                    <br><span class="in-stock"> In stock</span>
                    @else
                    <br><span class="out-stock clrred"> Out of stock</span>
                    @endif()
   
    </div>    
    </div>
        <div class="col-md-4 col-sm-5 col-xs-12">
        <div class="cartdelivery">
            <h4>₹ {{$subtotal}}</h4> 
          
          <!--<h6>Delivery in 3 days, Monday</h6> -->
          <!--  <p>₹ 375</p>-->
          <!--  <span>15 Days Replacement Policy</span>-->
        </div>
        </div> 
        
       
        </div>
        <?php  } 
        
        ?>