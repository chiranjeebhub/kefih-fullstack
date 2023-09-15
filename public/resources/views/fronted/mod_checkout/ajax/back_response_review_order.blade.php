 
<div class="db-2-main-com db-2-main-com-table carttbl checkoutpagemain">
        <div class="table " id="results">
            
            <div class="theader">
                <div class="table_header" style="width:18%;">Image</div>
                <div class="table_header" style="width:35%;">Product Name</div>
                <div class="table_header">Price</div>
                <div class="table_header">Qty</div>
                <div class="table_header">Amount</div>
            </div>

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
    
            
            <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Image</div>

                  <div class="table_cell">
                      <a href="#" class="item-photo" target="_blank"><img src="{{$url}}" alt="cart" class="img-thumbnail" width="50" height="50"></a>
                  </div>

                </div>
                <div class="table_small">
                  <div class="table_cell">Name</div>
                      <div class="table_cell">
                          <div class="product-name">
                              <div class="prdnamecartpage">
                                    <a href="#" target="_blank">{{$row->name}}</a>
                              </div>
                              <br>
                              @if($row->color_id!=0)
                               <span class="colormainbox">Color : <div class="colrboxcart"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$row->color_id);?> !important">
                             </div></span>
                              @endif()
                           
                                 @if ($row->size_id!=0)                                                                                   <br>
                          <span class="sizemainbox">Size : <div class="sizeboxcart">{!!App\Products::getAttrName('Sizes',$row->size_id)!!}</div></span>
                           @endif()
                          </div>
                      </div>
                </div>
                <div class="table_small">
				    <div class="table_cell">Price</div>
					<div class="table_cell pricecolr"> 
			 		    @if ($row->master_spcl_price!='')
                        <span id="prd_price_0"><i class="fa fa-rupee"></i> {{$prc}}</span>
                        <!--<del><i class="fa fa-rupee"></i>{{$old_prc}}</del>
                        <span class="offer_txt">{{App\Products::offerPercentage($old_prc,$prc)}}% <span>off</span></span>-->
                        @else
                        <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$old_prc}}</span>
                        @endif
					</div>
                </div>
                <div class="table_small">
				    <div class="table_cell">Qty</div>
					<div class="table_cell"><div class="tb-qty">{{$row->qty}}</div></div>
				</div>
                <div class="table_small">
                    <div class="table_cell">Amount</div>
                    <div class="table_cell pricecolr"><i class="fa fa-rupee"></i> {{$subtotal}}</div>
                </div>
            </div>
        
        
        <?php  } ?>
            </div>
    </div>