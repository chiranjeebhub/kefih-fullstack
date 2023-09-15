@extends('fronted.layouts.vendor_layout')
@section('content')
<section class="register-section" style="background-image: url(images/inner-section-bg.png); background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-md-12">
<div class="sellerform">
<h6 class="mb40">Create your <span>Seller Account</span></h6>
    
    <form action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
           @csrf
    <div class="form-group">
    <input type="email" class="form-control"  name="email" id="email" placeholder="Enter Your Email Address" value="{{old('email')}}">
    </div>
        
    <div class="form-group">
    <input type="text" class="form-control" id="mobile" name="phone" placeholder="Enter Your Mobile Number" value="{{old('phone')}}">
    </div>    
        
   <p>By filling this form, I agree to <a href="#">Terms of Use</a> </p>
    <button type="submit" class="registrbtn"><i class="fa fa-user"></i> Sign Up</button>
    <p>Already a Seller? <a href="{{ route('vendor_login') }}">Login Here</a> </p>    
    </form>  
    
</div>    
</div>
    
</div>    
</div>    
    
</section> 

   
@endsection
