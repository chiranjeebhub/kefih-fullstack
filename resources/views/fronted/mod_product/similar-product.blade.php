

	<div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="slide-heading">
                <div class="title text-center">
                    <h3>Related Products</h3>	
                </div>
            </div>
        </div>
    </div>  
    <ul class="list-inline owl-carousel owl-carouselSimilar">
										<!--repeat starts here-->
										@foreach($products as $product)
										<?php 
			
			if(@$product->color_id!='' && @$product->color_id!=0)
			{
				//$color_image=App\Products::getcolorNameAndCode('Colors',@$colors_img[0]['color_id']);
				// $color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
				// if(sizeof($color_image)>0){
				//     	$prd_img=@$color_image[0]->image;
				//     	$prd_img=@$color_image[0]['image'];
				//     	$prd_img=App\Products::getproductImageUrl(1,$prd_img);
				// } else{
				//    	$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
				// }
                $prd_img=App\Products::getproductImageUrl(1,$product->default_image);

				$flag=1;
			}else{
		$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
			
				$flag=2;
			}
		?>
									<li class="item-box">
<div class="addtocart-btm-btn">
    <div class="d-flex justify-content-between mb-3">
    <div class="pro-icon-hover">
			<div class="share-icon"><a href="javascript:void(0);" class="shareproduct" data-url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></div>
			</div>
    {{--
    <div class="size">
        <a href="#">XS</a>
        <a href="#">M</a>
        <a href="#">L</a>
        <a href="#">XL</a>
        <a href="#">XXL</a>
    </div>
    --}}

    </div>
    
    <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
        <span id="back_response_msg_{{$product->id}}"></span>
        @if ($product->qty!=0)

            <div class="twobtns">
                <!--<a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist" class="btn btn-light btn-block">
                     <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i> 
                    @php 
                        $wishlisticon = App\Helpers\CommonHelper::productHeartCheck($product->id);
                    @endphp 
                    {!! $wishlisticon !!}
                </a>-->
                <button type="submit" value="submit"
                class="btn btn-outline-dark btn-block addTocart hideCartButtton"
                prd_page='0'
                url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                prd_index='{{$product->id}}' 
                prd_id='{{$product->id}}'
                prd_type="{{$product->product_type}}"
                size_require="{!!App\Products::Issize_requires($product->id)!!}"
                color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
                color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
                >Add to cart</button>
            </div>

        @else
                                                                                
        <style>
            .outOfStcok {
                display: inline-block!important;
            }
        </style>
        <div class="twobtns">
        <!--<a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-heart-o"></i></a>-->
        <button type="submit"
        class="btn btn-outline-dark btn-block addTocart outOfStcok">Add To Cart
        </button>
        </div>
        @endif()
    
                    <!--<div class="twobtns">
                         <a href="#" class="btn btn-outline-dark btn-block"> Add To Cart</a>	 
                </div>-->
                </div>
            <div class="thumbnail tmb">
                <a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                    <div class="thumbnail tmb">


                    <?php  if(@$product->zoom_image){?>

<!-- <img class="hoverprdimg" src="{{URL::to('/uploads/products')}}/{{$product->zoom_image}}"> -->
                    <?php } ?>



                    <?php 
                    if($product['product_type']=='1')
                    {
                        $imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$product['sku'];
                    }else{
                    $productskuid=DB::table('product_attributes')->where('product_id',$product->id)->first();

                    $imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$productskuid->sku;
                    }
                    if($product->product_type=='3')
                    { 
                        $productimages=DB::table('product_configuration_images')->where('product_id',$product->id)->first();
                        if(!empty($productimages->product_config_image))
                        {
                        ?>
                        <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->product_config_image}}">
                         <?PHP }
                    }else{
                        $productimages=DB::table('product_images')->where('product_id',$product->id)->first();
                        ?>
                        <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->image}}">
                        <?php
                        }
                    ?>

                        
                        <!-- <img <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?> src="{{$prd_img}}"> -->

                        <!--<div class="product-image8">
                            <img src="{{$prd_img}}">
                        </div>-->
                      

                    </div></a>
				
                </div>
                <div class="item-box-dec">
                     <?php 
                     $vendorbradename=DB::table('vendor_company_info')->where('vendor_id',$product->vendor_id)->first();
                     ?>
                <h5>{{ $vendorbradename->name}}</h5>
                <h6><?php echo substr(strip_tags($product->short_description),0,150); ?> {{(!empty($product->short_description))?'...':''}}	</h6>
			
                <div class="row align-items-center">
                    <div class="col-8 col-sm-9 col-md-10 col-lg-10">
                       
                        @if ($product->price!='' && $product->price!=0)
                       
                            <p class="price">
                                <del><i class="fa fa-inr"></i>{{$product->price}}</del> 
                                <span class="offprice">{{$product::offerPercentage($product->price,$product->spcl_price)}}% Off</span> <br/> 
                                <i class="fa fa-inr"></i>{{$product->spcl_price}} 
                        
                                 @else
                                <i class="fa fa-rupee"></i> <del id="prd_price_{{$product->id}}">{{$product->spcl_price}}</del>
                        </p>
                            
                        @endif
                        </div>
                         <div class="col-4 col-sm-3 col-md-2 col-lg-2 pding-lft0 justify-content-end">
                        <p class="share-icon"><a href="#"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></p>
                        </div>
                    </div>
				
                </div>
            </li>
									@endforeach
								<!--repeat ends here-->
								</ul>

				
<!--<div class="slide-heading">
							<h6 class="fs24">Related products  </h6>

                           
                                <div class="txt-rgt view">
                                   <a href="{{route('all_products',['type' => 'Related', 'id' => $slider_type])}}">View All </a>
                                </div>                                
                      


						</div>-->
                     

                            
						<div class="pro-listing">
							<div>                           

                            
								
								
							</div>
						</div>
					