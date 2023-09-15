<div class="whitebox">
						<div class="slide-heading">
							<h6 class="fs20">Recent Views </h6>
						</div>
						<div class="pro-listing">
							<div>
								<ul id="owl-demo" class="owl-carousel owl-theme owl-carousel3">
										<!--repeat starts here-->
										@foreach($products as $product)
										<?php 
			
			if(@$product->color_id!='' && @$product->color_id!=0)
			{
				//$color_image=App\Products::getcolorNameAndCode('Colors',@$colors_img[0]['color_id']);
				$color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
				if(sizeof($color_image)>0){
				    	$prd_img=@$color_image[0]->image;
				    	$prd_img=@$color_image[0]['image'];
				    
				    	$prd_img=App\Products::getproductImageUrl(1,$prd_img);
				} else{
				   	$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
				}
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
									
								<img class="hoverprdimg" src="{{URL::to('/uploads/products/480-360')}}/{{$product->zoom_image}}">
									<?php } ?>
									
										<img <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?> src="{{$prd_img}}">
											
										</div></a>
										<!--<div class="product-image8">
											<img src="{{$prd_img}}">
										</div>-->
                                            @if ($product->spcl_price!='')
                                            <div class="sale-box">
                                            <span>{{App\Products::offerPercentage($product->price+$product->extra_price,$product->spcl_price+$product->extra_price)}}%<em>off</em></span>
                                            </div>
                                            @endif
                                            
										<ul class="social">
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
											
										</ul>
										<div class="product-content">
                                                <h3 class="title"><a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{$product->name}}</a></h3>
											
                                                                <span class="product-shipping">
                                                                {!!App\Products::productRatingCounter($product->id)!!}
                                                                
                                                                </span>
                                    @if ($product->spcl_price!='')
                                    <div class="price">
                                    <span><i class="fa fa-inr"></i> {{$product->price+$product->extra_price}}</span>
                                    <i class="fa fa-inr"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price+$product->extra_price}}</em></div>
                                    @else
                                    <div class="price"><i class="fa fa-inr"></i> <i id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->price+$product->extra_price}}</i></div>
                                    @endif
    
    
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
                                            ></i> Add to cart</button>
											
										</div>

									</div>
									</li>
									@endforeach
								<!--repeat ends here-->
								</ul>
								
							</div>
						</div>
					</div>