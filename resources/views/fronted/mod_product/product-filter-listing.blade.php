<ul class="list-inline productList row">
							@foreach($products as $product)
							<?php 
							/*	$simpleImage=App\Products::getproductImageUrl(1,$product->default_image);
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
			
				$prd_slider=$color_image;
				$flag=1;
			}else{
		$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
				$prd_slider=$product::prdImages($product->id);
				$flag=2;
				
			} */
			
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
                            <li class="col-6 col-sm-6 col-md-4 col-lg-3">
                                <div class="item-box">
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
									
										{{--
										<button type="submit" value="submit"
										class="add-to-cart-btn addTocart hideCartButtton"
										prd_page='0'
										url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
										prd_index='{{$product->id}}' 
										prd_id='{{$product->id}}'
										prd_type="{{$product->product_type}}"
										size_require="{!!App\Products::Issize_requires($product->id)!!}"
										color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
										size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
										color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
										><i class="fa fa-shopping-cart"></i> Add to cart</button>
                                       --}}


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
										
										<button type="submit"
                                        class="btn btn-outline-dark btn-block addTocart ml30 outOfStcok">
                                       Add To Cart
                                        </button>
										</div>
                                        @endif()
                                    </div>
                                    
                                    <div class="thumbnail tmb">
									<a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                                	
									
									
									<?php /*
									<?php  if(@$product->zoom_image){?>
									
				<img class="hoverprdimg" src="{{URL::to('/uploads/products')}}/{{$product->zoom_image}}">
									<?php } ?>
									
										<img <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?> src="{{$prd_img}}">
									
											*/ ?>


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
						@if(!empty($productimages))
                        <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->image}}">
						@endif 
                        <?php
                        }
                    ?>
							</a>		
									</div>
                                         

								{{--
                                    <ul class="social">
										<li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->id}}"><i class="fa fa-eye"></i></a></li>
										<li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
										<li><a href="javascript:void(0)" data-tip="Add to Cart" title="Add to Cart"
										class="addTocart"
											prd_page='0'
											url="{{$product::getProductDetailUrl($product->name,$product->id)}}"
											prd_index='{{$product->id}}' 
											prd_id='{{$product->id}}'
											prd_type="{{$product->product_type}}"
											size_require="{!!$product::Issize_requires($product->id)!!}"
											color_require="{!!$product::Iscolorrequires($product->id)!!}"
											size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
											color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
										><i class="fa fa-shopping-cart"></i></a></li>										
                                        <li><a href="#squarespaceModal" data-toggle="modal" data-tip="Quick View" title="Quick View"><i class="fa fa-eye"></i></a></li>
                                        <li><a href="" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o"></i></a></li>
                                        <li><a href="" data-tip="Add to Cart" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul> 
								--}}

                                    <div class="item-box-dec">
                                        <!-- <h3 class="title"><a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{$product->name}} </a></h3> -->
									
										<?php 
                  						   $vendorbradename=DB::table('vendor_company_info')->where('vendor_id',$product->vendor_id)->first();
										?>
									<h5>{{ $vendorbradename->name}}</h5>
										<h6><?php echo substr(strip_tags($product->short_description),0,150); ?> {{(!empty($product->short_description))?'...':''}}	</h6>
								
										<!--{!!App\Products::productRatingCounter($product->id)!!}	-->								
										
									<p class="price">
                                               @if ($product->price!='' && $product->price!=0)
                                        <del><i class="fa fa-inr"></i> {{$product->price}}</del>
                                        <span class="offprice">{{$product::offerPercentage($product->price,$product->spcl_price)}}% off</span><br>
											<i class="fa fa-inr"></i>{{$product->spcl_price}}
                                                @else
                                                	<i class="fa fa-inr"></i>{{$product->spcl_price}}
                                                
                                            @endif
                                        
										</p>
                                        
                                        
                                        
										
										
										
								
								
									</div>
									</div>
							</li>
							@endforeach
							@if (count($products) ==0)
							<li><div class="text-center">No Product Found</div></li>
							@endif														
                        </ul>
							<?php 
							
							echo $products->links();?>
                        	<script>
                			/*$(document).on('click', '.pagination a',function(event)
								{
								event.preventDefault();
								$('li').removeClass('active');
								$(this).parent('li').addClass('active');
								var myurl = $(this).attr('href');
								var page=$(this).attr('href').split('page=')[1];
								SearchData(page);
								});*/
                        	</script>
                        