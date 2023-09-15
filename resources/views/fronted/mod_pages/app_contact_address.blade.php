<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 
	
	<!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">-->
	
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
	
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title></title>
            <meta name="title" content=""/>
            <meta name="Keywords" content=""/>
	<meta name="csrf-token" content="b7k6ccJ1aIVdOorWLV26LFUdcj360NNiNAZD4Rd2">
	
	<link rel="shortcut icon" href="https://www.phaukat.com/public/fronted/images/favicon.png" />
	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="https://www.phaukat.com/public/fronted/css/owl.carousel.css" />
	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/custom.css" />
	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/megamenu/styles.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/animate.css">
	
	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/responsive.css" />
	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/rateit.css"> 

	<link rel="stylesheet" href="https://www.phaukat.com/public/fronted/css/jquery.fancybox.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
		
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156835066-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-156835066-1');
	</script>
	
<script type="text/javascript">
    if (window.location.hash && window.location.hash == '#_=_') {
        window.location.hash = '';
    }
</script>
</head>
<body style="touch-action: manipulation;">
	
	<section class="register-section" style="background-image:https://www.phaukat.com/public/fronted/images/inner-section-bg.png ; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container">
	
	<?php 
    $store_info=DB::table('store_info')->first();
    ?>
	
    <div class="row">
	
<div class="col-xs-12 col-sm-12 col-md-12">
<div class="contactinfo">
<h6>Contact Info</h6> 
<div class="mainbox media">
<div class="iconbox media-left">
<i class="fa fa-mobile"></i>    
</div>
<div class="icontext media-body">
    <h5>Call Us</h5>   
    <h4><a href="tel:+91{{$store_info->phone}}">+91 {{$store_info->phone}}</a></h4>
</div>     
</div>   
<div class="mainbox media">
<div class="iconbox media-left">
<i class="fa fa-envelope"></i>      
</div>
<div class="icontext media-body">
    <h5>Email Id</h5>
<h4><a href="mailto:{{$store_info->email}}">{{$store_info->email}} </a> </h4>
</div>     
</div>   
<div class="mainbox media">
<div class="iconbox media-left">
<i class="fa fa-location-arrow"></i>      
</div>
<div class="icontext media-body">
    <h5>Address</h5>
<h4>{{$store_info->address}}</h4>
</div>    
</div>   
                    
						<div class="contact-social">
                            <h6>Connect with us</h6>
							<ul class="list-inline">
							<li><a class="fb" href="{{$store_info->facebook_url}}"><i class="fa fa-facebook"></i> </a> </li>
							<li><a class="tw" href="{{$store_info->twiter_url}}"><i class="fa fa-twitter"></i> </a> </li>
                            <li><a class="snap" href="{{$store_info->snapchat_url}}"><i class="fa fa-snapchat-ghost"></i></a></li>
							<li><a class="ins" href="{{$store_info->linkedin_url}}"><i class="fa fa-instagram"></i></a> </li>
						</ul>
					</div>
</div>  
</div>
	</div>
		</div>
	</section>

	
	
	</body>
</html>


