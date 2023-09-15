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
    <div class="row">
	
<div class="col-xs-12 col-sm-12 col-md-12">
<div class="contactform">
<h6>Get In <span>Touch</span></h6>    
     <form role="form" class="form-element" action="{{route('contact_us')}}" method="post">
			   @csrf
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <span class="help-block">
                <p style="color:red">{{$error}}</p>
            </span>
        @endforeach
		 
    @endif
		 
 
    
    
         <div class="row">
    <div class="col-sm-6">
             <div class="form-group">
    <input type="text" class="form-control" id="name" placeholder="Name" name="name">
    </div>
             </div>
    <div class="col-sm-6">
             <div class="form-group">
    <input type="text" class="form-control" id="mobile" placeholder="Mobile Number" name="phone">
    </div> 
             </div>
    </div>
       
        
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
    </div>
        
    <div class="form-group">
<textarea class="form-control" name="message" data-required-error="Please fill the field" placeholder="message"></textarea>
    </div>
    <div class="row">
    <div class="col-sm-6 col-sm-pull-0 col-md-4 col-md-pull-0"> 
    <input type="submit" name="subscription" value="Send Message" class="registrbtn">
    </div>
    </div>
  
   
    </form>      
</div>    
</div>
	</div>
		</div>
	</section>
</body>
</html>