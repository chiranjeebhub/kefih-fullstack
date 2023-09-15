<div class="whitebox">
						<div class="slide-heading">
							<h6 class="fs24">Related products  </h6>

                           
                                <div class="txt-rgt view">
                                   <a href="{{route('all_products',['type' => 'Related', 'id' => $slider_type])}}">View All </a>
                                </div>                                
                      


						</div>
                     

                            
						<div class="pro-listing">
							<div>                           

                            
								<ul class="owl-carousel owl-theme owl-carouselSimilar">
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
									<li class="item">
										<div class="product-grid8">
										<a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
										<div class="product-image8">
										<?php  if(@$product->zoom_image){?>									
											<!--<img class="hoverprdimg" src="{{URL::to('/uploads/products')}}/{{$product->zoom_image}}">
										<?php } ?>-->
									
										<img <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?> src="{{$prd_img}}">
											
										</div></a>
                                            @if ($product->price!='' && $product->price!=0)
                                            <div class="sale-box">
                                            <span>{{App\Products::offerPercentage($product->price+$product->extra_price,$product->spcl_price+$product->extra_price)}}%<em>off</em></span>
                                            </div>
                                            @endif
                                            
										<!--<ul class="social">
											<li><a href="#squarespaceModal" data-toggle="modal" data-tip="Quick View" class="quickView" title="Quick View" prd_id="{{$product->product_id}}"><i class="fa fa-eye quickView" prd_id="{{$product->id}}"></i></a></li>
											<li><a href="" data-tip="Wishlist" title="Wishlist" class="wishList" prd_id="{{$product->id}}"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
											<li><a href="" data-tip="Add to Cart" title="Add to Cart" prd_page='0'
                                            class="addTocart"
                                            url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                                            prd_index='{{$product->id}}_s_{{$slider_type}}' 
                                            prd_id='{{$product->id}}'
                                            size_require="{!!App\Products::Issize_requires($product->id)!!}"
                                            color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                                            size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
                                            color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"><i class="fa fa-shopping-cart"
											></i></a></li>
											
										</ul>-->
										<div class="product-content">
                                                <h3 class="title"><a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{$product->name}}</a></h3>
											
                                                                <span class="product-shipping">
                                                                {!!App\Products::productRatingCounter($product->id)!!}
                                                                
                                                                </span>
                                  @if ($product->price!='' && $product->price!=0)
                                    <div class="price">
                                    <span><i class="fa fa-rupee"></i> {{$product->price+$product->extra_price}}</span>
                                    <i class="fa fa-rupee"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price+$product->extra_price}}</em></div>
                                    @else
                                    <div class="price"><i class="fa fa-rupee"></i> <i id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price+$product->extra_price}}</i></div>
                                    @endif
    
                                         <!--<div class="sizechart">
                                            MOQ : <span class="sws_moq" style="font-weight:600;">{{$product->moq}}</span>
                                        </div>
        
                                           <button type="button" value="submit" class="add-to-cart-btn hideCartButtton addTocart"
                                            prd_page='0'
                                            class="addTocart"
                                            url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                                            prd_index='{{$product->id}}_s_{{$slider_type}}' 
                                            prd_id='{{$product->id}}'
                                            size_require="{!!App\Products::Issize_requires($product->id)!!}"
                                            color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                                            size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
                                            color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
                                            data-tip="Add to Cart" title="Add to Cart"
                                            ><i class="fa fa-shopping-cart"
                                            ></i> Add to cart</button>-->
										<input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
										<span id="back_response_msg_{{$product->id}}"></span>
										@if ($product->qty!=0)
										
                                        <div class="addtocart-btm-btn">
											<div class="twobtns">
											<a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist" class="btn btn-light btn-block">
                                                    <!-- <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i> -->
                                                    @php 
                                                        $wishlisticon = App\Helpers\CommonHelper::productHeartCheck($product->id);
                                                    @endphp 
                                                    {!! $wishlisticon !!}
                                                </a>
                                                <button type="submit" value="submit"
                                                class="btn btn-danger btn-block addTocart hideCartButtton"
                                                prd_page='0'
                                                url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                                                prd_index='{{$product->id}}' 
                                                prd_id='{{$product->id}}'
                                                prd_type="{{$product->product_type}}"
                                                size_require="{!!App\Products::Issize_requires($product->id)!!}"
                                                color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                                                size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
                                                color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
                                                ><span>+</span> Add to cart</button>
										</div>
										</div>
										@else
                                        
                                        
                                        
										<style>
    									    .outOfStcok {
                                                display: inline-block!important;
                                            }
    									</style>
										<div class="twobtns">
										<a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-heart-o"></i></a>
										<button type="submit"
                                        class="add-to-cart-btn addTocart ml30 outOfStcok">
                                       <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </button>
										</div>
                                        @endif()

										</div>
                                </div>
									</li>
									@endforeach
								<!--repeat ends here-->
								</ul>
								
							</div>
						</div>
					</div>