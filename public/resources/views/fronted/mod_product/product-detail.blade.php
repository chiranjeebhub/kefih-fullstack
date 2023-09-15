@extends('fronted.layouts.app_new')
@section('pageTitle',$prd_detail->name)
@section('metaTitle',$prd_detail->meta_title)
@section('metaDescription',$prd_detail->meta_description)
@section('metaKeywords',$prd_detail->meta_keyword)
@section('content')   
<style>
    span.sellername {
    color: #d01f27;
    font-size: 16px;
}

</style>

<?php  $cuurent_url = Request::segment(1);?>
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
  <?php 
  error_reporting(0);
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
<a href="{{$url}}">{{($cat_trimmed)}}</a>
<i class="fa fa-long-arrow-right"></i>

<?php $br_i++;}?>
<a href="javascript:void(0)">{{(@$pr_name)}}</a>

@endsection     

<?php 

$stcok='InStock';
if($prd_detail->qty<1){
    $stcok='Out of Stock';
}

$extrs_price=$extra_price;
 $decodeInput=explode("~~~",base64_decode(Request()->id));

        $product_id=$decodeInput[0];
       
        $rewards_point=DB::table('product_reward_points')
                        ->where('product_id',$product_id)->first();
                        
        $prd=DB::table('products')
         ->select('product_type')
        ->where('id',$product_id)->first();

        $color_id=$decodeInput[2];
        
        if($color_id!='' && $prd->product_type==3 )
			{
$color_price=DB::table('product_attributes')
->where('product_id',$product_id)
->where('color_id',$color_id)->first();


// $extrs_price=$color_price->price;
$color_image=App\ProductImages::getConfiguredImages($product_id,$color_id,0);


        //$prd_img=$color_image[0]['image'];
        $prd_img=URL::to('/uploads/products').'/'.$color_image[0]['image'];
        $prd_slider=$color_image;

	
        //$flag=1;
        $flag=2;
			}else{
$prd_img=URL::to('/uploads/products').'/'.$prd_detail->default_image;
$prd_slider=App\Products::prdImages($product_id);
$flag=2;
			}
		
?>

<!--data layer-->
<script>
var a= window.location.href;
var res = a.split("/");
dataLayer.push({
  "ecommerce": {
    "impressions": [],
    "detail": {
      "products": [{
        "id": "{{$product_id}}",
        "name": "{{ ucwords($prd_detail->name) }}",
        "price": "{{$prd_detail->spcl_price+$extrs_price}}",
        "brand": "{{ ucwords($prd_detail->brand_name) }}",
        "category": res[4],
        "position": 0
      }]
    },
    "promoView": {
      "promotions": [{
        "id": "bts",
        "name": "Back To School",
        "creative": "PRODUCT banner",
        "position": "right sidebar"
      }]
    }
  }
});
</script>

<script type="application/ld+json">

 { 

   "@context":"https://schema.org",

    "@type":"Product", 

   "productID":"{{$product_id}}",

    "name":"{{ ucwords($prd_detail->name) }}",

    "description":"{{$prd_detail->short_description}}",

    "url":"{{$subject}}",

    "image":"{{$prd_img}}",

    "brand":"{{ ucwords($prd_detail->brand_name) }}",

    "offers": [ 

{ 

"@type": "Offer",

 "price": "{{$prd_detail->spcl_price+$extrs_price}}", 

"priceCurrency": "INR",

 "itemCondition": "https://schema.org/NewCondition", 

"availability": "https://schema.org/InStock" 

}

 ], 

"additionalProperty": [{ 

"@type": "PropertyValue", 

"propertyID": "item_group_id", 

"value": "{{ ucwords($prd_detail->sku) }}"

     }]

 } 

</script>

<!--data layer-->

