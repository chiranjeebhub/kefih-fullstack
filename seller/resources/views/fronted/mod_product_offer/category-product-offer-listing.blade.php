
<section class="product-slider-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="whitebox">
					<div class="slide-heading">
							<h6 class="fs20 fw600">{{$cat_name}}
							<span class="pull-right"><a href="{{$url}}">View All<i class="fa fa-caret-right"></i></a> </span>
							</h6>
						</div>
						<div class="pro-listing">
							<div>
								<ul id="owl-demo" class="owl-carousel productSlider owl-theme">
									@foreach($products as $product)
									<li class="item wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
										<div class="product-grid8">
										<div class="product-image8">
										    <a href="{{App\Products::getProductDetailUrl($product->name,$product->id)}}">
										    <div class="simpleimg"><img class=" " src="{{URL::to('/uploads/products')}}/{{$product->default_image}}"></div>
											<div id="myCarousel_{{$slider_type}}" class="carousel slide" data-ride="carousel">
												<!-- Indicators -->
												<ol class="carousel-indicators">
													<li data-target="#myCarousel_{{$slider_type}}" data-slide-to="0" class="active"></li>
												        	 @php $i=1; @endphp
                                                            @foreach(App\Products::prdImages($product->product_id) as $row)
                                                           	<li data-target="#myCarousel_{{$slider_type}}" data-slide-to="0" class="active"></li>
                                                           	 @php $i++; @endphp
                                                            @endforeach  
												
												</ol>

												<!-- Wrapper for slides -->
												<div class="carousel-inner">
													<div class="item active">
														<img class=" " src="{{URL::to('/uploads/products')}}/{{$product->default_image}}">
													</div>
                                                       
                                                        @foreach(App\Products::prdImages($product->product_id) as $row)
                                                       	<div class="item">
														<img class=" " src="{{URL::to('/uploads/products')}}/{{$row['image']}}">
													</div>
                                                        @endforeach  
													
												</div>
											</div>

</a>
										</div>
										<ul class="social">
                                                <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}_s_{{$slider_type}}">
                                                <span id="back_response_msg_{{$product->id}}_s_{{$slider_type}}"></span>
											<li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->product_id}}"><i class="fa fa-eye"></i></a></li>
											<li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
											<li><a href="javascript:void(0)"
                                                prd_page='0'
                                                 class="addTocart"
                                                url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}"
                                                prd_index='{{$product->id}}_s_{{$slider_type}}' 
                                                prd_id='{{$product->id}}'
                                                 size_require="{!!App\Products::Issize_requires($product->id)!!}"
                                                color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                                                size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
                                                color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
											data-tip="Add to Cart" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a></li>
											<li><a href="javascript:void(0)" data-tip="Compare" title="Compare"><i class="material-icons">&#xe915;</i></a></li>
										</ul>

										<div class="product-content text-left">
											<h3 class="title"><a href="{{App\Products::getProductDetailUrl($product->name,$product->id)}}">{{$product->name}} </a></h3>
											@include('fronted.mod_product.sub_views.products_attributes_listing_page')
											
					{!!App\Products::productRatingCounter($product->id)!!}
											
											    
                                            @if ($product->spcl_price!='')
                                            	<div class="price">Rs. <i id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price}}</i><span>Rs. {{$product->price}}</span><aside>{{App\Products::offerPercentage($product->price,$product->spcl_price)}}% off</aside>
											</div>
                                            @else
                                           	<div class="price">Rs. <i id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->price}}</i>
											</div>
                                            @endif
											    
										
											<button type="submit" value="submit" class="add-to-cart-btn hideCartButtton"><i class="fa fa-shopping-cart "></i> Add to cart</button>
										</div>

									</div>
									</li>
									@endforeach
									
								</ul>
								
							</div>
						</div>

					</div>

				</div>

			</div>
		</div>

	</section>
	
