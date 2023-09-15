<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>@yield('pageTitle')</title>
	<meta name="title" content="@yield('metaTitle')"/>
	<meta name="Keywords" content="@yield('metaKeywords')"/>
		<meta name="Description" content="@yield('metadescription')"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('public/fronted/images/favicon.png') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap.min.css') }}" /><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /><link rel="stylesheet" type="text/css" href="{{ asset('public/fronted/css/owl.carousel.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/custom.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/megamenu/styles.css') }}" type="text/css" media="all" /><link rel="stylesheet" href="{{ asset('public/fronted/css/animate.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/responsive.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/rateit.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/jquery.fancybox.min.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap-select.min.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/selectize.bootstrap3.min.css') }}" />
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156835066-1"></script>-->
	<!--<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-156835066-1');</script>-->
	
	<script type="text/javascript">if (window.location.hash && window.location.hash == '#_=_') {window.location.hash = '';}</script>
	
	
	<!-- Google Tag Manager -->

<!-- End Google Tag Manager -->




<!-- Google Tag Manager (noscript) -->

<!-- End Google Tag Manager (noscript) -->

	<!-- Facebook Pixel Code -->





<!-- End Facebook Pixel Code -->


</head>
<body style="touch-action: manipulation;">
<div id="loader"><img src="{{ asset('public/fronted/images/loader.gif') }}" width="50px"></div>
<div class="bd-example slider-main"><header class="" data-spy="affix" data-offset-top="50"> @include('fronted.includes.header') @include('fronted.mod_cart.cart')</header>
@yield('slider')
</div>   
<div class="add-section1"></div>
<?php 
$crt=url()->current();
$proper_url_array=explode('/',$crt);
$str=end($proper_url_array);
?>
@if($str!='index' && $str!='phaukat' )
<section class="inner-section"><div class="container"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="inner-banner"><div class="breadcrumb">@yield('breadcrum')</div></div></div></div></div></section>
@endif()
  
  @include('admin.includes.session_message') 
	
	@yield('content')  
	@include('fronted.includes.footer')	
	@include('fronted.includes.foot')
	@include('fronted.includes.addtocartscript')	
	@include('fronted.includes.script')	
	@yield('scripts')
<div class="quickview-model"><div class="modal fade fadeInUp animated" id="quickVieweModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body quickViewResponse"></div></div></div></div></div>
<div class="quickview-model"><div class="modal fade fadeInUp animated" id="phuakatCoinsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body phuakatCoinsResponse"></div></div></div></div></div>
<div class="cartpopup"><div class="modal fade fadeInUp animated" id="wishlistModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body wishlistModalResponse"></div></div></div></div></div>
<div class="sizechart-model"><div class="modal fade fadeInUp animated" id="showsizechart" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showsizechartResponse"></div></div></div></div></div></div></div>
</body></html>
