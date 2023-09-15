<div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 product_img">
                            <img src="{{URL::to('/uploads/products')}}/{{$prd_detail->default_image}}" class="img-responsive" id="img_zoom">
                        </div>

                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <div class="quick-view-content">
                         <h6> {!!$prd_detail->name!!} </h6>
                        <h4>Product Id: <span>#{{$prd_detail->id}}</span></h4>
                        
                       <?php 
                       $decodeInput[1]=$prd_detail->size_id;
                         $decodeInput[2]=$prd_detail->color_id;;
                       ?>
                        
                         {!!App\Products::productRatingCounter($prd_detail->id)!!}
                        
                        <h4 class="mt10 mb5 fw600">Description</h4>
                        <p>{!!$prd_detail->short_description!!}
                        </p>
                        
							
							
								@if ($prd_detail->spcl_price!='')
									<h3 class="cost"><span class="fa fa-inr"></span><span id="prd_price_0">{{$prd_detail->spcl_price}}</span>
									<del class="pre-cost"><span class="fa fa-inr"></span>{{$prd_detail->price}}</del></h3>
									
								@else
								<h3 class="cost"><span class="fa fa-inr"></span><span id="prd_price_0">{{$prd_detail->price}}</span></h3>
								@endif
								
                             @include('fronted.mod_product.sub_views.products_attributes')
                        <div class="space-ten"></div>
                        <div class="btn-ground">
						
							<input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_Q">
							<?php  $size_color_require=0; ?>
                            <span id="back_response_msg_Q"></span>
                            
                            @if($prd_detail->product_type==2)
                                    <button type="submit"
									class="btn btn-black waddTocart noProblem"
									prd_page='1'
									prd_index='Q' 
									prd_id='{{$prd_detail->id}}'
                                    size_require="{!!$prd_detail::Issize_requires($prd_detail->id)!!}"
									color_require="{!!$prd_detail::Iscolorrequires($prd_detail->id)!!}"
									size_id="0"
									w_size_id="0"
									color_id="{{$decodeInput[2]}}"
                                    prd_type="{{$prd_detail->product_type}}"
									><i class="fa fa-shopping-cart"></i> Add to cart </button> 
                                    @else
                                    
                                    <button type="submit"
									class="btn btn-black addTocart noProblem"
                                    prd_page='1'
                                    prd_index='Q' 
                                    prd_id='{{$prd_detail->id}}'
                                    size_require="{!!$prd_detail::Issize_requires($prd_detail->id)!!}"
                                    color_require="{!!$prd_detail::Iscolorrequires($prd_detail->id)!!}"
                                    size_id="0"
                                    	color_id="{{$decodeInput[2]}}"
                                    prd_type="{{$prd_detail->product_type}}"
									><i class="fa fa-shopping-cart"></i> Add to cart </button> 
                                    @endif()
                                    
                           
                            
                            <button type="button" class="btn btn-danger wishList" prd_id="{{$prd_detail->id}}"><span class="fa fa-heart " ></span> Add To Wishlist</button>
                        </div>
                    </div>    
                       
                    </div>
                </div>

                @include('fronted.includes.addtocartscript')