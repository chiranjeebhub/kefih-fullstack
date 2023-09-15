@extends('fronted.layouts.app_new')
@section('content')  
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Register</a>
@endsection  
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container">
<div class="row">    
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
<div class="register clearfix">
    <!--
<div class="col-md-6 col-sm-6 col-xs-12 pdnone">

<h6>Welcome Back </h6> 
<p>To keep connected with us please create the account with the required info</p>   
<a href="{{ route('login')}}"> <button type="submit" class="signupbtn"><i class="fa fa-lock"></i> Login</button>   </a>    
 
   
</div>
-->

<div class="registrform form-box-width">
<div class="login-white-box">
<h6>Create your account </h6>

    	
		
    <form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    @csrf
	
        <div class="row">
        <div class="col-sm-12 col-xs-12">
            <label>Enter your email ID</label>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}
                 @if($errors->has('email'))
                <span style="color:red;position: absolute;margin-top: -16px;">  {{ $errors->first('email') }}</span>
             @endif
            </div>
            <div class="col-sm-12 col-xs-12">
                <label>Enter Name</label>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['name_field']); !!}
                @if($errors->has('name'))
                    <span style="color:red;position: absolute;margin-top: -16px;">  {{ $errors->first('name') }}</span>
                @endif
            </div>
            <!--<div class="col-sm-12 col-xs-12">
                <div class="form-group">
                    <label>Your country</label>
                    <input type="text" class="form-control" id="" name="phone"  placeholder="Type here " value="">
                </div>
            </div>-->
            <div class="col-sm-12 col-xs-12">
                <label>Please enter your mobile no.</label>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phone_field']); !!}
                 @if($errors->has('phone'))
                    <span style="color:red;position: absolute;margin-top: -16px;">  {{ $errors->first('phone') }}</span>
                @endif
            </div>
            
           <div class="col-sm-12 col-xs-12">
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['password_field']); !!}
                  @if($errors->has('password'))
                    <span style="color:red;position: absolute;margin-top: -16px;">  {{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="col-sm-12 col-xs-12">{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['confirm_password_field']); !!}
              @if($errors->has('confirm_password'))
                    <span style="color:red;position: absolute;margin-top: -16px;">  {{ $errors->first('confirm_password') }}</span>
                @endif
            </div>
        </div>
      	
		<div class="paymnetthod">
                    <div class="checkbox checkbox-circle">
                    <input id="test2" name="term_and_condition" type="checkbox" checked>
                    <label for="test2"> Agree to <a href="{{route('page_url',['terms'])}}" target="_blank" rel="noreferrer">Terms & Conditions.</a> </label>
                    </div>
        </div>
			
			<!--{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['r_code_field']); !!}-->
		<div class="paymnetthod">
			<div class="checkbox checkbox-circle">
				<input id="test1" name="radio-group" type="checkbox">
				<label for="test1">Have a Referral Code ?</label>
				<div id="dvPassport" style="display: none">
				<input class="form-control" type="text" name="r_code" value="" placeholder="Referral Code"> 
			</div>
			</div>
		</div>
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
		
		
		<!--<div class="showhidediv">
			<input type="checkbox" id="test1" name="">
			<label for="test1">Have Referal Code ?</label>
			<div id="dvPassport" style="display: none">
				<input class="form-control" type="text" value=" " placeholder="Enter code"> 
			</div>
		</div>-->
        <div class="text-center" style="margin-top:15px;">
    <p>Already have an account? <a href="{{ route('login')}}"> Login </a></p>
    </div> 
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

