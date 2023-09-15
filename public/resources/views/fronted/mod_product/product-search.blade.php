@extends('fronted.layouts.app_new')
@section('content') 
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection     
<section class="prolisting-section">
<div class="container">
<div class="row">
  @include('fronted.includes.filter')   
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="bgwhite clearfix mt20">
<div class="pro-listing">
	<div class="slide-heading">
	
	<?php
				$search_term = Request::segment(1);
			?>
	  <h6 class="fs20 fw600">Electronic Items {{$search_term}} </h6>   
	</div>    
<ul>
@foreach($products as $product)
<li class=" wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
    <div class="product-grid8">
    <div class="product-image8">
    <a href="{{'product_details'}}/{{base64_encode($product->id)}}">
    <img class="" src="{{URL::to('/uploads/products')}}/{{$product->image}}">
    </a>
    <span class="product-discount-label"><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a> </span> 
    </div>
    <div class="product-content text-left">
    <h3 class="title"><a href="{{'product_details'}}/{{base64_encode($product->id)}}">{{$product->name}}</a></h3>
    <span class="product-shipping"><i class="fa fa-star text-blue"></i> <i class="fa fa-star text-blue"></i> <i class="fa fa-star text-blue"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> </span>            
    <div class="price">Rs{{$product->price}}<span>Rs{{$product->spcl_price}}</span><aside>70% off</aside>
    </div>
      <button type="submit" value="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Add to cart</button>     
    </div>
    </div>     
</li>
@endforeach
	@if( count($products)< 1 )               
		 <li>
			<p align="center" style="color:red;">Nothing to see here.</p>
		  </li> 
	@endif				
</ul>    
</div>    
</div>
{{ $products->links() }}    
</div>    
</div>    
</div>       
</section>      
 <section class="what-more-section">
    <div class="container">
    <div class="row mt">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ">
    <div class="whatmorebox zoom1">
    <a href="#"><img src="{{ asset('public/fronted/images/smart-tv50%25.jpg') }}" alt=""> </a>   
    </div>    
    </div>   
        
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ">
    <div class="whatmorebox zoom1">
    <a href="#"><img src="{{ asset('public/fronted/images/mega-offer-img.jpg') }}" alt="">  </a>
    </div>   
    </div>         
    </div>  
    </div>    
 </section>   
  @endsection


