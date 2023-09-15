@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<?php   
$dt=DB::table('meta_tags')->where('page_id',1)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Contact us</a>
@endsection       
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }} ; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container">
    <?php 
    $store_info=DB::table('store_info')->first();
    ?>
<div class="row">

<div class="col-xs-12 col-sm-5 col-md-4">
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
							<li><a class="fb" href="{{$store_info->facebook_url}}" target="_blank"><i class="fa fa-facebook"></i> </a> </li>
							<li><a class="tw" href="{{$store_info->twiter_url}}" target="_blank"><i class="fa fa-twitter"></i> </a> </li>                    
							<li><a class="linkdn" href="{{$store_info->linkedin_url}}" target="_blank"><i class="fa fa-linkedin"></i></a> </li>
                            <li><a class="youtube" href="#" target="_blank"><i class="fa fa-youtube"></i></a> </li>

						</ul>
					</div>
</div>    
</div>
<div class="col-xs-12 col-sm-7 col-md-8">
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
                 <label>Name</label>
    <input type="text" class="form-control" id="name" placeholder="" name="name">
    </div>
             </div>
    <div class="col-sm-6">
             <div class="form-group">
                 <label>Mobile Number</label>
    <input type="text" class="form-control" id="mobile" placeholder="" name="phone">
    </div> 
             </div>
    </div>
       
        
    <div class="form-group">
        <label>Email</label>
    <input type="email" class="form-control" id="email" placeholder="" name="email">
    </div>
        
    <div class="form-group">
        <label>Message</label>
        <textarea class="form-control" name="message" data-required-error="Please fill the field" placeholder=""></textarea>
    </div>
    <div class="row">
    <div class="col-sm-6 col-sm-pull-0 col-md-4 col-md-pull-0"> 
    <input type="submit" name="subscription" value="Send Message" class="btn btn-warning btn-block btn-lg">
    </div>
    </div>
  
   
    </form>      
</div>    
</div>    
    
</div>    
</div>       
</section>      
<section>
<!--<iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d15549.082440695503!2d77.61839967002575!3d13.018431653201299!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sNo%3A3%2F23%2C+2nd+Floor%2C+Anand+Complex%2C+Outer+Ring+Road%2C+Malagala%2C+Nagarbhavi+2nd+Stage%2C+Bengaluru%2C+Karanataka+%3A+560072!5e0!3m2!1sen!2sin!4v1566331329120!5m2!1sen!2sin" width="100%" height="350" frameborder="0" style="border:0" allowfullscreen></iframe>-->
</section> 
   
@endsection
