 
<div class="checkoutpagemain">
        <div class="table " id="results">
            
            <!--<div class="theader">
                <div class="table_header" style="width:18%;">Image</div>
                <div class="table_header" style="width:35%;">Product Name</div>
                <div class="table_header">Price</div>
                <div class="table_header">Qty</div>
                <div class="table_header">Amount</div>
            </div>-->

<?php
  $out_of_stock=0;
    foreach($cart_data  as $row) {

        $producdetails=DB::table('products')->where('id',$row->prd_id)->first();
        $productskuid=DB::table('product_attributes')->where('product_id',$row->prd_id)->first();
$imagespathfolder='uploads/products/'.$producdetails->vendor_id.'/'.$productskuid->sku;
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
                                    $url=$imagespathfolder.'/'.$colorImage->product_config_image;
                                    } else{
                                    $url=$imagespathfolder.'/'.$row->default_image;  
                                    }
                                    
                                    }
                                    else{
                                        $productid=DB::table('products')->where('id',$row->prd_id)->first();
                                     
                                        $productimages=DB::table('product_images')->where('product_id',$row->prd_id)->first();
                                        $imagespathfolder='uploads/products/'.$productid->vendor_id.'/'.$productid->sku;
                                        
                                        $url=$imagespathfolder.'/'.$productimages->image;  
                                    //$url=$imagespathfolder.'/'.$row->default_image;  
                                    }
    ?>
    
            
            <div class="table_row">
                <div class="table_small imagCell">
                  <div class="table_cell d-block d-sm-none">Image</div>

                  <div class="table_cell pro-img-table">
                      <a href="#" class="item-photo" target="_blank"><img src="{{$url}}" alt="cart" class="img-thumbnail" width="50" height="50"></a>
                  </div>

                </div>
                <div class="table_small">
                  <div class="table_cell">Name</div>
                      <div class="table_cell">
                          <div class="product-name">
                              <div class="prdnamecartpage">
                                    <h6><a href="#" target="_blank">{{$row->name}}</a></h6>
                              </div>
                              <div class="row">
                                  @if($row->color_id!=0)
                                    <div class="col-md-4 col-4">
                                        <p class="sizes">Color</p>
                                        <div class="coloroptionbox">
                                            <ul>
                                                <li>
                                                <label class="colrboxcart color1"  style="background-color:
                                       <?php echo App\Products::getcolorCode('Colors',$row->color_id);?> !important">
                                                  </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                  @endif()
                                  
                                  @if ($row->size_id!=0)  
                                  <div class="col-md-6 col-8">
                                    <div class="coloroptionbox">
                                        <ul>
                                            <li>
                                                <p class="sizes">Size</p> 
                                                <label class="size1 sizeboxcart">{!!App\Products::getAttrName('Sizes',$row->size_id)!!}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @endif()
                              </div>
                              
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