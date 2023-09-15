@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>

<a href="javascript:void(0)">Forgot Password</a>
@endsection   
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
    <div class="">
<div class="registrform forgetpassword form-box-width">
<div class="login-white-box">
 @include('admin.includes.form_error')
<h6>Forgot password</h6>    
    <form action="{{route('forgot_password')}}" method="post"> 
 @csrf	
    <div class="form-group">
    <label>Enter email Id</label>
    <input type="email" class="form-control" id="phone" placeholder="Enter Email ID" name="phone">
    <div class="form-icon"><i class="fa fa-envelope-o"></i></div>
    </div> 

  {{--
    <div class="form-group">
        <label>Password</label>
    <input type="text" class="form-control" id="password" placeholder="Password" name="phone">
    <div class="form-icon"><i class="fa fa-lock"></i></div>
    </div>        
    <div class="form-group">
        <label>Confirm Password</label>
    <input type="text" class="form-control" id="password" placeholder="Confirm Password" name="phone">
    <div class="form-icon"><i class="fa fa-lock"></i></div>
    </div>  
    --}}
    <button type="submit" class="registrbtn"> Submit</button>
    </form>  
</div>
</div>    
    </div>
</div>    
      
</div>    
</div>    
    
</section>       
@endsection    
