@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection     
<section class="register-section" style="background-image: {{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-md-12">
<div class="sellerform">
<h6 class="mb40">Create your <span>Seller Account</span></h6>
    
    <form action="">
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Enter Your Email Address">
    </div>
        
    <div class="form-group">
    <input type="text" class="form-control" id="mobile" placeholder="Enter Your Mobile Number">
    </div>    
        
    <div class="form-group">
    <input type="text" class="form-control" id="name" placeholder="Enter Your Full Name">
    </div>
        
    <div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Set Password">
    </div>
   <p>By filling this form, I agree to <a href="#">Terms of Use</a> </p>
    <button type="submit" class="registrbtn"><i class="fa fa-user"></i> Sign Up</button>
    <p >Already a Seller? <a href="become-an-seller.html">Login Here</a> </p>    
    </form>    
</div>    
</div>    
</div>    
</div>        
</section>    
@endsection   