<section class="productdetails-section">
	<div class="container">
		<div class="card">
			<div class="container-fliud">
				<div class="wrapper row">					
					<div class="col-xs-12 col-sm-6 col-md-6 fixedPosition">						
						<div class="preview">
							<!--<div class="wishlisticon">
								<a title="wishlist" href="#"><i class="fa fa-heart-o wishList" prd_id='{{$prd_detail->id}}'></i>  </a>
							</div>-->
							<div class="catalog-view_op1">								
								<div class="detail-gallery">
									<div class="thumb-product mid">											
										<!--<img id="img_zoom" src="{{$prd_img}}" data-zoom-image="{{$prd_img}}" alt=""/>-->
										
										<div id="facnyBoxitem">
											<?php $ij=0; $pr=3;?>
											@foreach($prd_slider as $row)											
											<?php if($flag==2){?>
											<a class="<?php echo ($ij>0)?"dnoneD":"fancyZoom";?>" data-fancybox="gallery" href="{{URL::to('/uploads/products')}}/{{$row['image']}}">
                                                
                                                <img id="img_zoom" src="{{URL::to('/uploads/products')}}/{{$row['image']}}" data-zoom-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" alt="" class="imgs" />
                                                
												<!--<i class="fa fa-search-plus" aria-hidden="true"></i>-->
											</a>
											<?php } if($flag==1){?>
											@if($ij>0)
											<a class="<?php echo ($ij>0)?"dnoneD":"fancyZoom";?>" data-fancybox="gallery" href="{{$prd_img}}">
                                               
                                                <img id="img_zoom" src="{{$prd_img}}" data-zoom-image="{{$prd_img}}" alt="" class="imgs"/>
                                                
												<!--<i class="fa fa-search-plus" aria-hidden="true"></i>-->
											</a>
											@endif()                    
											<?php } ?>									
											<?php $ij++; $pr++;?>
											@endforeach
											</div>			
									</div>
									<div class="gallery-control nonCircular">
										<div class="carousel thumblist" data-vertical="true">
											<ul class="list-none" id="leftthumbslidercontainer">
												<?php $ij=0; $pr=3;?>
												@foreach($prd_slider as $row)											
												<?php if($flag==2){?>													
												<li><a href="javascript:void(0)"   data-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" data-zoom-image="{{URL::to('/uploads/products')}}/{{$row['image']}}" class="active"> <img  src="{{URL::to('/uploads/products')}}/{{$row['image']}}" class="customThumnnail" data-large-image="{{URL::to('/uploads/products')}}/{{$row['image']}}"/></a></li>													
												<?php } if($flag==1){?>
												@if($ij>0)
												<li><a href="javascript:void(0)"  data-image="{{$row->image}}" data-zoom-image="{{$row->image}}" class=""> <img  src="{{$row->image}}" class="customThumnnail" data-large-image="{{$row->image}}" /></a></li>													
												@endif()                    
												<?php } ?>									
												<?php $ij++; $pr++;?>
												@endforeach																							
											</ul>
										</div>
										<div class="control-button-gallery text-center">																				    
											<a href="#" class="prevMe"><i class="fa fa-angle-up"></i></a>
											<a href="#" class="nextMe"><i class="fa fa-angle-down"></i></a>
										</div>
									</div>
								</div>								
							</div>					
						</div>
						<div class="genuanmainbox">
                                    <ul>
                                        <li> 100% Geniune Products </li>
                                        <li>Easy Return Policy</li>
                                        <li>Sold by: {{$prd_detail->seller_name}}</li>
                                    </ul>
												</div>
					</div>
					<div class="details col-xs-12 col-sm-6 col-md-6">
						<h3 class="product-title">{{ ucwords($prd_detail->name) }}</h3>
                       <div class="detail-rating">
							{!!App\Products::productRatingCounter($prd_detail->id)!!} <span class="review-no"><a href="#ratingsection" onclick="$('#ratiingreviewtab').trigger('click');">	{!!App\Products::productRating($prd_detail->id)!!}   Ratings &amp; {!!App\Products::productReviews($prd_detail->id)!!} Reviews </a> </span>
						</div>
						@if(!empty($prd_detail->product_range1) || !empty($prd_detail->product_range2)|| !empty($prd_detail->product_range3))
                        <div class="acres-price">
                        <div class="row">
                        <div class="col-xs-4 col-sm-4 pdingrgt0">
                            <div class="price-txt">
                            <h6>{{$prd_detail->product_range1}}</h6>
                            @if($prd_detail->product_price1 != 0)
                            <h4 class="txt-blue"><i class="fa fa-rupee"></i> {{$prd_detail->product_price1}}</h4>
                            @endif
                            </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 pdingrgt0">
                            <div class="price-txt">
                            <h6>{{$prd_detail->product_range2}}</h6>
                            @if($prd_detail->product_price2 != 0)
                            <h4><i class="fa fa-rupee"></i> {{$prd_detail->product_price2}}</h4>
                            @endif
                            </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 pdingrgt0">
                            <div class="price-txt">
                            <h6>{{$prd_detail->product_range3}}</h6>
                            @if($prd_detail->product_price3 != 0)
                            <h4><i class="fa fa-rupee"></i> {{$prd_detail->product_price3}}</h4>
                            @endif
                            </div>
                            </div>
                        </div>
                        </div>
                        @endif
                        <div class="prdtdtailprice">
                            <h4 class="price fs20">
                                    @if ($prd_detail->price!='' && $prd_detail->price!=0)
                                    <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$prd_detail->spcl_price+$extrs_price}}</span>
                                    <del><i class="fa fa-rupee"></i><span id="prd_old_price_0">{{$prd_detail->price+$extrs_price}}</span></del>
                                    <span class="offer_txt"><span class="percent">{{$prd_detail::offerPercentage($prd_detail->price+$extrs_price,$prd_detail->spcl_price+$extrs_price)}}</span> <span> % off</span></span>
                                    @else
                                    <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$prd_detail->spcl_price+$extrs_price}}</span>
                                    @endif
                            </h4>
                           <span class="taxesinclusive">Inclusive of all taxes</span>
                        </div>
						<ul class="availtext">
                                @if($rewards_point->reward_points!='0' && $rewards_point->reward_points!='')
                                <!--<li><p> <strong>Rewards Points</strong> : <i class="fa fa-rupee"></i><span class="out-stock">{{$rewards_point->reward_points}}</span></p></li>-->
                                @endif
               
                @if ($prd_detail->qty!=0)
                <li id="qtyDiv"><p>Availability : <span class="out-stock clrred"> In stock</span></p></li>
                
                @else
                
                <li id="qtyDiv"><p>Availability : <span class="in-stock"> Out of stock</span></p></li>
                @endif

				
				
                
						</ul> 
						<ul class="availtext">
						@if (!empty($prd_detail->sku))
				<li id=""><p>Product Code : <span>{{$prd_detail->sku}}</span></p></li>
				@endif 
						</ul> 

						<ul class="availtext">
						@if (!empty($prd_detail->material))
				<li id=""><p>Material : <span>{!!App\Products::getProductMaterial($prd_detail->material)!!}</span></p></li>
				@endif 
						</ul> 


						<!--<div class="row">
						<div class="col-12 col-sm-9 col-md-8">
						<ul class="availtext Sizes-list list-inline">
                    <li>
                      <p>Sizes  : </p>
                    </li>
                    <li><a href="#">S</a></li>
                    <li><a href="#">M</a></li>
                    <li><a href="#">L</a></li>
                    <li><a href="#">XL</a></li>
                    <li><a href="#">XXL</a></li>
                  </ul>
						</div>
						<div class="col-12 col-sm-3 col-md-4">
						<div class="sizechart">
                            <a type="button" value="button" class="showSizechart" prd_id="763" href="javascript:void(0)">Size Chart <img src="{{ asset('public/fronted/images/sizechart.png') }}" alt=""></a>
                        </div>
						</div>
												</div>   -->        
                       
						
						<!--<div class="sizechart">
                            MOQ : <span class="sws_moq" style="font-weight:600;">{{$prd_detail->moq}}</span>
                        </div>
                        <div class="sizechart">
                            Sample Price : <span class="sws_moq" style="font-weight:600;"><i class="fa fa-rupee"></i> {{$prd_detail->sample_price}}</span>
                        </div>-->
 
						@include('fronted.mod_product.sub_views.products_attributes')
						
					<div class="row sizeboxes qty-btn">
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
							<span class="sizes">Quantity : </span>	
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<div class="quantity buttons_added">
									<a href="javascript:void(0)" class="sign minus changeQty" method="2" row="0"><i class="fa fa-minus"></i></a><input type="number" id="prd_qty_0" name="qty" min="{{$prd_detail->moq}}" value="1" title="Qty" class="input-text qty text" size="{{$prd_detail->moq}}" disabled ><a href="javascript:void(0)" class="sign plus changeQty" method="1" row="0"><i class="fa fa-plus"></i></a>
								</div>
							</div>

							@if(!empty($prd_detail->product_size_chart))
							<div class="col-xs-12 col-sm-2 col-md-2 col-lg-3">
								<div class="sizechart">							
								<a type="button" value="button" class="showSizechart" prd_id="{{$prd_detail->id}}" href="javascript:void(0)">Size Chart <img src="{{ asset('public/fronted/images/sizechart.png') }}" alt=""></a>
								</div>
							</div>
							@endif 

							
						</div> 

				

						<?php  $size_color_require=0; ?>
                        
                       <!-- <button type="button" class="registrbtn" style="width:auto;" data-toggle="modal" data-target="#quoteModal">Get Quote</button>-->
                        <div class="detail-cartbtns">
						  <div class="row">
							
								<div class="col-md-12 col-xs-12">
									<span id="back_response_msg_0"></span>
                                    @if ($prd_detail->qty!=0)
                                    <div class="row">
                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                          @if($prd_detail->product_type==2)
                                            <button type="submit"
                                            class="btn btn-danger btn-block btn-lg waddTocart noProblem"
                                            prd_page='1'
                                            prd_index='0' 
                                            prd_id='{{$prd_detail->id}}'
                                            size_require="{!!$prd_detail::Issize_requires($prd_detail->id)!!}"
                                            color_require="{!!$prd_detail::Iscolorrequires($prd_detail->id)!!}"
                                            size_id="0"
                                            w_size_id="0"
                                            color_id="{{$decodeInput[2]}}"
                                            prd_type="{{$prd_detail->product_type}}"
                                            ><span>+</span> Add to cart </button> 
                                            @else

                                            <button type="submit"
                                            class="btn btn-danger btn-block btn-lg addTocart noProblem"
                                            prd_page='1'
                                            prd_index='0' 
                                            prd_id='{{$prd_detail->id}}'
                                            size_require="{!!$prd_detail::Issize_requires($prd_detail->id)!!}"
                                            color_require="{!!$prd_detail::Iscolorrequires($prd_detail->id)!!}"
                                            size_id="0"
                                                color_id="{{$decodeInput[2]}}"
                                            prd_type="{{$prd_detail->product_type}}"
                                            ><span>+</span> Add to cart </button> 
                                            @endif()
                                      
                                      </div>
                                      <div class="col-xs-4 col-sm-5 col-md-5 pding-lft0">
                                          @if($prd_detail->product_type==2)
                                             <button type="submit" 
                                             class="btn btn-warning btn-block btn-lg wbuyNow noProblem"
                                            prd_page='1'
                                            url="{{App\Products::getProductDetailUrl($prd_detail->name,$prd_detail->id)}}"
                                            prd_index='0' 
                                            prd_id='{{$prd_detail->id}}'
                                            size_require="{!!App\Products::Issize_requires($prd_detail->id)!!}"
                                            color_require="{!!App\Products::Iscolorrequires($prd_detail->id)!!}"
                                            size_id="0"
                                             prd_type="{{$prd_detail->product_type}}"
                                            w_size_id="0"
                                                color_id="{{$decodeInput[2]}}">
                                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                             Buy Now</button>
                                              @else
                                              <button type="submit"
                                              class="buynowbtn btn btn-warning btn-block btn-lg buyNow noProblem"
                                            prd_page='1'
                                            url="{{App\Products::getProductDetailUrl($prd_detail->name,$prd_detail->id)}}"
                                            prd_index='0' 
                                            prd_id='{{$prd_detail->id}}'
                                            size_require="{!!App\Products::Issize_requires($prd_detail->id)!!}"
                                            color_require="{!!App\Products::Iscolorrequires($prd_detail->id)!!}"
                                            size_id="0"
                                                color_id="{{$decodeInput[2]}}"
                                             prd_type="{{$prd_detail->product_type}}"
                                            >
                                                <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                             Buy Now</button>
                                              @endif()
                                      
                                </div>
                                      <div class="col-xs-3 col-sm-2 col-md-2 pding-lft0">
                                          <a title="wishlist" href="#" class="btn btn-light btn-block btn-lg">
										  @php 
										 	 $wishlisticon = App\Helpers\CommonHelper::productHeartCheck($prd_detail->id);
										  @endphp 
										 {!! $wishlisticon !!}
											
											
										</a>
                                       
                                      </div>
                                      </div>
									
									
                                    
									
									
									 
									@else
									
									<style>
									    .outOfStcok {
                                            display: inline-block!important;
                                        }
									</style>
									 @endif()
									
        <button type="submit"
        class="add-to-cart-btn add-to-cart ml30 outOfStcok">
         <i class="fa fa-shopping-cart"></i>  Out of stock
        </button>
									
        <button type="submit" class="bynow add-to-cart-btn outOfStcok">
       <i class="fa fa-shopping-cart"></i> Out of stock
        </button>
        
        
        <button type="submit"
        class="add-to-cart-btn add-to-cart ml30 outOFdelivery">
        <i class="fa fa-shopping-cart"></i> NO delivery
        </button>
									
        <button type="submit" class="add-to-cart-btn bynow outOFdelivery">
        <i class="fa fa-shopping-cart"></i> NO delivery
        </button>
								</div>
							
                            <div class="sellerBlock">
                                <!--<div class="selfSold">
                                     <span class="soldBy">Sold By <span class="sellername">{{$prd_detail->seller_name}}</span></span>
                                </div>-->
                                   
                                    @if($prd_detail->other_seller>0)
                                    <span class="otherSeller" prd_id="{{$prd_detail->id}}">See other offer</span>
                                    @endif()

								
                            </div>
						</div>
                        </div>
						<div class="row mt20">
							<!--<div class="col-xs-12 col-sm-4 col-md-4">
								<span class="delivery">Check Port Code </span>     
							</div>  
							<div class="col-xs-12 col-sm-8 col-md-8">
								
					<div class="pincodebtn">
					<input 
                    type="text" 
                    id="pincode"
                    class="form-control"
                    placeholder="Enter Port Code"
                    onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)"
                        product_id="{{$prd_detail->id}}",
                        product_name="{{$prd_detail->name}}"
                        menSizeID="0"
                        womenSizeID="0"
                        colorID="0"
                        weight="{{$prd_detail->weight}}"
                        height="{{$prd_detail->height}}"
                        length="{{$prd_detail->length}}"
                        width="{{$prd_detail->width}}"
					>
   
					<div class="searchpin">
						<a 
                        href="javascript:void(0)"
                        class="checkPinCode onlyDigit" element="pincode" 
                       
						>Check</a>
					  </div>
                    <div id="Pincodedisplay">
                    
                    </div>
					</div>
					
					 <span id="pincode_msg" style="color:red;"></span>
					
								
							</div>-->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="seller-msg">
								<!--<p> Please enter your area's Pin Code to get the estimated date of delivery</p>-->
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="seller-msg">
								<!--<p> 
								@if($prd_detail->delivery_days!=0)
								Deliver in {{$prd_detail->delivery_days}} days
								@endif

								@if($prd_detail->shipping_charges!=0)
								<br>Shipping Charges : <i class="fa fa-rupee"></i> {{$prd_detail->shipping_charges}}
								@endif
								</p>-->
							</div>
						</div>
						

						</div>
                        
                  <div class="details-dec">

                                <h4>Description</h4>
								@if(!empty($prd_detail->short_description))
								<div id="sellers-table1">
								{!! substr(strip_tags($prd_detail->short_description),0,200) !!}
								
									</div>
								<div id="sellers-table3">
                                    <p>{!! $prd_detail->short_description !!}</p>
                                </div>
								<div id="see-all3">
									<span id="see-all-hide3">Read More</span>
								</div>
						
								@endif 
 </div>
                        
					</div>
					
				</div>
			</div>
		</div>
	</div>
