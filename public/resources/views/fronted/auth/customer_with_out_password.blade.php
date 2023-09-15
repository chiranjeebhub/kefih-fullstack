@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Login</a>
@endsection

<section class="register-section" style="background-image: {{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
<div class="register clearfix">
<!--<div class="col-sm-6 col-xs-12 pdnone">

<h6>Welcome Back </h6> 
<p>To keep connected with us please create the account with the required info</p>   
<a href="{{ route('register')}}">    
<button type="submit" class="signupbtn"><i class="fa fa-user"></i>Create an account</button></a> 
</div>    -->

<div class="registrform form-box-width">
<div class="login-white-box">
<h6>Login</h6>
<!--<p>or use your phone for login</p>-->
    					
		@if(session()->has('message.level'))
		<div class=" alert_message alert alert-{{ session('message.level') }}">
		<strong>{{ ucfirst(session('message.level')) }} ,</strong>
		{!! session('message.content') !!}
		</div>
		@endif
       
   <form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    @csrf
    @include('admin.includes.form_error')
    <div class="form-group">
    <input type="text" class="form-control" id="email" name="phone"  placeholder="Enter Email Id / Mobile Number" value="">
    </div>
        
 
    <a href="{{ route('login')}}"><button type="submit" class="registrbtn"> Submit</button></a>   
    </form>  
	
	<!-- <h1 class="fs16 mb20 mt40">Or Login/Sign Up With</h1> 
<div class="login-socialmedia">   
<a class="fb" href="#"><span><i class="fa fa-facebook"></i></span> Facebook</a>  <a class="google" href="#"><span><i class="fa fa-google"></i></span>Google</a>    
</div> -->
</div>      
</div>
</div> 
    
</div>    
</div>    
</div>       
</section>        
@endsection

