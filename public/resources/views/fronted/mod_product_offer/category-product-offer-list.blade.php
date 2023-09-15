@extends('fronted.layouts.app_new')
@section('pageTitle','Offer Zone')
@section('content') 

@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Offer Zone</a>
@endsection 
       
<section class="prolisting-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="bgwhite clearfix mt20 listmain">

<!--offer section-->


<div class="tab-content">
	
	<!-- grid view -->
	
	<div class="tab-pane fade in active">
	
		<div class="pro-listing offerlistiing">
			<div class="slide-heading">
			   
			  <h6 class="fs20 fw600">{{$cat_name}} </h6>   
			</div>    
			<ul>
		@foreach($products as $product)
		<?php $prdImages=App\Products::prdImages($product->id); ?>
		<li class=" wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
			<div class="product-grid8">
			<div class="product-image8">
			     <div class="simpleimg"><img class=" " src="{{URL::to('/uploads/products')}}/{{$product->default_image}}"></div>
		   <div id="myCarousel_{{$product->id}}" class="carousel slide" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
			<li data-target="#myCarousel_{{$product->id}}" data-slide-to="0" class="active"></li>
				@php
				$i = 1
				@endphp
			@foreach($prdImages as $row)
				<li data-target="#myCarousel_{{$product->id}}" data-slide-to="{{$i}}"></li>
				@php
				$i++;
				@endphp
			@endforeach
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner">
			<div class="item active">
			  {!! App\Helpers\CustomFormHelper::support_image('uploads/products',$product->default_image); !!}
			</div>
@foreach($prdImages as $row)
 <div class="item">
    <img class=" " src="{{URL::to('/uploads/products')}}/{{$row['image']}}">
    </div>
@endforeach
		  </div>
		</div>
				
		 
			</div>
				<ul class="social">
                    <li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->id}}"><i class="fa fa-eye"></i></a></li>
                    <li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
                    <li><a href="javascript:void(0)" data-tip="Add to Cart" title="Add to Cart"
                     class="addTocart"
                        prd_page='0'
                        url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}"
                        prd_index='{{$product->id}}' 
                        prd_id='{{$product->id}}'
                        
                        size_require="{!!App\Products::Issize_requires($product->id)!!}"
                        color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                        size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
                        color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
                    ><i class="fa fa-shopping-cart"></i></a></li>
                    <li><a href="javascript:void(0)" data-tip="Compare" title="Compare"><i class="material-icons">&#xe915;</i></a></li>
				</ul>

			<div class="product-content text-left">
			  <h3 class="title"><a href="{{App\Products::getProductDetailUrl($product->name,$product->id)}}">{{$product->name}} </a></h3>
				
				
					 @include('fronted.mod_product.sub_views.products_attributes_listing_page')
				
					
			<div>	
			 {!!App\Products::productRatingCounter($product->id)!!}
				
			  <div class="price"> Rs
					@if ($product->spcl_price!='')
					<i id="prd_price_{{$product->id}}">{{$product->spcl_price}}</i>
				
	<span>{{$product->price}}</span><aside>{{App\Products::offerPercentage($product->price,$product->spcl_price)}}% off</aside>
					@else
						<i id="prd_price_{{$product->id}}">{{$product->price}}</i>
					@endif
			  </div>
				
			</div>	
				
			  <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
			  	<span id="back_response_msg_{{$product->id}}"></span>
			  <button type="submit" value="submit"
			  class="add-to-cart-btn addTocart hideCartButtton"
            prd_page='0'
            url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}"
            prd_index='{{$product->id}}' 
            prd_id='{{$product->id}}'
            size_require="{!!App\Products::Issize_requires($product->id)!!}"
            color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
            size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
            color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
			  ><i class="fa fa-shopping-cart"></i> Add to cart</button>     
			</div>

			</div> 

		</li>
		@endforeach
		@if (count($products) ==0)
			No Product Found
		@endif
		</ul>   
		</div>    