</section>


   
<section class="about-spfc-section" id="ratingsection">
<div class="container">

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


<!--tabs start--->
<div class="tabs-details">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item active">
							<a class="nav-link" data-toggle="tab" href="#description" aria-expanded="true">Additional Information</a>
							</li>
							<!--<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#video" aria-expanded="false">Videos</a>
							</li>
							<li class="nav-item active">
								<a class="nav-link" data-toggle="tab" href="#home" aria-expanded="true">Product Detail</a>
							</li>
							
                            <li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#menu2" aria-expanded="false"> Company Profile </a>
							</li>-->
                            <li class="nav-item">
								<a class="nav-link" data-toggle="tab" id="ratiingreviewtab" href="#menu1" aria-expanded="false"> Ratings &amp; Reviews </a>
							</li>
<!--
							<?php 
								$prd_questions=App\ProductQuestions::productQuestions($prd_detail->id);
								if(@$prd_questions[0]->product_question!=''){
							?>
							<li class="nav-item ">
								<a class="nav-link" data-toggle="tab" href="#menu2" aria-expanded="false">  QA's </a>
							</li>
							<?php } ?>
-->
							@if($prd_detail->return_days!='')
    								<!--<li class="nav-item ">
                                        <a class="nav-link" data-toggle="tab" href="#replacetab" aria-expanded="false"> Replace Available </a>
                                    </li>-->
							@endif()
						
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div id="description" class="tab-pane active in">

						



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
								 <p>{!!$prd_detail->long_description!!}</p>

							<u><h3>Legal Information</h3></u>
							<p><strong>Country of Manufacturing:</strong> {{$prd_detail->country_of_manufacturing}}</p>
							<p><strong>Manufacturer's Name:</strong> {{$prd_detail->manufacturer_name}} </p>
							<p><strong>Packer's Name:</strong> {{$prd_detail->packer_name}} </p>
							<p><strong>Generic Name:</strong> {{$prd_detail->generic_name}} </p>


							</div>

							<!--<div id="video" class="tab-pane">
								<iframe width="560" height="315" src="https://www.youtube.com/embed/3w4t1dYCayM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>

							</div>-->

							<div id="home" class="tab-pane">
								 <p>{!!$prd_detail->short_description!!}</p>
								<h6 class="fs18 fw600 mt20 mb20">{{ucwords($prd_detail->name)}}</h6>
								 @include('fronted.mod_product.sub_views.product-specification')
							</div>

							<div id="menu1" class="tab-pane fade">
								<div class="review">
									<div class="row">
										
										<div class="col-md-12 col-xs-12 col-xs-12">
											
											<div class="review">
												<div class="review-form-hdr">
													<h6 class="fw600 fs20">
													@if($prd_detail::productRating($prd_detail->id)>0)
													{!!App\Products::productRatingCounterNumber($prd_detail->id)!!}/5
															@endif
													
													@if($prd_detail::productReviews($prd_detail->id)>0)
															
															<span class="review-no"> ({{$prd_detail::productReviews($prd_detail->id)}} rating)  </span>
															@endif

														{{--
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
														--}}

														{!!App\Products::productRatingCounter($prd_detail->id)!!}
																				
													</h6>
												</div>
												
												<?php 
												foreach($prd_detail::getAllReview($prd_detail->id,3)  as $rating_row){
												?>


												<div class="media">
														<div class="media-left">
														<div class="rating-img">
															<img src="{{$prd_img}}" >
														</div>
														</div>
														<div class="media-body">
														<h4>{{$rating_row->user_name}}</h4>
														<p class="rating-stars">
															
														{!!App\Products::userRatingOnproduct($rating_row->rating)!!}

															<span class="review-no"> 
																<?php 
                                                                    $old_date_timestamp = strtotime($rating_row->review_date);
                                                                    echo date('d, M Y', $old_date_timestamp); 
                                                                    ?>
																	  </span>
														</p>
														
																<p>{{$rating_row->review}}</p>
														</div>
													</div>
												<?php 	/*
												?>
											
												<div class="row">
													<div class="col-md-12 col-xs-12">
													<div class="hp-sub-tit reviewlist">
														<div class="row">
															<div class="col-md-4 col-xs-12">
																<h4 class="mb10">
																
																
																	
																{{$rating_row->user_name}}</h4>
																<div class="dateBox"><i class="fa fa-calendar"></i>
                                                                    <?php 
                                                                    $old_date_timestamp = strtotime($rating_row->review_date);
                                                                    echo date('d M ,Y g:i A', $old_date_timestamp); 
                                                                    ?>
																</div>
																
																
																
															</div>
															<div class="col-md-4 col-xs-7">
																<div>
																{!!App\Products::userRatingOnproduct($rating_row->rating)!!}
																<p>{{$rating_row->review}}</p>
																</div>
																
															</div>
															
															
																    
															
															<?php if($rating_row->uploads!=''){ ?>
																	<div class="col-md-4 col-xs-5">
																		<a title="Click Here" href="{{URL::to('/uploads/review')}}/{{$rating_row->uploads}}" class="fancybox" >
																		<img src="{{URL::to('/uploads/review')}}/{{$rating_row->uploads}}"></a>
																</div>
																<?php } ?>
														</div>
													</div>
													
												</div>
												</div>
											


												<?php */ } ?>
											</div>
										</div>
										<!-- form-->
											@if (Auth::guard('customer')->check())
										
											<?php 
											
											$purchased=DB::table('order_details')
											->join('orders','order_details.order_id','orders.id')
                                            ->where('order_details.product_id',$prd_detail->id)
                                            ->where('order_details.order_status',3)
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
																	<label for="file-2"><i class="fa fa-file"></i> <span>Please upload your product photo here</span></label>
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
									 @endif
									  @endif
								</div>
							</div>

						
							
						</div>
							
							<div id="replacetab" class="tab-pane">
								<div class="shippingbox">
									<div class="form-group">
										<h6 class="fw600 fs18 mb20">Product Replace
									
										
										</h6>
										<p class="fs16 mb10">{{$prd_detail->return_days}} Days Replacement.</p>
										
									</div>
								</div>
								
							</div>
                            
                            <div id="menu2" class="tab-pane fade">
                                
								@php 
										$companydata = App\Helpers\CommonHelper::getVendorCompanyProfile($prd_detail->vendor_id);
								@endphp					
								{!! $companydata->about_us !!}				
                            </div>
					
					</div>
