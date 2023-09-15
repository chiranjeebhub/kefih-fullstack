@extends('fronted.layouts.app_new')
@section('pageTitle',$prd_detail->name)
@section('metaTitle',$prd_detail->meta_title)
@section('metaDescription',$prd_detail->meta_description)
@section('metaKeywords',$prd_detail->meta_keyword)
@section('content')   
<?php  $cuurent_url = Request::segment(1);?>
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
  <?php 
  //$subject = url()->current();
  $subject=Request::path();
  $trimmed = str_replace('p/', '', $subject) ;
  $arr=explode("/",$trimmed);
    array_pop($arr); 
    $pr_name=end($arr);
    array_pop($arr); 
    $last=sizeof($arr)-1;
$br_i=0;

$pr_name=str_replace('-',' ',$pr_name);

foreach($arr as $ar){
    $cat_trimmed = str_replace('-', ' ', $ar) ;
    
$cat_data=DB::table('categories')->where('name',$ar)->first();
$url=route('cat_wise', [$ar,base64_encode(@$cat_data->id)]);
?>
<a href="{{$url}}">{{strtoupper($cat_trimmed)}}</a>
<i class="fa fa-long-arrow-right"></i>



</a>
<?php $br_i++;}?>
<a href="javascript:void(0)">{{strtoupper(@$pr_name)}}</a>
@endsection  

<link rel="stylesheet" type="text/css" href="{{ asset('public/fronted/css/zoomslider.css') }}">

<?php 



$extrs_price=0;
 $decodeInput=explode("~~~",base64_decode(Request()->id));
        $product_id=$decodeInput[0];
        
        $rewards_point=DB::table('product_reward_points')
->where('product_id',$product_id)->first();

        $color_id=$decodeInput[2];
        if($color_id!='' && $color_id!=0)
			{
$color_price=DB::table('product_attributes')
->where('product_id',$product_id)
->where('color_id',$color_id)->first();


$extrs_price=$color_price->price;
$color_image=App\ProductImages::getConfiguredImages($product_id,$color_id,0);
        $prd_img=$color_image[0]->image;
        $prd_slider=$color_image;
        $flag=1;
			}else{
$prd_img=URL::to('/uploads/products').'/'.$prd_detail->default_image;
$prd_slider=App\Products::prdImages($product_id);
$flag=2;
			}

