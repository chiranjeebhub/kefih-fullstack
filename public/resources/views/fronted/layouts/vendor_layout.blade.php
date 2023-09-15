<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('pageTitle')</title>
<meta name="title" content="@yield('metaTitle')"/>
<meta name="Keywords" content="@yield('metaKeywords')"/>
<meta name="Description" property="og:description" content="@yield('metaDescription')"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link rel="shortcut icon" href="{{ asset('public/fronted/images/favicon.ico') }}" type="image/x-icon" />
	
<link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/fronted/css/custom.css') }}" />
<link rel="stylesheet" href="{{ asset('public/fronted/css/styles.css') }}" type="text/css" media="all"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('public/fronted/css/owl.carousel.css') }}" />

<link rel="stylesheet" href="{{ asset('public/fronted/css/responsive.css') }}" />
<link rel="stylesheet" href="{{ asset('public/fronted/css/animate.css') }}"> 
<link rel="stylesheet" href="{{ asset('public/css/rateit.css') }}"> 
    
<link href="{{ asset('public/fronted/css/jquery.smartmenus.bootstrap.css') }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('public/fronted/css/easyzoom.css') }}">
<link rel="stylesheet" href="{{ asset('public/fronted/css/slick.css') }}" />
	
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,400i,500,700&display=swap" rel="stylesheet">    
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<style> 
   html {
    scroll-behavior: smooth;
}  
/*.shopping-cart .shopping-cart-items .item-price del{ padding-left: 10px;
    color: #999;
    font-size: 14px;}*/
    .shopping-cart .shopping-cart-items .item-price {
    margin-bottom: 8px;
    display: inline-block;
}
.color-sze{display: block;
    color: #999;
    font-size: 12px;
    margin-top: 5px;}
    .shopping-cart .shopping-cart-items .item-quantity {
    color: #999;
    font-size: 13px;
    display: block;
		margin-bottom: 8px;}
.hideCartButtton{
    display:none;
}
</style>
</head>

	
	
<body>
	
<div id="preloader" class=" ">
	<div class="preloader1"></div>
</div>
	


   
  @include('admin.includes.session_message') 
	@yield('content')


	@include('fronted.includes.foot')
	<footer class="main-footer">

	© 2020 <a href="javascript:void(0)">redlips.com</a>. All Rights Reserved.
	</footer>
@include('fronted.includes.script')
<script>    
    (function($) {

	// Testimonial Carousel
	if ($('.testimonial-carousel').length) {
		$('.testimonial-carousel').owlCarousel({
			animateOut: 'slideOutDown',
		    animateIn: 'zoomIn',
			loop:true,
			margin:0,
			nav:true,
			smartSpeed: 300,
			autoplay: 7000,
			navText: [ '<span class="arrow-left"></span>', '<span class="arrow-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				800:{
					items:1
				},
				1024:{
					items:1
				}
			}
		});  		
	}

	$(window).on('scroll', function() {
		headerStyle();
	});
	
	$(window).on('load', function() {
		handlePreloader();
		defaultMasonry();
	});	

})(window.jQuery);
    </script>

</body>
</html>
