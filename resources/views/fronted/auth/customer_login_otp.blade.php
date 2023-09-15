@extends('fronted.layouts.app_new')
@section('content')  
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Login With OTP</a>
@endsection  
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
<div class="register clearfix">
<!--<div class="welbcom">
    
<div class="col-md-6 col-sm-6 col-xs-12 pdnone">

<h6>Welcome Back </h6> 
<p>To keep connected with us please create the account with the required info</p>   
<a href="{{ route('login')}}"> <button type="submit" class="signupbtn"><i class="fa fa-lock"></i> Login</button>   </a>    
 
</div>    -->

<div class="registrform form-box-width">
<div class="login-white-box">
<h6>Verify Otp</h6>

  
		
    <form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    @csrf
		@include('admin.includes.form_error') 
		 <input type="hidden" id="OTPmethod" name="OTPmethod" value="0">
      	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['otp_field']); !!}
                <span class="customer_resend_login_otp"><i class="fa fa-exchange" aria-hidden="true"></i> Resend OTP</span>
                <span class="customer_resend_login_otp_message"></span>
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
		
  
    </form>  
   
    
</div>    
</div>
    
</div>
    
</div> 
    
</div>    
</div>    
</div>       
</section>    
@endsection