?>
    <section class="productdetails-section">
    <div class="container">
    <div class="card">
    <div class="container-fliud">
    <div class="wrapper row">
    
		<div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
			
		<div class="preview">
								<div class="wishlisticon">
									 @if (Auth::guard('customer')->check())
								     <a title="wishlist" href="#"><i class="fa fa-heart wishList 
								     {{
								     (App\Customer::productInWishlist(auth()->guard('customer')->user()->id,$prd_detail->id)==1)?"isInwishlist":""
								     }}" prd_id='{{$prd_detail->id}}'></i> </a>
								     @else
								     <a title="wishlist" href="#"><i class="fa fa-heart wishList" prd_id='{{$prd_detail->id}}'></i> </a>
								      @endif
								</div>
								
								<div class="catalog-view_op1">
									<div class="product-media media-horizontal">

										<div class="image_preview_container images-large">

											<img id="img_zoom" data-zoom-image="{{$prd_img}}" src="{{$prd_img}}" alt="">

											<button class="btn-zoom open_qv"><span>zoom</span></button>

										</div>

										<div class="product_preview  images-small_xx">

											<div class="owl-carousel thumbnails_carousel images-small" id="thumbnails"  data-nav="true" data-dots="false" data-margin="10" data-responsive='{"0":{"items":3},"480":{"items":6},"600":{"items":5},"768":{"items":6}}'>
	
                        <a href="javascript:void(0)" data-image="{{$prd_img}}" data-zoom-image="{{$prd_img}}">
                        
                        <img src="{{$prd_img}}" data-large-image="{{$prd_img}}" alt="">
                        
                        </a>
	
					
				                            	<?php  $pr=3;?>
												@foreach($prd_slider as $row)
											
												<?php if($flag==2){?>
            <a href="javascript:void(0)"  data-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" data-zoom-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" class="thumbnailSmall">
            <img src="{{URL::to('/uploads/products')}}/{{$row['image']}}" class="customThumnnail" data-large-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" alt="">
            </a>
								
												<?php } if($flag==1){?>
                    <a href="javascript:void(0)" data-image="{{$row->image}}" data-zoom-image="{{$row->image}}" class="thumbnailSmall">
                    <img src="{{$row->image}}" data-large-image="{{$row->image}}" alt="">
                    </a>
											
												<?php } ?>
											
												<?php  $pr++;?>
												@endforeach
												
												
			

											</div><!--/ .owl-carousel-->

										</div><!--/ .product_preview-->
										</div>
										</div>
							
							</div>
			
			
	
		
		</div>
		
        <div class="details col-xs-12 col-sm-7 col-md-6 col-lg-6">
            
        <h3 class="product-title">{{ ucwords($prd_detail->name) }} </h3>
        
		<h4 class="price fs20">
		
		
		@if ($prd_detail->spcl_price!='' && $prd_detail->spcl_price!=0)
		     <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$prd_detail->spcl_price+$extrs_price}}</span>
		<del><i class="fa fa-rupee"></i><span id="prd_old_price_0">{{$prd_detail->price+$extrs_price}}</span></del>
		<span class="offer_txt"><span class="percent">{{$prd_detail::offerPercentage($prd_detail->price+$extrs_price,$prd_detail->spcl_price+$extrs_price)}}</span> <span> % off</span></span>
			@else
			<i class="fa fa-rupee"></i> <span id="prd_price_0">{{$prd_detail->price+$extrs_price}}</span>
			@endif
		
		
		</h4>
		
        <ul class="availtext">
      
		@if ($prd_detail->qty!='0')
			<li><p> <strong>Availability</strong> : <span class="in-stock"> In stock</span></p></li>
		@else
			<li><p> <strong>Availability</strong> : <span class="out-stock"> Out stock</span></p></li>
		@endif
                    @if($rewards_point->reward_points!='0' && $rewards_point->reward_points!='')
                    <li><p> <strong>Rewards Points</strong> : <i class="fa fa-rupee"></i><span class="out-stock">{{$rewards_point->reward_points}}</span></p></li>
                    @endif
        </ul>
            
        <div class="rating">
        {!!App\Products::productRatingCounter($prd_detail->id)!!}

        </div>

		
			
       <section class="about-spfc-section short-spfc-section">
        <div class=" ">
        <div  class=" ">
        <div class="tabs-details">
        <?php //echo substr(strip_tags($prd_detail->short_description,0,100)); ?>
        <div id="section">
		  <div class="article">
			<div class="smallText">
			  {!!$prd_detail->short_description!!} 
			</div>
			<div class="moreText">
			 {!!$prd_detail->short_description!!}
			</div> 
		  </div>
		  <a class="moreless-button" href="javascript:void(0)" tab="1">Read more</a>
		</div>
        
        </div>	</div>	</div>	
		</section>	
			@include('fronted.mod_product.sub_views.products_attributes')
			
			<div class="row availtext pt10 pb10" style="display:none;">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 availtext">Quantity :</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div class="quantity buttons_added">
					<a href="javascript:void(0)" class="sign plus changeQty" method="1" row="0"><i class="fa fa-plus"></i></a><input type="number" id="prd_qty_0" name="qty" min="1" max="5" value="1" title="Qty" class="input-text qty text" size="1" disabled ><a href="javascript:void(0)" class="sign minus changeQty" method="2" row="0"><i class="fa fa-minus"></i></a>
				</div>
			</div>
			</div>
			
				
			<div class="row pt10 pb10">

				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<span class="delivery"><strong>Delivery</strong> :</span>     
				</div>  

				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="pincodebtn">
					<input type="text" id="pincode" class="form-control" placeholder="Enter pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
   
					<div class="searchpin">
						<a href="javascript:void(0)" class="checkPinCode onlyDigit" element="pincode">Check</a>
					  </div>
                    <div id="Pincodedisplay">
                    
                    </div>
					</div>
					 <span id="pincode_msg"></span>
					</div>
					</div>
					<div class="row">
					<div class="col-md-12">
						<div class="seller-msg"><p> 
						@if($prd_detail->delivery_days!=0)
							Delivered by
						<?php 
                            $date = strtotime("+".$prd_detail->delivery_days." days");
                            echo date('M d, Y', $date);
						?>
						@endif

						@if($prd_detail->shipping_charges!=0)
						<br>Shipping Charges : <i class="fa fa-rupee"></i> {{$prd_detail->shipping_charges}}
						@endif
						</p></div>
					</div>
					</div>
				</div>

				<!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				</div>-->

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="seller-info row">	
						@if ($prd_detail->vendor_id!=0)
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"><strong>Seller</strong> :</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<span class="sellerCaps">{!!$prd_detail->getProductsVendor()!!}</span>
							 {!!App\Vendor::getVendorRating($prd_detail->vendor_id)!!}
						</div>
						@endif	
						@include('fronted.mod_product.sub_views.more_seller')
					</div>
				</div>
				
				<?php  $size_color_require=0; ?>
				<div class=" ">
					<div class="addcartbox">
					<div class="col-md-12 col-xs-12">
						<span id="back_response_msg_0"></span>
						<button type="submit"
						class="add-to-cart ml30 addTocart"
						prd_page='1'
						prd_index='0' 
						prd_id='{{$prd_detail->id}}'

						size_require="{!!$prd_detail::Issize_requires($prd_detail->id)!!}"
						color_require="{!!$prd_detail::Iscolorrequires($prd_detail->id)!!}"
						size_id="{{$decodeInput[1]}}"
						color_id="{{$decodeInput[2]}}"

						><i class="fa fa-shopping-cart"></i> Add to cart </button>  
						<button type="submit" class="bynow buyNow"
						prd_page='1'
						url="{{App\Products::getProductDetailUrl($prd_detail->name,$prd_detail->id)}}"
						prd_index='0' 
						prd_id='{{$prd_detail->id}}'
						size_require="{!!App\Products::Issize_requires($prd_detail->id)!!}"
						color_require="{!!App\Products::Iscolorrequires($prd_detail->id)!!}"
						size_id="{{$decodeInput[1]}}"
						color_id="{{$decodeInput[2]}}">
							<i class="fa fa-cart-plus" aria-hidden="true"></i>
						Buy now</button>
