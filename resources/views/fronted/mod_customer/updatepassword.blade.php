@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home<i class="fa fa-long-arrow-right"></i></a>

<a href="javascript:void(0)">Update Passowrd</a>
@endsection 
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
<div class="registrform forgetpassword form-box-width">
 @include('admin.includes.form_error')
 <div class="login-white-box">
<h6>Update Password</h6>    
    <form action="{{route('update_password')}}" method="post"> 
 @csrf	
  <input type="hidden" id="OTPmethod" name="OTPmethod" value="1">
    <div class="form-group">
    <input type="text" class="form-control" id="password" placeholder="OTP" name="OTP">
    </div>   

<div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
    </div>   
@include('fronted.includes.otp_resend')	
  
    <button type="submit" class="registrbtn">Submit</button>
    </form>  
</div>
</div>    
</div>    
      
</div>    
</div>    
    
</section>       
@endsection    
