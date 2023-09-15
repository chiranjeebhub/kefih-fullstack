@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>

<a href="javascript:void(0)">Forgot Passowrd</a>
@endsection   
<section class="register-section" style="background-image:{{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 pdnone">
<div class="registrform forgetpassword">
 @include('admin.includes.form_error')
<h6>Forgot Password</h6>    
    <form action="{{route('forgot_password')}}" method="post"> 
 @csrf	
    <div class="form-group">
    <input type="text" class="form-control" id="password" placeholder="Phone/Email" name="phone">
    </div>        
  
    <button type="submit" class="registrbtn"> Submit</button>
    </form>  
    
</div>    
</div>    
      
</div>    
</div>    
    
</section>       
@endsection    