</div>

</div>
				</div> 

			</div>    
        </div>	
        </div>
        </div>
        </div>
        </div>  
        </section>    
    <section class="about-spfc-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


<!--tabs start--->
<div class="tabs-details">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item ">
								<a class="nav-link" data-toggle="tab" href="#description" aria-expanded="true">Description</a>
							</li>
							<!--<li class="nav-item">-->
							<!--	<a class="nav-link" data-toggle="tab" href="#video" aria-expanded="false">Videos</a>-->
							<!--</li>-->
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#home" aria-expanded="false">Specifications</a>
							</li>
							<li class="nav-item active">
								<a class="nav-link" data-toggle="tab" href="#menu1" aria-expanded="false"> Ratings &amp; Reviews </a>
							</li>
							<?php 
								$prd_questions=App\ProductQuestions::productQuestions($prd_detail->id);
								if(@$prd_questions[0]->product_question!=''){
							?>
							<li class="nav-item ">
								<a class="nav-link" data-toggle="tab" href="#menu2" aria-expanded="false">  QA's </a>
							</li>
							<?php } ?>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div id="description" class="tab-pane fade in">
								<!--<h6 class="fs18 fw600 mb10">Product Description</h6>-->
								<?php 
									$prd_extra_description=App\ProductExtraDescription::getProductExtraDescription($prd_detail->id);
									foreach($prd_extra_description as $row_extra_descrip){
								?>
								<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
                                                @if($row_extra_descrip->product_descrip_image!='')
                                                <img src="{{URL::to('/uploads/products')}}/{{$row_extra_descrip->product_descrip_image}}" style="vertical-align:middle" widht="100" height="100">
                                                @endif
											<h2><?php echo ucwords($row_extra_descrip->product_descrip_title);?></h2>
											<p><?php echo $row_extra_descrip->product_descrip_content;?></p>
										</div>
									</div>
								</div>
								<?php } ?>
								
								
								<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<table>
												<?php 
													$prd_extra_general_description=App\ProductExtraGeneralDescription::getProductExtraGeneralDescription($prd_detail->id);
													foreach($prd_extra_general_description as $row_extra_general_descrip){
												?>
												<tr>
													<td><?php echo ucwords($row_extra_general_descrip->product_general_descrip_title);?></td>
													<td><?php echo $row_extra_general_descrip->product_general_descrip_content;?></td>
												</tr>
												<?php } ?>
											</table>
                                            
										</div>
									</div>
								</div>
								
								<!--<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<img src="https://rukminim1.flixcart.com/image/200/200/cms-rpd-images/8dbfd0ff86bf49b6ae3d6a4d1f87e91b_16c9a2bb612_image.png?q=90" style="max-width:100%; vertical-align:middle">
											<h2>AI Quad Camera</h2>
											<p>The power of four cameras in one lets you click incredible pictures. The 12 MP Primary Lens with its large aperture and pixel size lets you capture amazing pictures, even in low-light conditions. The 8 MP Ultra Wide-angle Lens, the 2 MP Super Macro Lens and the 2 MP Portrait Lens let you capture your subject with different perspectives.</p>
										</div>
									</div>
								</div>
								<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<img src="https://rukminim1.flixcart.com/image/200/200/cms-rpd-images/8dbfd0ff86bf49b6ae3d6a4d1f87e91b_16c9a2bb612_image.png?q=90" style="max-width:100%; vertical-align:middle">
											<h2>AI Quad Camera</h2>
											<p>The power of four cameras in one lets you click incredible pictures. The 12 MP Primary Lens with its large aperture and pixel size lets you capture amazing pictures, even in low-light conditions. The 8 MP Ultra Wide-angle Lens, the 2 MP Super Macro Lens and the 2 MP Portrait Lens let you capture your subject with different perspectives. The power of four cameras in one lets you click incredible pictures. The 12 MP Primary Lens with its large aperture and pixel size lets you capture amazing pictures, even in low-light conditions. The 8 MP Ultra Wide-angle Lens, the 2 MP Super Macro Lens and the 2 MP Portrait Lens let you capture your subject with different perspectives.</p>
										</div>
									</div>
								</div>
								
								<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<img src="https://rukminim1.flixcart.com/image/200/200/cms-rpd-images/8dbfd0ff86bf49b6ae3d6a4d1f87e91b_16c9a2bb612_image.png?q=90" style="max-width:100%; vertical-align:middle">
											<h2>AI Quad Camera</h2>
											<p>The power of four cameras in one lets you click incredible pictures. The 12 MP Primary Lens with its large aperture and pixel size lets you capture amazing pictures, even in low-light conditions. The 8 MP Ultra Wide-angle Lens, the 2 MP Super Macro Lens and the 2 MP Portrait Lens let you capture your subject with different perspectives.</p>
										</div>
									</div>
								</div>
								
								<div class="detailDesrptn">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											
											<h2>AI Quad Camera</h2>
											<p>The power of four cameras in one lets you click incredible pictures. The 12 MP Primary Lens with its large aperture and pixel size lets you capture amazing pictures, even in low-light conditions. The 8 MP Ultra Wide-angle Lens, the 2 MP Super Macro Lens and the 2 MP Portrait Lens let you capture your subject with different perspectives.</p>
										</div>
									</div>
								</div>-->
								
								
								 <!--<p>{!!$prd_detail->long_description!!}</p>-->
							</div>

							<div id="video" class="tab-pane">
								<iframe width="560" height="315" src="https://www.youtube.com/embed/3w4t1dYCayM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>

							</div>


							<div id="home" class="tab-pane">
							
								<h6 class="fs18 fw600">{{ucwords($prd_detail->name)}} - Specifications</h6>
								 @include('fronted.mod_product.sub_views.product-specification')
							</div>



							<div id="menu1" class="tab-pane  active in ">
								<div class="review">
									<div class="row">
										
										<div class="col-md-6 col-xs-12">
											
											<div class="">
												<div class="review-form-hdr">
													<h6 class="fw600 fs20">
                <a href="{{route('product_review',base64_encode($prd_detail->id))}}">
                    @if($prd_detail::productRating($prd_detail->id)>0)
                     {{$prd_detail::productRating($prd_detail->id)}}
                    @endif
                    Ratings &amp; 
                    
                     @if($prd_detail::productReviews($prd_detail->id)>0)
                     {{$prd_detail::productReviews($prd_detail->id)}}
                    @endif
                   Reviews
                </a>
   
          
           
           {!!App\Products::productRatingCounter($prd_detail->id)!!}
          
            
            
														</h6>
												</div>
												
												<?php 
						foreach($prd_detail::getAllReview($prd_detail->id,3)  as $rating_row){
												?>
												<div class="row">
												  <div class="col-md-12 col-xs-12">
													<div class="hp-sub-tit reviewlist">
														<div class="row">
															<div class="col-md-8 col-xs-12">
																<h4 class="mb10">{{$rating_row->user_name}}</h4>
																<div class="dateBox"><i class="fa fa-calendar"></i>
                                                                    <?php 
                                                                    $old_date_timestamp = strtotime($rating_row->review_date);
                                                                    echo date('d M ,Y g:i A', $old_date_timestamp); 
                                                                    ?>
																</div>
																<p>{{$rating_row->review}}</p>
																
																
															</div>
															<div class="col-md-4 col-xs-12">
																<div>
			{!!App\Products::userRatingOnproduct($rating_row->rating)!!}
																
																</div>
																
																<?php if($rating_row->uploads!=''){ ?>
																<div class="ratingprodct">
										<img src="{{URL::to('/uploads/review')}}/{{$rating_row->uploads}}">
																</div>
																<?php } ?>
															</div>
														</div>
													</div>
													
												</div>
												</div>
												<?php  } ?>
											</div>
										</div>
										<!-- form-->
											@if (Auth::guard('customer')->check())
										
											<?php 
										
											$purchased=DB::table('order_details')
											->join('orders','order_details.order_id','orders.id')
                                            ->where('order_details.product_id',$prd_detail->id)
                                                    ->where(function($query){
                                                    $query->where('order_details.order_status',3);
                                                    $query->orwhere('order_details.order_status',4);
                                                    $query->orwhere('order_details.order_status',5);
                                                    
                                                    })
                                            ->where('orders.customer_id',auth()->guard('customer')->user()->id)
											->first();
											
											?>
											
                                            @if ($purchased)
				<!--product rating-->
										<div class="col-sm-4 col-md-4 col-lg-4">
											<div class="post-review">
												<h6 class="fw600 fs20">Post Your product Review & Rating</h6>
                                                        @if ($errors->any())
                                                        @foreach ($errors->all() as $error)
                                                        <span class="help-block">
                                                        <p style="color:red">{{$error}}</p>
                                                        </span>
                                                        @endforeach
                                                        @endif
                                                    <form role="form" action="{{ route('addReview')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
													<div class="row">
														
													<div class="col-md-12">
														<div class="form-group"> <!--<span class="input-group-text" id="inputGroupFileAddon01">Add Review</span>-->
															<div class="custom-file">
																
																<div class="box">
																	<input type="file" name="file" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected">
																	<label for="file-2"><i class="fa fa-file"></i> <span>Choose a file…</span></label>
																</div>
																
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<textarea name="review" rows="10" placeholder="Your Review"> </textarea>
															<input type="hidden" name="prd_id" value="{{$prd_detail->id}}">
															<input type="hidden" name="rating" class="rateit-reset-2" id="rateit-range-2" value="">
														</div>
													</div>
													<div class="col-md-12">
                                                            
														<div class="rating">
														   
															<div class="stars">
															     <span class="review-no">Select Ratings
															     </span> 
                                                                    <div class="rateit" data-rateit-mode="font" id="rateit" mode="1">
                                                                    </div>
															     </div>
														</div>
													</div>
													<div class="col-md-12 mt10">
														<button type="submit" class="check" value="submit">Submit</button>
													</div>
												</div>
														</form>

										</div>
										
									</div>
									<!--product rating-->
									
									<!--seller rating-->
										<div class="col-sm-2 col-md-2 col-lg-2">
											<div class="post-review">
												<h6 class="fw600 fs20">seller  Rating</h6>
                                                        @if ($errors->any())
                                                        @foreach ($errors->all() as $error)
                                                        <span class="help-block">
                                                        <p style="color:red">{{$error}}</p>
                                                        </span>
                                                        @endforeach
                                                        @endif
                                                    <form role="form" action="{{ route('sellerRating')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
													<div class="row">
														
													<div class="col-md-12">
														<div class="form-group">
															<input type="hidden" name="prd_id" value="{{$prd_detail->id}}">
															<input type="hidden" name="rating" class="rateit-reset-3" id="rateit-range-3" value="">
														</div>
													</div>
													<div class="col-md-12">
                                                            
														<div class="rating">
														   
                                                            <div class="stars">
                                                                <span class="review-no">Select Ratings
                                                                </span> 
                                                                <div class="rateit" data-rateit-mode="font" id="rateit">
                                                                </div>
                                                            </div>
														</div>
													</div>
													<div class="col-md-12 mt10">
														<button type="submit" class="check" value="submit">Submit</button>
													</div>
												</div>
														</form>

										</div>
										
									</div>
									<!--seller rating-->
									 @endif
									  @endif
								</div>
							</div>

						
							
						</div>
						
							<div id="menu2" class="tab-pane fade ">
								<!--<div class="shippingbox">
									<div class="form-group">
										<h6 class="fw600 fs18 mb20">Product Replace</h6>
										<p class="fs16 mb10">10 Days Replacement Policy?</p>
										
			
									</div>
								</div>-->
								<div class="helpfaq-section">
								<div class="section-title mb20 fs20 fw600">
								<h1>Questions and Answers</h1>
								</div>
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
									
									<?php 
										$i=0;
										foreach($prd_questions as $row_questions){
									?>
									
									<div class="panel panel-default">
										<div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
													<?php echo $row_questions->product_question;?>
												</a>
											</h4>
										</div>
										<div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php echo ($i==0)?'in':'';?>" role="tabpanel" aria-labelledby="heading<?php echo $i;?>">
											<div class="panel-body">
												<p><?php echo $row_questions->product_answer;?> </p>
											</div>
										</div>
									</div>
									<?php $i++;} ?>
									
								</div>
		
							</div>
							</div>
					
					</div>
