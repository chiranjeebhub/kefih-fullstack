@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">customized</a>
@endsection
<section class="about-section">
<div class="container-fluid">
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="bgwhite clearfix mr-btm-40">
			<div class="pro-listing">
				<div class="slide-heading">
					<h6 class="fs20 fw600">Customized</h6>   
				</div>  
				<ul class="row">
					<li class="col-xs-6 col-sm-4 col-md-3">
						<div class="product-grid8">
							<div class="product-image8">
								<img src="{{ asset('public/fronted/images/customized1.jpg') }}">
							</div>
							<div class="product-content">
								<h3 class="title"><a href="{{ route('customizedPageDetail')}}">Printed Men & Women Round Neck Yellow T-Shirt</a></h3>
								<a href="{{ route('customizedPageDetail')}}"  class="add-to-cart-btn">Place Order</a>
							</div>
						</div>
					</li>
					<li class="col-xs-6 col-sm-4 col-md-3">
						<div class="product-grid8">
							<div class="product-image8">
								<img src="{{ asset('public/fronted/images/customized2.jpg') }}">
							</div>
							<div class="product-content">
								<h3 class="title"><a href="{{ route('customizedPageDetail')}}">Men Checkered Casual Spread Shirt Blue</a></h3>
							<a href="{{ route('customizedPageDetail')}}"  class="add-to-cart-btn">Place Order</a>
							</div>
						</div>
					</li>
					<li class="col-xs-6 col-sm-4 col-md-3">
						<div class="product-grid8">
							<div class="product-image8">
								<img src="{{ asset('public/fronted/images/customized3.jpg') }}">
							</div>
							<div class="product-content">
								<h3 class="title"><a href="{{ route('customizedPageDetail')}}">Color Block Women Round Neck Multicolor T-Shirt</a></h3>
								<a href="{{ route('customizedPageDetail')}}"  class="add-to-cart-btn">Place Order</a>
							</div>
						</div>
					</li>
					<li class="col-xs-6 col-sm-4 col-md-3">
						<div class="product-grid8">
							<div class="product-image8">
								<img src="{{ asset('public/fronted/images/customized4.jpg') }}">
							</div>
							<div class="product-content">
								<h3 class="title"><a href="{{ route('customizedPageDetail')}}">Solid Men Round Neck Black, Dark Blue, Yellow T-Shirt</a></h3>
								<a href="{{ route('customizedPageDetail')}}"  class="add-to-cart-btn">Place Order</a>
								
							</div>
						</div>
					</li>
				</ul>
			</div>    
		</div>    
	</div>
</div>
</div>     
</section>  



 @endsection   
 