</div>  

	<!-- End grid view -->
	
	<!-- list grid view -->

	<div id="list" class="tab-pane fade">
    <div class="pro-listing">
        <div class="slide-heading">
            <h6 class="fs20 fw600">{{$cat_name}} </h6>   
        </div>
		
        @foreach($products as $product)
        <div class="row ">

            <div class="for-spacing wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
    <div class="product-grid8 list-views-listing">
    <div class="col-md-5 col-sm-12 col-xs-12">
        <!-- <div class="first-images-products">
             <img class=" " src="images/handicam-img.jpg">
        </div> -->
		<div class="product-image8">
		     <div class="simpleimg"><img class=" " src="{{URL::to('/uploads/products')}}/{{$product->default_image}}"></div>
		 <div id="myCarousel_l{{$product->id}}" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
			<li data-target="#myCarousel_l{{$product->id}}" data-slide-to="0" class="active"></li>
				@php
				$i = 1
				@endphp
			@foreach($prdImages as $row)
				<li data-target="#myCarousel_l{{$product->id}}" data-slide-to="{{$i}}"></li>
				@php
				$i++;
				@endphp
			@endforeach
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner">
			<div class="item active">
			  {!! App\Helpers\CustomFormHelper::support_image('uploads/products',$product->default_image); !!}
			</div>
	@foreach($prdImages as $row)
 <div class="item">
    <img class=" " src="{{URL::to('/uploads/products')}}/{{$row['image']}}">
    </div>
@endforeach
		  </div>
</div>
		</div>
        <ul class="social">
            <li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->id}}"><i class="fa fa-eye"></i></a></li>
            <li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
            <li><a href="javascript:void(0)" data-tip="Add to Cart" title="Add to Cart"
             class="addTocart"
             prd_page='0'
            url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}"
            prd_index='{{$product->id}}' 
            prd_id='{{$product->id}}'
            size_require="{!!App\Products::Issize_requires($product->id)!!}"
            color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
            size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
            color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
            ><i class="fa fa-shopping-cart"></i></a></li>
            <li><a href="javascript:void(0)" data-tip="Compare" title="Compare"><i class="material-icons">&#xe915;</i></a></li>
        </ul>
   </div>

   <div class="col-md-7 col-sm-12 col-xs-12">
		<div class="product-content text-left">
		  <h3 class="title"><a href="{{App\Products::getProductDetailUrl($product->name,$product->id)}}">{{$product->name}} </a></h3>
			
			@include('fronted.mod_product.sub_views.products_attributes_listing_page')
			
		  <span class="product-shipping"><i class="fa fa-star text-blue"></i> <i class="fa fa-star text-blue"></i> <i class="fa fa-star text-blue"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>            
		  <div class="price list-view-price">Rs.
					<i id="prd_price_{{$product->id}}_l">@if ($product->spcl_price!='')</i>
				{{$product->spcl_price}}<span>{{$product->price}}</span><aside>{{App\Products::offerPercentage($product->price,$product->spcl_price)}}% off</aside>
				@else
				<i id="prd_price_{{$product->id}}_l">{{$product->price}}</i>
				@endif
		  </div>
		  <div class="offer-jone">
				<p>{{$product->short_description}}</p>
		  </div>
		 <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}_l">
			  	<span id="back_response_msg_{{$product->id}}_l"></span>
			  <button type="submit" value="submit"
			  class="add-to-cart-btn addTocart hideCartButtton"
            prd_page='0'
            url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}"
            prd_index='{{$product->id}}_l' 
            prd_id='{{$product->id}}'
            size_require="{!!App\Products::Issize_requires($product->id)!!}"
            color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
            size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}"
            color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}"
			  ><i class="fa fa-shopping-cart"></i> Add to cart</button>    
		</div>
	</div>

    </div> 

</div>
        </div>
		@endforeach
		@if (count($products) ==0)
			No Product Found
		@endif

    </div>

 </div>

	<!-- // End list grid view -->

</div>

{{ $products->links() }}
<!--offer section-->
</div>    
</div>    
</div>       
</section>      
  
@endsection


@include('fronted.includes.addtocartscript')