<!-- tabs end ---->

</div>    

</div>
</div>
    
		</div>   
    </section>
     
<section class="product-details-section wrap wrap-bdrtp">
	<div class="container-fluid">
		
		{!! App\Helpers\HomeProductSliderHelper::getSimilarProduct($prd_detail->id); !!}
		<?php /*
{!! App\Helpers\HomeProductSliderHelper::getSliderCustomized(1); !!}
		{!! App\Helpers\HomeProductSliderHelper::getSliderCustomized(2); !!}
		{!! App\Helpers\HomeProductSliderHelper::recentlyViewProduct(); !!}*/ ?>
	
	</div>
</section>
   
@endsection

@section('sizechart')

<style>
	#img_zoom { /*pointer-events: none;*/ }
	.zoomLens {
		width: 100px !important;
		height: 100px !important;
	}
   .helpfaq-section{padding:0px;}
   .helpfaq-section  .section-title h1{}	
</style>


@endsection     
@section('scripts')
<script>
    $('.slider-main header').removeAttr('data-spy');
</script>
<script>
    
</script>

<div id="quoteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div class="contactform quoteform">
            <h6 class="text-center">Get Quote</h6>
            <form>
				<input type="hidden" value="{{$prd_detail->id}}" id="myproduct_enquiry">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" id="quote_name" class="form-control" placeholder="Name" name="quote_name">
							<div id="quote_name-info" class="validation-info text-danger"></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="text" id="quote_mail" class="form-control" placeholder="Email Id" name="">
							<div id="quote_mail-info" class="validation-info text-danger"></div>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                            <input class="form-control" type="number" id="country_code" placeholder="Country code">
                            <div id="country_code-info" class="validation-info text-danger"></div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                            <input type="number" class="form-control" id="quote_phone" placeholder="Phone" name="phone">
            				<div id="quote_phone-info" class="validation-info text-danger"></div>
            				</div>
                        </div> 
                   </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="quote_qty" placeholder="Quantity" name=" ">
    							<div id="quote_qty-info" class="validation-info text-danger"></div>
                            </div>
                        </div>
                    </div>

                <div>
                    <textarea class="form-control" name="" id="quote_comment" placeholder="Comments"></textarea>
					<div id="quote_comment-info" class="validation-info text-danger"></div>
                </div>
                <div class="row">
            <div class="col-sm-6 col-sm-pull-0 col-md-4 col-md-pull-0"> 
            <input type="button" id="bookquote" onclick="submitQuoteForm();" value="Submit" class="registrbtn">
            </div>
            </div>
            </form>      
        </div>
      </div>
    </div>

  </div>
</div>


@endsection     



