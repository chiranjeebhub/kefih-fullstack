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
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="register clearfix">
<div class="col-md-6 pdnone">
<div class="welbcom">
<h6>Welcome Back </h6> 
<p>To keep connected with us please create the account with the required info</p>   
<a href="{{ route('register')}}">    
<button type="submit" class="signupbtn"><i class="fa fa-user"></i>Create an account</button></a> 
</div>    
</div>
<div class="col-md-6 pdnone">
<div class="registrform">
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
        
    <div class="form-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="">
    </div>
    <div class="form-group">
        <a id="otpclick" href="{{ route('OTP_login')}}" style="text-align:left; float:left;">Login with OTP </a> <a style="text-align:right; float:right;" href="{{ route('forgot_password')}}">Forgot your  password?</a>
    </div>
    <a href="{{ route('login')}}"><button type="submit" class="registrbtn"><i class="fa fa-lock"></i> Login</button></a>   
    </form>  
	
	 <h1 class="fs16 mb20 mt40">Or Login/Sign Up With</h1> 
<div class="login-socialmedia">   
<?php /*
<a class="fb" href="{{route('redirect_fb')}}"><span><i class="fa fa-facebook"></i></span> Facebook</a>  
*/
?>
<a class="google" href="{{route('redirect_gp')}}"><span><i class="fa fa-google"></i></span>Google</a>    
</div> 
</div>    
</div>    
</div> 
    
</div>    
</div>    
</div>       
</section>        
@endsection