<!-- tabs end ---->

</div>    

</div>
</div>
    
		</div>   
    </section>
    
   <style>
   .helpfaq-section{padding:0px;}
   .helpfaq-section  .section-title h1{}
   </style>
   
   
    
   {!! App\Helpers\HomeProductSliderHelper::getSimilarProduct($prd_detail->id); !!}
   {!! App\Helpers\HomeProductSliderHelper::getSlider(4); !!}
   {!! App\Helpers\HomeProductSliderHelper::recentlyViewProduct(); !!}
   <section class="productdetails-section">
        <div class="container">
        <div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="about-txt whitebox categText">

				{!!$prd_detail::brandDescription($prd_detail->product_brand,0)!!}
				<br> <br>

				<p>{!!$prd_detail::productcategoryDescription($prd_detail->id)!!}</p> 

	</div>
			</div>
    	</div>
     </div>
     </section>
	 
	<!--<script src="{{ asset('public/fronted/js/zoom-image.js') }}"></script>
	<script src="{{ asset('public/fronted/js/main-zoom.js') }}"></script>-->
	 
	 <script type="text/javascript" src="{{ asset('public/fronted/js/jquery.elevatezoom.js') }}"></script>
	 <script>
		if($('#img_zoom').length){
            $('#img_zoom').elevateZoom({
           
				zoomWindowPosition : 0 ,
				zoomWindowOffsetX : 1,
				zoomWindowOffsetY : 0,
				zoomType: "window",
                gallery:'thumbnails',
                galleryActiveClass: 'active',
                cursor: "crosshair",
                responsive:true,
                easing:true,
				borderSize		: 2,
				borderColour 		: "#e3e3e3",
				tintColour 		: "#e7e7e7",
				zoomWindowWidth 	: 450,
				zoomWindowHeight 	: 450,
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 500,
                lensFadeIn: 500,
                lensFadeOut: 500
				
            });

            $(".open_qv").on("click", function(e) { 
                var ez = $(this).siblings('img').data('elevateZoom');
                $.fancybox(ez.getGalleryList());
                e.preventDefault();
            });

        }
        
        $('.moreless-button').click(function() {
		  var tab=$(this).attr('tab');
		  if(tab==1){
                    $(this).text("Read less");
                    $('.moreless-button').attr('tab',0); 
                    $('.smallText').hide();
                    $('.moretext').show();
		  } else{
		      $('.moreless-button').attr('tab',1); 
		       $(this).text("Read more");
		         $('.smallText').show();
                $('.moretext').hide();
		  }
		 
		});
	</script>
	<!--<script type="text/javascript" src="{{ asset('public/fronted/js/easyzoom.js') }}"></script>-->
	<!--<script>
    	$('.easyzoom').easyZoom();
		
			$('.small-image-slider-single-product-tabstyle-3').slick({ 
				prevArrow: '<i class="fa fa-angle-left"></i>',
				nextArrow: '<i class="fa fa-angle-right slick-next-btn"></i>',
				arrows: true,
				slidesToShow: 6,
				responsive: [{
					breakpoint: 1200,
					settings: {
						slidesToShow: 6,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 991,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 3
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 2
					}
				}]
			}); 
			$('.small-image-slider-single-product-tabstyle-3 a').on('click', function (e) {
				e.preventDefault();

				var $thisParent = $(this).closest('.product-image-slider');
				var $href = $(this).attr('href');
				$thisParent.find('.small-image-slider-single-product-tabstyle-3 a').removeClass('active');
				$(this).addClass('active');

				$thisParent.find('.product-large-image-list .tab-pane').removeClass('active show');
				$thisParent.find('.product-large-image-list ' + $href).addClass('active show');

			});
			
			
		
		
	</script>-->


	
@endsection


@include('fronted.includes.addtocartscript')		

<style>
	/*.zoomContainer{
        min-width: 450px;
    }*/
	#img_zoom { pointer-events: none; }
	.zoomLens {
		width: 100px !important;
		height: 100px !important;
	}
</style>

@section('sizechart') 

<div class="sizechart-model">

	<!-- line modal -->
	<div class="modal fade fadeInUp animated" id="sizechart" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">

		<div class="modal-dialog">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

								<?php 
								 $cats=App\ProductCategories::select('categories.size_chart')
								->join('categories','categories.id','product_categories.cat_id')
								->where('product_categories.product_id',$prd_detail->id)
								->where('categories.size_chart','!=','')
								->first();
								if($cats){?>
								<img src="{{URL::to('/uploads/category/size_chart')}}/{{$cats->size_chart}}" class="img-responsive" alt="">
								<?php } ?>
						</div>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection  

